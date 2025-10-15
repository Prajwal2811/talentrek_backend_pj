<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentHelper;
use App\Models\PaymentHistory;
use App\Models\Jobseekers;
use App\Models\Setting;
use App\Models\Expat;
use App\Models\CorporatesEmailIds;
use App\Models\JobseekerCartItem;
use App\Models\TrainingBatch;
use App\Models\TeamCourseMember;
use App\Models\TrainingMaterial;
use App\Models\PurchasedSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

use App\Models\JobseekerTrainingMaterialPurchase;
use App\Models\Trainers;
use App\Models\JobseekerTrainingMaterialPurchasePaymentRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CoursePurchaseController extends Controller
{
    /**
     * Process Course Payment
     */
    public function processPurchaseCoursePayment(Request $request)
{
    // Determine logged-in user type
    if (Auth::guard('jobseeker')->check()) {
        $userType = 'jobseeker';
        $userId = Auth::guard('jobseeker')->id();
    } elseif (Auth::guard('expat')->check()) {
        $userType = 'expat';
        $userId = Auth::guard('expat')->id();
    } else {
        return redirect()->back()->with('error', 'Please login to purchase a course.');
    }

    // Check subscription
    $subscription = PurchasedSubscription::where('user_id', $userId)
        ->where('user_type', $userType)
        ->where('payment_status', 'paid')
        ->orderBy('end_date', 'desc')
        ->first();

    if (!$subscription || Carbon::parse($subscription->end_date)->isPast()) {
        return redirect()->back()->with('error', 'You need an active subscription to book a session.');
    }

    // Validate input
    $validator = Validator::make($request->all(), [
        'buy_type'       => 'required|in:buyNow,cart,corporate',
        'material_id'    => 'required|exists:training_materials,id',
        'training_type'  => 'required|in:online,classroom,recorded',
        'batch_id'       => 'nullable|required_if:training_type,online|required_if:training_type,classroom',
        'original_price' => 'required|numeric|min:0',
        'tax_rate'       => 'nullable|numeric|min:0',
        'coupon_code'    => 'nullable|string',
        'coupon_amount'  => 'nullable|numeric|min:0',
        'coupon_type'    => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->with('error', $validator->errors()->first());
    }

    $material = TrainingMaterial::findOrFail($request->material_id);

    $offerPrice = floatval($request->original_price ?? $material->training_offer_price ?? $material->training_price);
    $taxRate    = floatval($request->tax_rate ?? Setting::first()->trainingMaterialTax );
    $tax        = round($offerPrice * ($taxRate / 100), 2);
    $amountPaid = round($offerPrice + $tax - ($request->coupon_amount ?? 0), 2);

    // Generate unique reference
    $referenceNo = "TRK-COURSE-" 
        . strtoupper(substr($material->training_type, 0, 3)) 
        . '-' . $material->id
        . '-' . ($request->batch_id)
        . '-' . $userId
        . '-' . date('YmdHi')
        . '-' . time();

    // Create payment request
    $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
        'jobseeker_id'        => $userId,
        'user_type'      => $userType,
        'trainer_id'     => $material->trainer_id,
        'material_id'    => $material->id,
        'batch_id'       => $request->batch_id ?? null,
        'request_payload'=> null,
        'track_id'       => $referenceNo,
        'type'           => $request->buy_type,
        'training_type'  => $material->training_type,
        'transaction_id' => null,
        'payment_status' => 'initiated',
        'taxed_amount'   => $tax,
        'tax_percentage' => $taxRate,
        'amount'         => $offerPrice,
        'amount_paid'    => $amountPaid,
        'currency'       => 'SAR',
        'payment_gateway'=> 'Al Rajhi',
        'coupon_type'    => $request->coupon_type ?? null,
        'coupon_code'    => $request->coupon_code ?? null,
        'coupon_amount'  => $request->coupon_amount ?? 0,
    ]);

    // Prepare Neoleap transaction
    $config = config('neoleap');
    $transactionDetails = [
        "id"          => $config['tranportal_id'],
        "amt"         => $amountPaid,
        "action"      => "1",
        "password"    => $config['password'] ?? "T4#2H#ma5yHv\$G7",
        "currencyCode"=> "682",
        "trackId"     => $referenceNo,
        "langid"      => "en",
        "responseURL" => $config['course_success_url'],
        "errorURL"    => $config['course_failure_url'],
        "udf1"        => $userId,
        "udf2"        => $request->buy_type,
        "udf3"        => $paymentRequest->id,
        "udf5"        => 'course',
    ];

    $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
    $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));
    $paymentRequest->update(['request_payload' => $jsonTrandata]);

    // Final payload
    $payload = json_encode([[ 
        "id"          => $config['tranportal_id'],
        "trandata"    => $trandata,
        "responseURL" => $config['course_success_url'],
        "errorURL"    => $config['course_failure_url']
    ]], JSON_UNESCAPED_SLASHES);

    // Redirect to Neoleap payment
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL         => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST        => true,
        CURLOPT_POSTFIELDS  => $payload,
        CURLOPT_HTTPHEADER  => ['Content-Type: application/json'],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    $data   = json_decode($response, true);
    $result = $data[0]['result'] ?? null;

    if ($result) {
        [$paymentId, $paymentUrl] = explode(":", $result, 2);
        return redirect()->away($paymentUrl . "?PaymentID=" . $paymentId);
    }

    return redirect()->back()->with('error', 'Unable to initiate payment. Please try again.');
}

