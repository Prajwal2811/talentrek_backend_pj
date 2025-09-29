<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentHelper;
use App\Models\PaymentHistory;
use App\Models\Jobseekers;
use App\Models\Setting;
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
     * Initiate Course Purchase Payment
     */
    public function processPurchaseCoursePayment(Request $request)
    {

        // Check if jobseeker is logged in
        if (!Auth::guard('jobseeker')->check()) {
            return redirect()->back()->with('error', 'Please login to purchase a course.');
        }

        // Use the logged-in jobseeker ID
        $jobseekerId = Auth::guard('jobseeker')->id();

        // Check if jobseeker has a valid subscription
        $subscription = PurchasedSubscription::where('user_id', $jobseekerId)
            ->where('user_type', 'jobseeker')
            ->where('payment_status', 'paid')
            ->orderBy('end_date', 'desc')
            ->first();

        $hasValidSubscription = false;

        if ($subscription) {
            $endDate = Carbon::parse($subscription->end_date);
            $hasValidSubscription = !$endDate->isPast(); // true if not expired
        }

        if (!$hasValidSubscription) {
            return redirect()->back()->with('error', 'You need an active subscription to book a session.');
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:jobseekers,id',
            'buy_type' => 'required|in:buyNow,cart,corporate',
            'material_id' => 'required|exists:training_materials,id',
            'training_type' => 'required|in:online,classroom,recorded',
            'batch_id' => 'nullable|required_if:training_type,online|required_if:training_type,classroom',
            'original_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string',
            'coupon_amount' => 'nullable|numeric|min:0',
            'coupon_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $userId = $request->user_id;
        $buyType = $request->buy_type;
        $config = config('neoleap');

        $material = TrainingMaterial::findOrFail($request->material_id);

        $offerPrice = floatval($request->original_price ?? $material->training_offer_price ?? $material->training_price);
        $taxRate = floatval($request->tax_rate ?? Setting::first()->trainingMaterialTax ?? 0);
        $tax = round($offerPrice * ($taxRate / 100), 2);
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
            'jobseeker_id'    => $userId,
            'trainer_id'      => $material->trainer_id,
            'material_id'     => $material->id,
            'batch_id'        => $request->batch_id ?? null,
            'request_payload' => null, // will update after creating transaction payload
            'track_id'        => $referenceNo,
            'type'            => $buyType,
            'training_type'   => $material->training_type,
            'transaction_id'  => null,
            'payment_status'  => 'initiated',
            'tax'             => $tax,
            'amount'          => $offerPrice,
            'amount_paid'     => $amountPaid,
            'currency'        => 'SAR',
            'payment_gateway' => 'Al Rajhi',
            'coupon_type'     => $request->coupon_type ?? null,
            'coupon_code'     => $request->coupon_code ?? null,
            'coupon_amount'   => $request->coupon_amount ?? 0,
        ]);

        // echo "<pre>"; print_r($paymentRequest); die;

        // Prepare Neoleap transaction
        $transactionDetails = [
            "id" => $config['tranportal_id'],
            "amt" => $amountPaid,
            "action" => "1",
            "password" => $config['password'] ?? "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682",
            "trackId" => $referenceNo,
            "langid" => "en",
            "responseURL" => $config['course_success_url'],
            "errorURL" => $config['course_failure_url'],
            "udf1" => $userId,
            "udf2" => $buyType,
            "udf3" => $paymentRequest->id,
            "udf5" => 'course',
        ];

        // echo "<pre>"; print_r($transactionDetails); die;

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));

        // Update payment request payload
        $paymentRequest->update(['request_payload' => $jsonTrandata]);

        // Prepare final payload
        $payload = json_encode([[ 
            "id" => $config['tranportal_id'],
            "trandata" => $trandata,
            "responseURL" => $config['course_success_url'],
            "errorURL" => $config['course_failure_url']
        ]], JSON_UNESCAPED_SLASHES);

        // Redirect to Neoleap payment
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
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

        // Decrypt Neoleap response
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

        // Update payment request status
        $paymentStatus = $data['result'] === 'CAPTURED' ? 'success' : 'failed';
        $paymentRequest->update([
            'transaction_id'   => $data['transId'] ?? null,
            'payment_status'   => $paymentStatus,
            'response_payload' => json_encode($data),
        ]);

        if ($paymentStatus === 'success') {
            // Create payment history first
            $paymentHistory = PaymentHistory::create([
                'user_type'      => 'jobseeker',
                'user_id'        => $paymentRequest->jobseeker_id,
                'receiver_type'  => 'trainer',
                'receiver_id'    => $paymentRequest->trainer_id,
                'payment_for'    => 'training',
                'amount_paid'    => $paymentRequest->amount_paid,
                'tax'            => $paymentRequest->tax,
                'applied_coupon' => $paymentRequest->coupon_code,
                'payment_status' => 'completed',
                'transaction_id' => $paymentRequest->transaction_id,
                'track_id'       => $paymentRequest->track_id,
                'order_id'       => 'ORD-' . $paymentRequest->jobseeker_id . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
                'currency'       => 'SAR',
                'payment_method' => $paymentRequest->payment_gateway,
                'paid_at'        => now(),
            ]);

            // Create purchase record with all fields
            $purchase = JobseekerTrainingMaterialPurchase::create([
                'jobseeker_id'    => $paymentRequest->jobseeker_id,
                'trainer_id'      => $paymentRequest->trainer_id,
                'material_id'     => $paymentRequest->material_id,
                'purchased_by'    => $paymentRequest->jobseeker_id,
                'training_type'   => $paymentRequest->training_type,
                'session_type'    => $paymentRequest->training_type, // adjust if different session_type logic
                'batch_id'        => $paymentRequest->batch_id,
                'purchase_for'    => 'course',
                'payment_id'      => $paymentHistory->id,
                'batchStatus'     => 'active',
                'status'          => 'active',
                'tax_percentage'  => $paymentRequest->tax,
                'taxed_amount'    => $paymentRequest->amount_paid - $paymentRequest->amount,
                'amount_paid'     => $paymentRequest->amount_paid,
                'coupon_type'     => $paymentRequest->coupon_type,
                'coupon_code'     => $paymentRequest->coupon_code,
                'coupon_amount'   => $paymentRequest->coupon_amount,
                'order_id'        => 'ORD-' . $paymentRequest->jobseeker_id . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
                'track_id'        => $paymentRequest->track_id,
                'transaction_id'  => $paymentRequest->transaction_id,
                'payment_status'  => 'success',
                'response_payload'=> json_encode($data),
                'member_count'    => 1, // default, adjust if needed for team purchases
            ]);

            // Auto-login the jobseeker if needed
            if ($jobseeker = Jobseekers::find($paymentRequest->jobseeker_id)) {
                Auth::guard('jobseeker')->login($jobseeker);
            }
        }

        return redirect()->route('jobseeker.profile')->with('success', 'Course purchased successfully!');
    }



    /**
     * Failure callback
     */
    public function failurePurchaseCourse(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

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
                }
            }

             // 🔑 Log the jobseeker in
            $jobseeker = Jobseekers::find($payment->jobseeker_id);
            if ($jobseeker) {
                Auth::guard('jobseeker')->login($jobseeker); 
            }
        }

        return redirect()->route('jobseeker.profile')->with('error', 'Payment failed or cancelled. Please try again.');
    }



    /**
     * Process Team Course Purchase Payment
     */
    public function processPurchaseCoursePaymentForteam(Request $request)
{
    // Check if jobseeker is logged in
    if (!Auth::guard('jobseeker')->check()) {
        return redirect()->back()->with('error', 'Please login to purchase a course.');
    }

    $jobseekerId = Auth::guard('jobseeker')->id();

    // Check subscription
    $subscription = PurchasedSubscription::where('user_id', $jobseekerId)
        ->where('user_type', 'jobseeker')
        ->where('payment_status', 'paid')
        ->orderBy('end_date', 'desc')
        ->first();

    if (!$subscription || Carbon::parse($subscription->end_date)->isPast()) {
        return redirect()->back()->with('error', 'You need an active subscription to book a session.');
    }

    // Validate input
    $validator = Validator::make($request->all(), [
        'material_id'    => 'required|exists:training_materials,id',
        'training_type'  => 'required|in:online,classroom,recorded',
        'batch_id'       => 'nullable|required_if:training_type,online|required_if:training_type,classroom',
        'member_count'   => 'required|integer|min:2',
        'member_emails.*'=> 'required|email',
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
    $memberCount = intval($request->member_count);
    $offerPrice   = floatval($request->original_price ?? $material->training_offer_price ?? $material->training_price);
    $taxRate      = floatval($request->tax_rate ?? Setting::first()->trainingMaterialTax ?? 0);
    $tax          = round($offerPrice * $memberCount * ($taxRate / 100), 2);
    $amountPaid   = round(($offerPrice * $memberCount) + $tax - ($request->coupon_amount ?? 0), 2);

    // Generate unique reference
    $referenceNo = "TRK-TEAM-" 
                    . strtoupper(substr($material->training_type, 0, 3)) 
                    . '-' . $material->id
                    . '-' . ($request->batch_id ?? '0') 
                    . '-' . $jobseekerId
                    . '-' . date('YmdHi') 
                    . '-' . time();

    // Create payment request
    $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
        'jobseeker_id'    => $jobseekerId,
        'trainer_id'      => $material->trainer_id,
        'material_id'     => $material->id,
        'batch_id'        => $request->batch_id ?? null,
        'request_payload' => null,
        'track_id'        => $referenceNo,
        'type'            => 'buyForCorporate',
        'training_type'   => $material->training_type,
        'transaction_id'  => null,
        'payment_status'  => 'initiated',
        'taxed_amount'             => $taxRate,
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
        "id"            => $config['tranportal_id'],
        "amt"           => $amountPaid,
        "action"        => "1",
        "password"      => $config['password'] ?? "T4#2H#ma5yHv\$G7",
        "currencyCode"  => "682",
        "trackId"       => $referenceNo,
        "langid"        => "en",
        "udf1"          => $jobseekerId,
        "udf2"          => 'team',
        "udf3"          => $paymentRequest->id,
        "udf4"          => $request->training_type ?? 'recorded',
        "udf5"          => $request->training_type,
        "udf6"          => $request->material_id,
        "udf7"          => $tax ?? 0.00,
        "udf8"          => $offerPrice,
        "udf9"          => $corporatesEmailIds->id ?? '',
        "udf10"         => $request->trainer_id,
        "responseURL"   => $config['team_course_success_url'],
        "errorURL"      => $config['team_course_failure_url'],
    ];

    $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
    $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));
    $paymentRequest->update(['request_payload' => $jsonTrandata, 'track_id'=> $referenceNo]);

    $payloads = json_encode([[
        "id" => $config['tranportal_id'],
        "trandata" => $trandata,
        "responseURL" => $config['team_course_success_url'],
        "errorURL" => $config['team_course_failure_url']
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

        // Update payment request status
        $payment->update([
            'transaction_id'   => $data['transId'] ?? null,
            'payment_status'   => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
            'response_payload' => json_encode($data),
        ]);

        if ($data['result'] !== 'CAPTURED') {
            return redirect()->route('jobseeker.profile')->with('error', 'Team payment failed. Please try again.');
        }

        // Create main purchase record
        $purchase = JobseekerTrainingMaterialPurchase::firstOrCreate(
            ['track_id' => $payment->track_id],
            [
                'jobseeker_id'     => $payment->jobseeker_id,
                'trainer_id'       => $payment->trainer_id,
                'material_id'      => $payment->material_id,
                'purchased_by'     => $payment->jobseeker_id,
                'training_type'    => $payment->training_type,
                'session_type'     => $payment->training_type,
                'batch_id'         => $payment->batch_id,
                'purchase_for'     => 'team',
                'payment_id'       => $payment->id,
                'batchStatus'      => 'active',
                'status'           => 'active',
                'tax_percentage'   => $payment->taxed_amount ?? 0,
                'taxed_amount'     => $payment->tax,
                'amount_paid'      => $payment->amount_paid,
                'coupon_type'      => $payment->coupon_type,
                'coupon_code'      => $payment->coupon_code,
                'coupon_amount'    => $payment->coupon_amount,
                'order_id'         => 'ORD-' . $payment->jobseeker_id . '-' . $payment->material_id . '-' . now()->format('YmdHi'),
                'track_id'         => $payment->track_id,
                'transaction_id'   => $payment->transaction_id,
                'payment_status'   => 'success',
                'response_payload' => json_encode($data),
                'member_count'     => $request->member_count,
            ]
        );

        // Fetch team/corporate emails
        $corporateEmails = CorporatesEmailIds::where('track_id', $data['trackId'])->first();
        $teamMembers = [];

        if ($corporateEmails) {
            $savedEmails = $corporateEmails->corporatesEmailIds ?? [];
            $corporateEmails->update(['successPaymentId' => $purchase->id]);

            foreach ($savedEmails as $memberEmail) {
                if (empty($memberEmail)) continue;

                $teamMember = Jobseekers::firstOrCreate(
                    ['email' => $memberEmail],
                    [
                        'password' => bcrypt(explode('@', $memberEmail)[0] . '@talentrek'),
                        'pass'     => explode('@', $memberEmail)[0] . '@talentrek',
                        'roles'    => 'jobseeker',
                    ]
                );

                TeamCourseMember::updateOrCreate(
                    [
                        'main_jobseeker_id'              => $payment->jobseeker_id,
                        'jobseeker_id'                   => $teamMember->id,
                        'training_material_purchases_id' => $purchase->id,
                        'material_id'                    => $payment->material_id,
                    ],
                    [
                        'trainer_id'       => $payment->trainer_id ?? 1,
                        'training_type'    => $payment->training_type,
                        'session_type'     => 'team',
                        'batch_id'         => $payment->batch_id ?? 0,
                        'transaction_id'   => $payment->transaction_id,
                        'payment_status'   => 'success',
                        'track_id'         => $payment->track_id,
                        'email'            => $memberEmail,
                    ]
                );

                $teamMembers[] = $teamMember;
            }
        }

        $mainJobseeker = Jobseekers::find($payment->jobseeker_id);

        // Send email to main jobseeker
        if ($mainJobseeker) {
            $teamEmails = implode(', ', array_map(fn($m) => $m->email, $teamMembers));
            Mail::html('
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Team Course Purchase Confirmation</title>
                    <style>
                        body { font-family: Arial,sans-serif; background:#f4f6f9; margin:0; padding:20px; color:#333; }
                        .container { background:#fff; padding:30px; border-radius:8px; max-width:600px; margin:auto; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
                        .footer { text-align:center; font-size:12px; color:#888; margin-top:20px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Team Course Purchase Successful</h2>
                        <p>Hello <strong>' . e($mainJobseeker->email) . '</strong>,</p>
                        <p>You have successfully purchased the course "<strong>' . e($purchase->material->name ?? 'Course') . '</strong>" for your team members:</p>
                        <p>' . $teamEmails . '</p>
                        <p>Thank you for using Talentrek!</p>
                    </div>
                    <div class="footer">
                        &copy; ' . date('Y') . ' Talentrek. All rights reserved.
                    </div>
                </body>
                </html>
            ');
        }

        // Send email to each team member
        foreach ($teamMembers as $member) {
            Mail::html('
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Team Course Notification</title>
                    <style>
                        body { font-family: Arial,sans-serif; background:#f4f6f9; margin:0; padding:20px; color:#333; }
                        .container { background:#fff; padding:30px; border-radius:8px; max-width:600px; margin:auto; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
                        .footer { text-align:center; font-size:12px; color:#888; margin-top:20px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Course Assigned to You</h2>
                        <p>Hello <strong>' . e($member->email) . '</strong>,</p>
                        <p>You have been assigned the course "<strong>' . e($purchase->material->name ?? 'Course') . '</strong>" by <strong>' . e($mainJobseeker->email) . '</strong>.</p>
                        <p>Thank you for joining the session!</p>
                    </div>
                    <div class="footer">
                        &copy; ' . date('Y') . ' Talentrek. All rights reserved.
                    </div>
                </body>
                </html>
            ', function($message) use ($member) {
                $message->to($member->email)
                        ->subject('You have been assigned a Team Course!');
            });
        }

        // Create payment history for main jobseeker
        PaymentHistory::create([
            'user_type'      => 'jobseeker',
            'user_id'        => $payment->jobseeker_id,
            'receiver_type'  => 'trainer',
            'receiver_id'    => $payment->trainer_id,
            'payment_for'    => 'training',
            'amount_paid'    => $payment->amount_paid,
            'tax'            => $payment->taxed_amount ?? 0,
            'applied_coupon' => $payment->coupon_code,
            'payment_status' => 'completed',
            'transaction_id' => $payment->transaction_id,
            'track_id'       => $payment->track_id,
            'order_id'       => 'ORD-' . $payment->jobseeker_id . '-' . $payment->material_id . '-' . now()->format('YmdHi'),
            'currency'       => 'SAR',
            'payment_method' => $payment->payment_gateway,
            'paid_at'        => now(),
        ]);

        // Auto-login main jobseeker
        if ($mainJobseeker) {
            Auth::guard('jobseeker')->login($mainJobseeker);
        }

        return redirect()->route('jobseeker.profile')->with('success', 'Team course purchased successfully and emails sent!');
    }






    /**
     * Team Course Failure
     */
    public function failurePurchaseCourseForTeam(Request $request)
    {

        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if ($responseTrandata) {
            $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
            $data = json_decode($decrypted, true)[0] ?? [];

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
                }
            }

             // 🔑 Log the jobseeker in
            $jobseeker = Jobseekers::find($payment->jobseeker_id);
            if ($jobseeker) {
                Auth::guard('jobseeker')->login($jobseeker); 
            }
        }

        return redirect()->route('jobseeker.profile')->with('error', 'Team payment failed or cancelled. Please try again.');
    }


    /**
     * Process payment for all cart items
     */
    // Process Cart Payment (multiple materials)
   
    public function processPurchaseCoursePaymentCart(Request $request)
    {

        $jobseekerId = Auth::guard('jobseeker')->id();

        $materialIds = $request->input('material_ids', []);
        $batchIds    = $request->input('batch_ids', []);
        $offerPrices = $request->input('offer_prices', []);

        if (empty($materialIds)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $taxRate     = floatval(Setting::first()->trainingMaterialTax ?? 0);
        $totalAmount = 0;
        $totalSaved  = 0;
        $hasEndedBatch = false;

        // Loop through cart items
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

        $couponAmount = floatval($request->coupon_amount ?? 0);
        $amountWithTax = round($totalAmount + ($totalAmount * ($taxRate / 100)) - $couponAmount, 2);

        $referenceNo = 'TRK-CART-' . $jobseekerId . '-' . date('YmdHi') . '-' . time();

        // Get last material for reference
        $material    = TrainingMaterial::find(end($materialIds));
        $offerPrice  = floatval(end($offerPrices));
        $batchId     = end($batchIds) ?? null;
        $amountPaid  = $amountWithTax;
        $memberCount = $request->member_count ?? 1;

        DB::beginTransaction();
        try {
            // ✅ 1. Save Payment Request
            $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
                'jobseeker_id'    => $jobseekerId,
                'trainer_id'      => $material->trainer_id,
                'material_id'     => $material->id,
                'batch_id'        => $batchId,
                'request_payload' => null,
                'track_id'        => $referenceNo,
                'type'            => 'buyForCorporate',
                'training_type'   => $material->training_type,
                'transaction_id'  => null,
                'payment_status'  => 'initiated',
                'taxed_amount'    => $taxRate,
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

            // ✅ 2. Save Purchase Record
            $purchase = JobseekerTrainingMaterialPurchase::firstOrCreate(
                ['track_id' => $paymentRequest->track_id],
                [
                    'jobseeker_id'     => $paymentRequest->jobseeker_id,
                    'trainer_id'       => $paymentRequest->trainer_id,
                    'material_id'      => $paymentRequest->material_id,
                    'purchased_by'     => $paymentRequest->jobseeker_id,
                    'training_type'    => $paymentRequest->training_type,
                    'session_type'     => $paymentRequest->training_type,
                    'batch_id'         => $paymentRequest->batch_id,
                    'purchase_for'     => 'team',
                    'payment_id'       => $paymentRequest->id,
                    'batchStatus'      => 'active',
                    'status'           => 'active',
                    'tax_percentage'   => $paymentRequest->taxed_amount ?? 0,
                    'taxed_amount'     => $paymentRequest->taxed_amount ?? 0,
                    'amount_paid'      => $paymentRequest->amount_paid,
                    'coupon_type'      => $paymentRequest->coupon_type,
                    'coupon_code'      => $paymentRequest->coupon_code,
                    'coupon_amount'    => $paymentRequest->coupon_amount,
                    'order_id'         => 'ORD-' . $paymentRequest->jobseeker_id . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
                    'track_id'         => $paymentRequest->track_id,
                    'transaction_id'   => $paymentRequest->transaction_id,
                    'payment_status'   => 'success',
                    'response_payload' => null,
                    'member_count'     => $memberCount,
                ]
            );

            // ✅ 3. Save Payment History
            PaymentHistory::create([
                'user_type'      => 'jobseeker',
                'user_id'        => $paymentRequest->jobseeker_id,
                'receiver_type'  => 'trainer',
                'receiver_id'    => $paymentRequest->trainer_id,
                'payment_for'    => 'training',
                'amount_paid'    => $paymentRequest->amount_paid,
                'tax'            => $paymentRequest->taxed_amount ?? 0,
                'applied_coupon' => $paymentRequest->coupon_code,
                'payment_status' => 'completed',
                'transaction_id' => $paymentRequest->transaction_id,
                'track_id'       => $paymentRequest->track_id,
                'order_id'       => 'ORD-' . $paymentRequest->jobseeker_id . '-' . $paymentRequest->material_id . '-' . now()->format('YmdHi'),
                'currency'       => 'SAR',
                'payment_method' => $paymentRequest->payment_gateway,
                'paid_at'        => now(),
            ]);

            // ✅ 4. Prepare Transaction Details
            $config = config('neoleap');
            $transactionDetails = [
                "id"            => $config['tranportal_id'],
                "amt"           => $amountPaid,
                "action"        => "1",
                "password"      => $config['password'] ?? "T4#2H#ma5yHv\$G7",
                "currencyCode"  => "682",
                "trackId"       => $referenceNo,
                "langid"        => "en",
                "udf1"          => $jobseekerId,
                "udf2"          => 'team',
                "udf3"          => $paymentRequest->id,
                "udf4"          => $request->training_type ?? 'recorded',
                "udf5"          => $request->training_type,
                "udf6"          => $request->material_id,
                "udf7"          => $taxRate ?? 0.00,
                "udf8"          => $offerPrice,
                "udf9"          => $request->corporatesEmailIds->id ?? '',
                "udf10"         => $request->trainer_id,
                "responseURL"   => $config['cart_course_success_url'],
                "errorURL"      => $config['cart_course_failure_url'],
            ];

            $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
            $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));
            $paymentRequest->update(['request_payload' => $jsonTrandata]);

            $payloads = json_encode([[
                "id" => $config['tranportal_id'],
                "trandata" => $trandata,
                "responseURL" => $config['cart_course_success_url'],
                "errorURL" => $config['cart_course_failure_url']
            ]], JSON_UNESCAPED_SLASHES);

            DB::commit();

            // Redirect to Neoleap
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
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

        $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $data = json_decode($decrypted, true)[0] ?? [];

        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        $paymentStatus = $data['result'] === 'CAPTURED' ? 'success' : 'failed';
        $payment->update([
            'transaction_id'   => $data['transId'] ?? null,
            'payment_status'   => $paymentStatus,
            'response_payload' => json_encode($data),
        ]);

        if ($paymentStatus === 'success') {
            // ✅ Handle multiple materials from cart
            $materialIds = explode(',', $payment->material_ids ?? '');
            foreach ($materialIds as $materialId) {
                $material = TrainingMaterial::find($materialId);
                if (!$material) continue;

                // ✅ Create purchase record per material
                $purchase = JobseekerTrainingMaterialPurchase::create([
                    'jobseeker_id'     => $payment->jobseeker_id,
                    'trainer_id'       => $material->trainer_id,
                    'material_id'      => $material->id,
                    'purchased_by'     => $payment->jobseeker_id,
                    'training_type'    => $material->training_type,
                    'session_type'     => $material->training_type,
                    'batch_id'         => $payment->batch_id,
                    'purchase_for'     => 'team',
                    'payment_id'       => $payment->id,
                    'batchStatus'      => 'active',
                    'status'           => 'active',
                    'tax_percentage'   => $payment->taxed_amount ?? 0,
                    'taxed_amount'     => $payment->taxed_amount ?? 0,
                    'amount_paid'      => $payment->amount_paid,
                    'coupon_type'      => $payment->coupon_type,
                    'coupon_code'      => $payment->coupon_code,
                    'coupon_amount'    => $payment->coupon_amount,
                    'order_id'         => 'ORD-' . $payment->jobseeker_id . '-' . $material->id . '-' . now()->format('YmdHi'),
                    'track_id'         => $payment->track_id,
                    'transaction_id'   => $payment->transaction_id,
                    'payment_status'   => 'success',
                    'response_payload' => json_encode($data),
                    'member_count'     => $payment->member_count ?? 1,
                ]);

                // ✅ Create payment history
                PaymentHistory::create([
                    'user_type'      => 'jobseeker',
                    'user_id'        => $payment->jobseeker_id,
                    'receiver_type'  => 'trainer',
                    'receiver_id'    => $material->trainer_id,
                    'payment_for'    => 'training',
                    'amount_paid'    => $payment->amount_paid,
                    'tax'            => $payment->taxed_amount ?? 0,
                    'applied_coupon' => $payment->coupon_code,
                    'payment_status' => 'completed',
                    'transaction_id' => $payment->transaction_id,
                    'track_id'       => $payment->track_id,
                    'order_id'       => 'ORD-' . $payment->jobseeker_id . '-' . $material->id . '-' . now()->format('YmdHi'),
                    'currency'       => 'SAR',
                    'payment_method' => $payment->payment_gateway,
                    'paid_at'        => now(),
                ]);

                // ✅ Remove item from cart
                JobseekerCartItem::where('jobseeker_id', $payment->jobseeker_id)
                    ->where('material_id', $material->id)
                    ->delete();
            }

            // ✅ Log jobseeker in
            if ($jobseeker = Jobseekers::find($payment->jobseeker_id)) {
                Auth::guard('jobseeker')->login($jobseeker);
            }
        }

        return redirect()->route('jobseeker.profile')
            ->with('success', 'All cart courses purchased successfully!');
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

        // Fetch the payment request
        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
        if ($payment) {
            $payment->update([
                'transaction_id'   => $data['transId'] ?? null,
                'payment_status'   => 'failed',
                'response_payload' => json_encode($data),
            ]);
        }

        // Optionally log in jobseeker to redirect them properly
        if ($payment && $payment->jobseeker_id) {
            $jobseeker = Jobseekers::find($payment->jobseeker_id);
            if ($jobseeker) {
                Auth::guard('jobseeker')->login($jobseeker);
            }
        }

        return redirect()->route('jobseeker.profile')->with('error', 'Your cart payment failed. Please try again.');
    }

}