/**
 * Success callback
 */
public function successPurchaseCourse(Request $request)
{
    $config = config('neoleap');
    $responseTrandata = $request->input('trandata');

    if (!$responseTrandata) {
        return redirect()->back()->with('error', 'Missing payment response.');
    }

    // Decrypt response
    $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
    $data = json_decode($decrypted, true);

    if (!$data || !isset($data[0])) {
        return redirect()->back()->with('error', 'Invalid payment response.');
    }

    $data = $data[0];

    // Find original payment request
    $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
    if (!$paymentRequest) {
        return redirect()->back()->with('error', 'Payment request not found.');
    }

    // User info
    $userType  = $paymentRequest->user_type ?? 'jobseeker';
    $userId    = $paymentRequest->jobseeker_id;
    $userModel = $userType === 'expat' ? Expat::class : Jobseekers::class;

    // Update payment request status
    $paymentStatus = $data['result'] === 'CAPTURED' ? 'success' : 'failed';
    $paymentRequest->update([
        'transaction_id'   => $data['transId'] ?? null,
        'payment_status'   => $paymentStatus,
        'response_payload' => json_encode($data),
    ]);

    if ($paymentStatus === 'success') {
        // Payment history
        $paymentHistory = PaymentHistory::create([
            'user_type'      => $userType,
            'user_id'        => $userId,
            'receiver_type'  => 'trainer',
            'receiver_id'    => $paymentRequest->trainer_id,
            'payment_for'    => 'training',
            'amount_paid'    => $paymentRequest->amount_paid,
            'taxed_amount'   => $paymentRequest->taxed_amount,
            'tax_percentage'   => $paymentRequest->tax_percentage,
            'applied_coupon' => $paymentRequest->coupon_code,
            'payment_status' => 'completed',
            'transaction_id' => $paymentRequest->transaction_id,
            'track_id'       => $paymentRequest->track_id,
            'order_id'       => 'ORD-' . $userId . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
            'currency'       => 'SAR',
            'payment_method' => $paymentRequest->payment_gateway,
            'paid_at'        => now(),
        ]);

        // Purchase record
        JobseekerTrainingMaterialPurchase::create([
            'jobseeker_id'    => $userId,
            'user_type'       => $userType,
            'trainer_id'      => $paymentRequest->trainer_id,
            'material_id'     => $paymentRequest->material_id,
            'purchased_by'    => $userId,
            'training_type'   => $paymentRequest->training_type,
            'session_type'    => $paymentRequest->training_type,
            'batch_id'        => $paymentRequest->batch_id,
            'purchase_for'    => 'individual',
            'payment_id'      => $paymentHistory->id,
            'batchStatus'     => 'active',
            'status'          => 'active',
            'tax_percentage'  => $paymentRequest->tax_percentage,
            'taxed_amount'    => $paymentRequest->taxed_amount,
            'amount_paid'     => $paymentRequest->amount_paid,
            'coupon_type'     => $paymentRequest->coupon_type,
            'coupon_code'     => $paymentRequest->coupon_code,
            'coupon_amount'   => $paymentRequest->coupon_amount,
            'order_id'        => 'ORD-' . $userId . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
            'track_id'        => $paymentRequest->track_id,
            'transaction_id'  => $paymentRequest->transaction_id,
            'payment_status'  => 'success',
            'response_payload'=> json_encode($data),
            'member_count'    => 1,
        ]);

        // Auto-login user if needed
        if ($user = $userModel::find($userId)) {
            Auth::guard($userType)->login($user);
        }
    }

    // Redirect to profile
    $redirectRoute = $userType === 'expat' ? 'expat.profile' : 'jobseeker.profile';
    return redirect()->route($redirectRoute)->with('success', 'Course purchased successfully!');
}




    /**
     * Failure callback
     */
    public function failurePurchaseCourse(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        $userType = 'jobseeker'; // default
        $userId = null;

        if ($responseTrandata) {
            $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
            $data = json_decode($decrypted, true);
            $data = $data[0] ?? [];

            if (!empty($data['trackId'])) {
                $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();

                if ($payment) {
                    $status = in_array($data['result'], ['NOT CAPTURED', 'DECLINED', 'FAILED', 'CANCELED'])
                        ? 'failed'
                        : 'pending';

                    $payment->update([
                        'transaction_id'   => $data['transId'] ?? null,
                        'payment_status'   => $status,
                        'response_payload' => json_encode($data),
                    ]);

                    // 🔑 Determine user type and ID from payment request
                    $userType = $payment->user_type ?? 'jobseeker';
                    $userId = $payment->user_id;
                }
            }
        }

        // 🔑 Log in the correct user guard
        if ($userId) {
            $userModel = $userType === 'expat' ? Expat::class : Jobseekers::class;
            if ($user = $userModel::find($userId)) {
                Auth::guard($userType)->login($user);
            }
        }

        // 🔑 Redirect to the correct profile page
        $redirectRoute = $userType === 'expat' ? 'expat.profile' : 'jobseeker.profile';
        return redirect()->route($redirectRoute)->with('error', 'Payment failed or cancelled. Please try again.');
    }



    /**
     * Process Team Course Purchase Payment
     */
    public function processPurchaseCoursePaymentForteam(Request $request)
    {
        // Determine user type and ID
        $userType = null;
        $userId = null;

        if (Auth::guard('jobseeker')->check()) {
            $userType = 'jobseeker';
            $userId = Auth::guard('jobseeker')->id();
        } elseif (Auth::guard('expat')->check()) {
            $userType = 'expat';
            $userId = Auth::guard('expat')->id();
        }

        if (!$userId) {
            return redirect()->back()->with('error', 'Please login to purchase a course.');
        }

        // Check subscription
        $subscription = PurchasedSubscription::where('user_id', $userId)
            ->where('user_type', $userType)
            ->where('payment_status', 'paid')
            ->orderBy('end_date', 'desc')
            ->first();

        if (!$subscription || Carbon::parse($subscription->end_date)->isPast()) {
            return redirect()->back()->with('error', 'You need an active subscription to book a session.');
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'material_id'     => 'required|exists:training_materials,id',
            'training_type'   => 'required|in:online,classroom,recorded',
            'batch_id'        => 'nullable|required_if:training_type,online|required_if:training_type,classroom',
            'member_count'    => 'required|integer|min:2',
            'member_emails.*' => 'required|email',
            'original_price'  => 'required|numeric|min:0',
            'tax_rate'        => 'nullable|numeric|min:0',
            'coupon_code'     => 'nullable|string',
            'coupon_amount'   => 'nullable|numeric|min:0',
            'coupon_type'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $material    = TrainingMaterial::findOrFail($request->material_id);
        $memberCount = intval($request->member_count);
        $offerPrice  = floatval($request->original_price ?? $material->training_offer_price ?? $material->training_price);
        $taxRate     = floatval($request->tax_rate ?? Setting::first()->trainingMaterialTax ?? 0);
        $tax         = round($offerPrice * $memberCount * ($taxRate / 100), 2);
        $amountPaid  = round(($offerPrice * $memberCount) + $tax - ($request->coupon_amount ?? 0), 2);

        // Generate unique reference
        $referenceNo = "TRK-TEAM-" 
                        . strtoupper(substr($material->training_type, 0, 3)) 
                        . '-' . $material->id
                        . '-' . ($request->batch_id ?? '0') 
                        . '-' . $userId
                        . '-' . date('YmdHi') 
                        . '-' . time();

        // Create payment request
        $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
            'jobseeker_id'    => $userId,
            'user_type'       => $userType,
            'trainer_id'      => $material->trainer_id,
            'material_id'     => $material->id,
            'batch_id'        => $request->batch_id ?? null,
            'request_payload' => null,
            'track_id'        => $referenceNo,
            'type'            => 'buyForCorporate',
            'training_type'   => $material->training_type,
            'transaction_id'  => null,
            'payment_status'  => 'initiated',
            'taxed_amount'    => $tax,
            'tax_percentage'  => $request->tax_rate ?? Setting::first()->trainingMaterialTax,
            'amount'          => $offerPrice * $memberCount,
            'amount_paid'     => $amountPaid,
            'currency'        => 'SAR',
            'payment_gateway' => 'Al Rajhi',
            'coupon_type'     => $request->coupon_type ?? null,
            'coupon_code'     => $request->coupon_code ?? null,
            'coupon_amount'   => $request->coupon_amount ?? 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Save team member emails
        $corporatesEmailIds = CorporatesEmailIds::create([
            'corporatesEmailIds' => $request->member_emails,
            'paymentRequestId'   => $paymentRequest->id,
            'track_id'           => $referenceNo,
        ]);

        // Prepare Neoleap transaction
        $config = config('neoleap');
        $transactionDetails = [
            "id"           => $config['tranportal_id'],
            "amt"          => $amountPaid,
            "action"       => "1",
            "password"     => "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682",
            "trackId"      => $referenceNo,
            "langid"       => "en",
            "udf1"         => $userId,
            "udf2"         => $userType,
            "udf3"         => $paymentRequest->id,
            "udf4"         => $request->training_type ?? 'recorded',
            "udf5"         => $request->training_type,
            "udf6"         => $request->material_id,
            "udf7"         => $tax ?? 0.00,
            "udf8"         => $offerPrice,
            "udf9"         => $corporatesEmailIds->id ?? '',
            "udf10"        => $request->trainer_id,
            "responseURL"  => $config['team_course_success_url'],
            "errorURL"     => $config['team_course_failure_url'],
        ];

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));
        $paymentRequest->update(['request_payload' => $jsonTrandata, 'track_id'=> $referenceNo]);

        $payloads = json_encode([[ 
            "id"         => $config['tranportal_id'],
            "trandata"   => $trandata,
            "responseURL"=> $config['team_course_success_url'],
            "errorURL"   => $config['team_course_failure_url']
        ]], JSON_UNESCAPED_SLASHES);

        // Redirect to Neoleap payment
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $config['curlopt_url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payloads,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        $result = $data[0]['result'] ?? null;

        if ($result) {
            [$paymentId, $paymentUrl] = explode(":", $result, 2);
            return redirect()->away($paymentUrl . "?PaymentID=" . $paymentId);
        }

        return redirect()->back()->with('error', 'Unable to initiate team payment. Please try again.');
    }




    /**
     * Team Course Success
     */
    public function successPurchaseCourseForTeam(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if (!$responseTrandata) {
            return redirect()->back()->with('error', 'Missing payment response.');
        }

        // Decrypt payment response
        $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $dataArray = json_decode($decrypted, true);
        $data = $dataArray[0] ?? null;

        if (!$data) {
            return redirect()->back()->with('error', 'Invalid payment response.');
        }

        // Fetch original payment request
        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        // Determine user type
        $userType = $payment->type === 'buyForCorporate' && $data['udf2'] ? $data['udf2'] : 'jobseeker';
        $userId   = $payment->jobseeker_id;

        // Update payment request status
        $payment->update([
            'transaction_id'   => $data['transId'] ?? null,
            'payment_status'   => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
            'response_payload' => json_encode($data),
        ]);

        if ($data['result'] !== 'CAPTURED') {
            return redirect()->route($userType . '.profile')->with('error', 'Team payment failed. Please try again.');
        }

        // Create main purchase record
        $purchase = JobseekerTrainingMaterialPurchase::firstOrCreate(
            ['track_id' => $payment->track_id],
            [
                'user_type'       => $userType,
                'jobseeker_id'   => $userId,
                'trainer_id'     => $payment->trainer_id,
                'material_id'    => $payment->material_id,
                'purchased_by'   => $userId,
                'training_type'  => $payment->training_type,
                'session_type'   => $payment->training_type,
                'batch_id'       => $payment->batch_id,
                'purchase_for'   => 'team',
                'payment_id'     => $payment->id,
                'batchStatus'    => 'active',
                'status'         => 'active',
                'tax_percentage' => $payment->tax_percentage ?? 0,
                'taxed_amount'   => $payment->taxed_amount,
                'amount_paid'    => $payment->amount_paid,
                'coupon_type'    => $payment->coupon_type,
                'coupon_code'    => $payment->coupon_code,
                'coupon_amount'  => $payment->coupon_amount,
                'order_id'       => 'ORD-' . $userId . '-' . $payment->material_id . '-' . now()->format('YmdHi'),
                'track_id'       => $payment->track_id,
                'transaction_id' => $payment->transaction_id,
                'payment_status' => 'success',
                'response_payload'=> json_encode($data),
                'member_count'   => $request->member_count,
            ]
        );

        // Handle corporate emails
        $corporates = CorporatesEmailIds::where('track_id', $data['trackId'])->first();
        if ($corporates) {
            $savedEmails = $corporates->corporatesEmailIds ?? [];
            $corporates->update(['successPaymentId' => $purchase->id]);

            foreach ($savedEmails as $memberEmail) {
                if (empty($memberEmail)) continue;

                // Check if user already exists
                $member = $userType === 'expat'
                    ? Expat::firstOrCreate(['email' => $memberEmail], [
                        'password' => bcrypt(explode('@', $memberEmail)[0] . '@talentrek'),
                        'pass'     => explode('@', $memberEmail)[0] . '@talentrek',
                        'role'    => 'expat',
                    ])
                    : Jobseekers::firstOrCreate(['email' => $memberEmail], [
                        'password' => bcrypt(explode('@', $memberEmail)[0] . '@talentrek'),
                        'pass'     => explode('@', $memberEmail)[0] . '@talentrek',
                        'role'    => 'jobseeker',
                    ]);

                // Save a separate purchase record for this team member
                JobseekerTrainingMaterialPurchase::firstOrCreate(
                    [
                        'jobseeker_id' => $member->id,
                        'material_id'  => $payment->material_id,
                        'batch_id'     => $payment->batch_id,
                        'purchase_for' => 'team',
                        'user_type'    => $userType,
                    ],
                    [
                        'purchased_by'     => $userId,
                        'trainer_id'       => $payment->trainer_id,
                        'training_type'    => $payment->training_type,
                        'session_type'     => $payment->training_type,
                        'batchStatus'      => 'active',
                        'status'           => 'active',
                        'tax_percentage'   => $payment->tax_percentage,
                        'taxed_amount'     => $payment->taxed_amount,
                        'amount_paid'      => $payment->amount_paid,
                        'coupon_type'      => $payment->coupon_type,
                        'coupon_code'      => $payment->coupon_code,
                        'coupon_amount'    => $payment->coupon_amount,
                        'order_id'         => 'ORD-' . $member->id . '-' . $payment->material_id . '-' . now()->format('YmdHi'),
                        'track_id'         => $payment->track_id . '-' . $member->id,
                        'transaction_id'   => $payment->transaction_id,
                        'payment_status'   => 'success',
                        'response_payload' => json_encode($data),
                        'member_count'     => 1,
                    ]
                );
            }
        }

        // Create payment history
        PaymentHistory::create([
            'user_type'      => $userType,
            'user_id'        => $userId,
            'receiver_type'  => 'trainer',
            'receiver_id'    => $payment->trainer_id,
            'payment_for'    => 'training',
            'amount_paid'    => $payment->amount_paid,
            'taxed_amount'   => $payment->taxed_amount ?? 0,
            'tax_percentage' => $payment->tax_percentage ?? 0,
            'applied_coupon' => $payment->coupon_code,
            'payment_status' => 'completed',
            'transaction_id' => $payment->transaction_id,
            'track_id'       => $payment->track_id,
            'order_id'       => 'ORD-' . $userId . '-' . $payment->material_id . '-' . now()->format('YmdHi'),
            'currency'       => 'SAR',
            'payment_method' => $payment->payment_gateway,
            'paid_at'        => now(),
        ]);

        // Auto-login main user
        if ($userType === 'expat') {
            $mainUser = Expat::find($userId);
            Auth::guard('expat')->login($mainUser);
        } else {
            $mainUser = Jobseekers::find($userId);
            Auth::guard('jobseeker')->login($mainUser);
        }

        return redirect()->route($userType . '.profile')->with('success', 'Team course purchased successfully!');
    }

    /**
     * Team Course Failure
     */
    public function failurePurchaseCourseForTeam(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        $userType = 'jobseeker'; // default
        $userId   = null;

        if ($responseTrandata) {
            $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
            $data = json_decode($decrypted, true)[0] ?? [];

            if (!empty($data['trackId'])) {
                $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();

                if ($payment) {
                    // Determine user type from payment
                    $userType = $data['udf2'] ?? 'jobseeker';
                    $userId   = $payment->jobseeker_id;

                    $status = in_array($data['result'], ['NOT CAPTURED', 'DECLINED', 'FAILED', 'CANCELED'])
                        ? 'failed'
                        : 'pending';

                    $payment->update([
                        'transaction_id'   => $data['transId'] ?? null,
                        'payment_status'   => $status,
                        'response_payload' => json_encode($data),
                    ]);
                }
            }

            // 🔑 Log the user in
            if ($userId) {
                if ($userType === 'expat') {
                    $user = Expat::find($userId);
                    if ($user) {
                        Auth::guard('expat')->login($user);
                    }
                } else {
                    $user = Jobseekers::find($userId);
                    if ($user) {
                        Auth::guard('jobseeker')->login($user);
                    }
                }
            }
        }

        return redirect()->route($userType . '.profile')->with('error', 'Team payment failed or cancelled. Please try again.');
    }


    /**
     * Process payment for all cart items
     */
    // Process Cart Payment (multiple materials)
   
    public function processPurchaseCoursePaymentCart(Request $request)
    {
        // ✅ Detect user type (jobseeker or expat)
        $userType = Auth::guard('jobseeker')->check() ? 'jobseeker' : (Auth::guard('expat')->check() ? 'expat' : null);
        $userId   = Auth::guard($userType)->id();

        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to proceed with payment.');
        }

        $materialIds = $request->input('material_ids', []);
        $batchIds    = $request->input('batch_ids', []);
        $offerPrices = $request->input('offer_prices', []);

        if (empty($materialIds)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $taxRate       = floatval(Setting::first()->trainingMaterialTax ?? 0);
        $totalAmount   = 0;
        $totalSaved    = 0;
        $hasEndedBatch = false;

        // ✅ Calculate total amount and validate batches
        foreach ($materialIds as $index => $materialId) {
            $material = TrainingMaterial::find($materialId);
            if (!$material) continue;

            $batchId    = $batchIds[$index] ?? null;
            $offerPrice = floatval($offerPrices[$index] ?? $material->training_price);

            $totalAmount += $offerPrice;
            $totalSaved  += ($material->training_price - $offerPrice);

            if ($batchId) {
                $batch = TrainingBatch::where('id', $batchId)
                    ->where('training_material_id', $material->id)
                    ->first();

                if (!$batch) {
                    return redirect()->back()->with('error', "Selected batch for {$material->training_title} not found.");
                }

                $endDate = isset($batch->end_date) ? Carbon::parse($batch->end_date) : Carbon::parse($batch->start_date);
                if ($endDate->isPast()) {
                    $hasEndedBatch = true;
                }
            }
        }

        if ($hasEndedBatch) {
            return redirect()->back()->with('error', 'One or more batches in your cart have already ended. Please remove them to proceed.');
        }

        $couponAmount   = floatval($request->coupon_amount ?? 0);
        $amountWithTax  = round($totalAmount + ($totalAmount * ($taxRate / 100)) - $couponAmount, 2);
        $referenceNo    = strtoupper($userType) . '-CART-' . $userId . '-' . date('YmdHi') . '-' . time();

        $lastMaterial   = TrainingMaterial::find(end($materialIds));
        $lastOfferPrice = floatval(end($offerPrices));
        $lastBatchId    = end($batchIds) ?? null;
        $amountPaid     = $amountWithTax;
        $memberCount    = $request->member_count ?? 1;

        DB::beginTransaction();
        try {
            // ✅ 1. Save Payment Request
            $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
                'user_type'       => $userType,
                'jobseeker_id'    => $userId,
                'trainer_id'      => $lastMaterial->trainer_id,
                'material_id'     => $lastMaterial->id,
                'batch_id'        => $lastBatchId,
                'material_ids'    => implode(',', $materialIds),
                'request_payload' => null,
                'track_id'        => $referenceNo,
                'type'            => 'cart',
                'training_type'   => $lastMaterial->training_type,
                'transaction_id'  => null,
                'payment_status'  => 'initiated',
                'taxed_amount'    => $taxRate,
                'amount'          => $lastOfferPrice * $memberCount,
                'amount_paid'     => $amountPaid,
                'currency'        => 'SAR',
                'payment_gateway' => 'Al Rajhi',
                'coupon_type'     => $request->coupon_type ?? null,
                'coupon_code'     => $request->coupon_code ?? null,
                'coupon_amount'   => $request->coupon_amount ?? 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // ✅ 2. Save Purchase Record for last material
            $purchase = JobseekerTrainingMaterialPurchase::firstOrCreate(
                ['track_id' => $paymentRequest->track_id],
                [
                    'jobseeker_id'     => $userId,
                    'user_type'        => $userType,
                    'trainer_id'       => $paymentRequest->trainer_id,
                    'material_id'      => $paymentRequest->material_id,
                    'purchased_by'     => $userId,
                    'training_type'    => $paymentRequest->training_type,
                    'session_type'     => $paymentRequest->training_type,
                    'batch_id'         => $paymentRequest->batch_id,
                    'purchase_for'     => 'cart',
                    'payment_id'       => $paymentRequest->id,
                    'batchStatus'      => 'active',
                    'status'           => 'active',
                    'tax_percentage'   => $paymentRequest->taxed_amount ?? 0,
                    'taxed_amount'     => $paymentRequest->taxed_amount ?? 0,
                    'amount_paid'      => $paymentRequest->amount_paid,
                    'coupon_type'      => $paymentRequest->coupon_type,
                    'coupon_code'      => $paymentRequest->coupon_code,
                    'coupon_amount'    => $paymentRequest->coupon_amount,
                    'order_id'         => 'ORD-' . $userId . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
                    'track_id'         => $paymentRequest->track_id,
                    'transaction_id'   => $paymentRequest->transaction_id,
                    'payment_status'   => 'success',
                    'response_payload' => null,
                    'member_count'     => $memberCount,
                ]
            );

            // ✅ 3. Save Payment History
            PaymentHistory::create([
                'user_type'      => $userType,
                'user_id'        => $userId,
                'receiver_type'  => 'trainer',
                'receiver_id'    => $paymentRequest->trainer_id,
                'payment_for'    => 'training',
                'amount_paid'    => $paymentRequest->amount_paid,
                'tax'            => $paymentRequest->taxed_amount ?? 0,
                'applied_coupon' => $paymentRequest->coupon_code,
                'payment_status' => 'completed',
                'transaction_id' => $paymentRequest->transaction_id,
                'track_id'       => $paymentRequest->track_id,
                'order_id'       => 'ORD-' . $userId . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
                'currency'       => 'SAR',
                'payment_method' => $paymentRequest->payment_gateway,
                'paid_at'        => now(),
            ]);

            // ✅ 4. Prepare transaction for Neoleap
            $config = config('neoleap');
            $transactionDetails = [
                "id"           => $config['tranportal_id'],
                "amt"          => $amountPaid,
                "action"       => "1",
                "password"     => "T4#2H#ma5yHv\$G7",
                "currencyCode" => "682",
                "trackId"      => $referenceNo,
                "langid"       => "en",
                "udf1"         => $userId,
                "udf2"         => $userType,
                "udf3"         => $paymentRequest->id,
                "udf4"         => $paymentRequest->training_type,
                "udf5"         => $paymentRequest->training_type,
                "udf6"         => $paymentRequest->material_id,
                "udf7"         => $taxRate ?? 0.00,
                "udf8"         => $lastOfferPrice,
                "udf9"         => $paymentRequest->trainer_id,
                "responseURL"  => $config['cart_course_success_url'],
                "errorURL"     => $config['cart_course_failure_url'],
            ];

            $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
            $trandata     = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));
            $paymentRequest->update(['request_payload' => $jsonTrandata]);

            $payloads = json_encode([[
                "id"        => $config['tranportal_id'],
                "trandata"  => $trandata,
                "responseURL" => $config['cart_course_success_url'],
                "errorURL"    => $config['cart_course_failure_url']
            ]], JSON_UNESCAPED_SLASHES);

            DB::commit();

            // ✅ Redirect to Neoleap
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $config['curlopt_url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payloads,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $data = json_decode($response, true);
            $result = $data[0]['result'] ?? null;

            if ($result) {
                [$paymentId, $paymentUrl] = explode(":", $result, 2);
                return redirect()->away($paymentUrl . "?PaymentID=" . $paymentId);
            }

            return redirect()->back()->with('error', 'Unable to initiate payment. Please try again.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    public function successPurchaseCourseCart(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if (!$responseTrandata) {
            return redirect()->back()->with('error', 'Missing payment response.');
        }

        // ✅ Decrypt response from Neoleap
        $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $data = json_decode($decrypted, true)[0] ?? [];

        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        $userType = $payment->user_type;
        $userId   = $payment->jobseeker_id ;

        $paymentStatus = ($data['result'] === 'CAPTURED') ? 'success' : 'failed';
        $payment->update([
            'transaction_id'   => $data['transId'],
            'payment_status'   => $paymentStatus,
            'response_payload' => json_encode($data),
        ]);

        if ($paymentStatus === 'success') {
            // ✅ Handle multiple materials
            $materialIds = explode(',', $payment->material_ids ?? '');
            foreach ($materialIds as $materialId) {
                $material = TrainingMaterial::find($materialId);
                if (!$material) continue;

                // ✅ Create purchase record per material
                JobseekerTrainingMaterialPurchase::create([
                    'jobseeker_id'     => $userId,
                    'user_type'        => $userType,
                    'trainer_id'       => $material->trainer_id,
                    'material_id'      => $material->id,
                    'purchased_by'     => $userId,
                    'training_type'    => $material->training_type,
                    'session_type'     => $material->training_type,
                    'batch_id'         => $payment->batch_id,
                    'purchase_for'     => 'cart',
                    'payment_id'       => $payment->id,
                    'batchStatus'      => 'active',
                    'status'           => 'active',
                    'tax_percentage'   => $payment->taxed_amount ?? 0,
                    'taxed_amount'     => $payment->taxed_amount ?? 0,
                    'amount_paid'      => $payment->amount_paid,
                    'coupon_type'      => $payment->coupon_type,
                    'coupon_code'      => $payment->coupon_code,
                    'coupon_amount'    => $payment->coupon_amount,
                    'order_id'         => 'ORD-' . $userId . '-' . $material->id . '-' . now()->format('YmdHi'),
                    'track_id'         => $payment->track_id,
                    'transaction_id'   => $payment->transaction_id,
                    'payment_status'   => 'success',
                    'response_payload' => json_encode($data),
                    'member_count'     => $payment->member_count ?? 1,
                ]);

                // ✅ Create payment history
                PaymentHistory::create([
                    'user_type'      => $userType,
                    'user_id'        => $userId,
                    'receiver_type'  => 'trainer',
                    'receiver_id'    => $material->trainer_id,
                    'payment_for'    => 'training',
                    'amount_paid'    => $payment->amount_paid,
                    'tax'            => $payment->taxed_amount ?? 0,
                    'applied_coupon' => $payment->coupon_code,
                    'payment_status' => 'completed',
                    'transaction_id' => $payment->transaction_id,
                    'track_id'       => $payment->track_id,
                    'order_id'       => 'ORD-' . $userId . '-' . $material->id . '-' . now()->format('YmdHi'),
                    'currency'       => 'SAR',
                    'payment_method' => $payment->payment_gateway,
                    'paid_at'        => now(),
                ]);

                // ✅ Update cart status
                $cartStatus = JobseekerCartItem::where('jobseeker_id', $userId)
                                                ->where('material_id', $material->id)
                                                ->update(['status' => 'paid']);

            }

            // ✅ Auto-login
            if ($userType === 'jobseeker') {
                if ($user = Jobseekers::find($userId)) Auth::guard('jobseeker')->login($user);
            } elseif ($userType === 'expat') {
                if ($user = Expat::find($userId)) Auth::guard('expat')->login($user);
            }

            $redirectRoute = $userType === 'expat' ? 'expat.profile' : 'jobseeker.profile';
            return redirect()->route($redirectRoute)->with('success', 'All cart courses purchased successfully!');
        }

        $redirectRoute = $userType === 'expat' ? 'expat.profile' : 'jobseeker.profile';
        return redirect()->route($redirectRoute)->with('error', 'Payment failed or cancelled.');
    }





    public function failurePurchaseCourseCart(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if (!$responseTrandata) {
            return redirect()->route('jobseeker.profile')->with('error', 'Missing payment response.');
        }

        $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $dataArray = json_decode($decrypted, true);
        $data = $dataArray[0] ?? null;

        if (!$data) {
            return redirect()->route('jobseeker.profile')->with('error', 'Invalid payment response.');
        }

        // 🧾 Find the payment record
        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();

        if ($payment) {
            $payment->update([
                'transaction_id'   => $data['transId'] ?? null,
                'payment_status'   => 'failed',
                'response_payload' => json_encode($data),
            ]);

            // 🔍 Detect user type dynamically
            $userType = $payment->user_type ?? 'jobseeker'; // default fallback

            // 👤 Log the correct user in and redirect properly
            if ($userType === 'expat') {
                $expat = Expat::find($payment->user_id);
                if ($expat) {
                    Auth::guard('expat')->login($expat);
                    return redirect()->route('expat.profile')->with('error', 'Your cart payment failed. Please try again.');
                }
            } else {
                $jobseeker = Jobseekers::find($payment->user_id);
                if ($jobseeker) {
                    Auth::guard('jobseeker')->login($jobseeker);
                    return redirect()->route('jobseeker.profile')->with('error', 'Your cart payment failed. Please try again.');
                }
            }
        }

        // 🔁 Fallback if payment not found
        return redirect()->route('jobseeker.profile')->with('error', 'Payment record not found. Please try again.');
    }


}
