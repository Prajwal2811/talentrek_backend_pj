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
use App\Models\TrainingMaterial;
use App\Models\PurchasedSubscription;

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
            . '-' . ($request->batch_id ?? '0')
            . '-' . $userId
            . '-' . date('YmdHi')
            . '-' . time();

        // Create payment request
        $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
            'jobseeker_id'    => $userId,
            'trainer_id'      => $material->trainer_id,
            'material_id'     => $material->id,
            'batch_id'        => $request->batch_id ?? null,
            'request_payload' => null,
            'track_id'        => $referenceNo,
            'type'            => $buyType,
            'training_type'   => $material->training_type,
            'transaction_id'  => null,
            'payment_status'  => 'initiated',
            'taxed_amount'    => $taxRate,
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

        // echo "<pre>";  print_r($data); die;
        // Find original payment request
        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        // Update payment request status
        $paymentStatus = $data['result'] === 'CAPTURED' ? 'success' : 'failed';
        $payment->update([
            'transaction_id'   => $data['transId'] ?? null,
            'payment_status'   => $paymentStatus,
            'response_payload' => json_encode($data),
        ]);

        if ($data['result'] === 'CAPTURED') {
            // Create purchase record
            $purchase = JobseekerTrainingMaterialPurchase::create([
                'jobseeker_id'    => $payment->jobseeker_id,
                'material_id'     => $payment->material_id,
                'batch_id'        => $payment->batch_id,
                'status'          => 'active',
                'track_id'        => $payment->track_id,
                'transaction_id'  => $payment->transaction_id,
                'payment_status'  => 'success',
                'response_payload'=> json_encode($data),
            ]);

            // Add entry in payment history
            PaymentHistory::create([
                'user_type'      => 'jobseeker',
                'user_id'        => $payment->jobseeker_id,
                'receiver_type'  => 'trainer',
                'receiver_id'    => $payment->trainer_id,
                'payment_for'    => 'training',
                'amount_paid'    => $payment->amount_paid,
                'tax'            => $payment->taxed_amount,
                'applied_coupon' => $payment->coupon_code,
                'payment_status' => 'completed',
                'transaction_id' => $payment->transaction_id,
                'track_id'       => $payment->track_id,
                'order_id'       => 'ORD-' . $payment->jobseeker_id . '-' . $payment->material_id . '-' . now()->format('YmdHi'),
                'currency'       => 'SAR',
                'payment_method' => $payment->payment_gateway,
                'paid_at'        => now(),
            ]);

            // 🔑 Log the jobseeker in
            $jobseeker = Jobseekers::find($payment->jobseeker_id);
            if ($jobseeker) {
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
        $validator = Validator::make($request->all(), [
            'material_id' => 'required|exists:training_materials,id',
            'training_type' => 'required|in:online,classroom,recorded',
            'batch_id' => 'nullable|required_if:training_type,online|required_if:training_type,classroom',
            'member_count' => 'required|integer|min:2',
            'member_emails.*' => 'required|email',
            'original_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string',
            'coupon_amount' => 'nullable|numeric|min:0',
            'coupon_type' => 'nullable|string',
        ], [
            'material_id.required' => 'Please select a course to purchase.',
            'material_id.exists' => 'Selected course does not exist.',
            'training_type.required' => 'Training type is required.',
            'training_type.in' => 'Invalid training type selected.',
            'batch_id.required_if' => 'Please select a batch for this training type.',
            'member_count.required' => 'Please select number of team members.',
            'member_count.integer' => 'Member count must be a number.',
            'member_count.min' => 'You must add at least 2 members.',
            'member_emails.*.required' => 'Please enter email for each team member.',
            'member_emails.*.email' => 'Each team member email must be a valid email address.',
            'original_price.required' => 'Original price is missing.',
            'original_price.numeric' => 'Original price must be a number.',
            'original_price.min' => 'Original price cannot be negative.',
            'tax_rate.numeric' => 'Tax rate must be a number.',
            'tax_rate.min' => 'Tax rate cannot be negative.',
            'coupon_code.string' => 'Invalid coupon code.',
            'coupon_amount.numeric' => 'Coupon amount must be a number.',
            'coupon_amount.min' => 'Coupon amount cannot be negative.',
            'coupon_type.string' => 'Invalid coupon type.',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $material = TrainingMaterial::findOrFail($request->material_id);

        $memberCount = intval($request->member_count);
        $offerPrice = floatval($request->original_price ?? $material->training_offer_price ?? $material->training_price);
        $taxRate = floatval($request->tax_rate ?? Setting::first()->trainingMaterialTax ?? 0);
        $tax = round($offerPrice * $memberCount * ($taxRate / 100), 2);
        $amountPaid = round(($offerPrice * $memberCount) + $tax - ($request->coupon_amount ?? 0), 2);

        // Generate unique reference
        $referenceNo = "TRK-TEAM-" . strtoupper(substr($material->training_type, 0, 3)) . '-' . $material->id
                        . '-' . ($request->batch_id ?? '0') . '-' . auth('jobseeker')->id() . '-' . date('YmdHi') . '-' . time();


        // Create payment request
        $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
            'jobseeker_id'    => auth('jobseeker')->id(),
            'trainer_id'      => $material->trainer_id,
            'material_id'     => $material->id,
            'batch_id'        => $request->batch_id ?? null,
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
        ]);

        // Prepare Neoleap transaction
        $config = config('neoleap');
        $transactionDetails = [
            "id" => $config['tranportal_id'],
            "amt" => $amountPaid,
            "action" => "1",
            "password" => $config['password'] ?? "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682",
            "trackId" => $referenceNo,
            "langid" => "en",
            "responseURL" => $config['team_course_success_url'],
            "errorURL" => $config['team_course_failure_url'],
            "udf1" => auth('jobseeker')->id(),
            "udf2" => 'team',
            "udf3" => $paymentRequest->id,
            "udf5" => 'team_course',
        ];

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));

        $paymentRequest->update(['request_payload' => $jsonTrandata]);

        $payload = json_encode([[
            "id" => $config['tranportal_id'],
            "trandata" => $trandata,
            "responseURL" => $config['team_course_success_url'],
            "errorURL" => $config['team_course_failure_url']
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

        $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $dataArray = json_decode($decrypted, true);
        $data = $dataArray[0] ?? null;

        if (!$data) {
            return redirect()->back()->with('error', 'Invalid payment response.');
        }

        // Fetch payment request
        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        // Update payment request
        $payment->update([
            'transaction_id'   => $data['transId'] ?? null,
            'payment_status'   => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
            'response_payload' => json_encode($data),
        ]);

        // Continue only if payment is successful
        if ($data['result'] !== 'CAPTURED') {
            return redirect()->route('jobseeker.profile')->with('error', 'Team payment failed. Please try again.');
        }

        // Create main purchase record
        $purchase = JobseekerTrainingMaterialPurchase::create([
            'jobseeker_id'     => $payment->jobseeker_id,
            'material_id'      => $payment->material_id,
            'batch_id'         => $payment->batch_id,
            'status'           => 'active',
            'track_id'         => $payment->track_id,
            'transaction_id'   => $payment->transaction_id,
            'payment_status'   => 'success',
            'response_payload' => json_encode($data),
        ]);

        // Fetch corporate/team emails
        $corporateEmails = CorporatesEmailIds::where('track_id', $data['trackId'])->first();
        if ($corporateEmails) {
            $savedEmails = $corporateEmails->corporatesEmailIds ?? [];
            $corporateEmails->update(['successPaymentId' => $purchase->id]);

            foreach ($savedEmails as $memberEmail) {
                if (empty($memberEmail)) continue;

                // Find or create jobseeker
                $jobseeker = Jobseekers::firstOrCreate(
                    ['email' => $memberEmail],
                    ['password' => 1111, 'pass' => 1111, 'roles' => 'jobseeker']
                );

                // Insert into team members
                DB::table('team_course_members')->insert([
                    'main_jobseeker_id'              => $payment->jobseeker_id,
                    'jobseeker_id'                   => $jobseeker->id,
                    'trainer_id'                     => 1,
                    'training_material_purchases_id' => $purchase->id,
                    'material_id'                    => 0,
                    'training_type'                  => $data['udf4'] ?? 'recorded',
                    'session_type'                   => 'group',
                    'batch_id'                       => $data['udf3'] ?? 0,
                    'transaction_id'                 => $data['transId'] ?? null,
                    'payment_status'                 => 'success',
                    'track_id'                       => $data['trackId'] ?? null,
                    'email'                          => $memberEmail,
                    'created_at'                     => now(),
                    'updated_at'                     => now(),
                ]);
            }
        }

        // Create payment history for main jobseeker
        PaymentHistory::create([
            'user_type'      => 'jobseeker',
            'user_id'        => $payment->jobseeker_id,
            'receiver_type'  => 'trainer',
            'receiver_id'    => $payment->trainer_id,
            'payment_for'    => 'training',
            'amount_paid'    => $payment->amount_paid,
            'tax'            => $payment->taxed_amount,
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
        $jobseeker = Jobseekers::find($payment->jobseeker_id);
        if ($jobseeker) {
            Auth::guard('jobseeker')->login($jobseeker);
        }

        return redirect()->route('jobseeker.profile')->with('success', 'Team course purchased successfully!');
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
        $batchIds = $request->input('batch_ids', []);
        $offerPrices = $request->input('offer_prices', []);

        if (empty($materialIds)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $taxRate = floatval(Setting::first()->trainingMaterialTax ?? 0);
        $totalAmount = 0;
        $totalSaved = 0;
        $hasEndedBatch = false;

        foreach ($materialIds as $index => $materialId) {
            $material = TrainingMaterial::find($materialId);
            if (!$material) continue;

            $batchId = $batchIds[$index] ?? null;
            $offerPrice = floatval($offerPrices[$index] ?? $material->training_price);

            $totalAmount += $offerPrice;
            $totalSaved += ($material->training_price - $offerPrice);

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
        $amountWithTax = round($totalAmount + ($totalAmount * ($taxRate/100)) - $couponAmount, 2);

        $referenceNo = 'TRK-CART-' . $jobseekerId . '-' . date('YmdHi') . '-' . time();

        // Save payment request
        $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
            'jobseeker_id'  => $jobseekerId,
            'track_id'      => $referenceNo,
            'type'          => 'cart',
            'training_type' => 'cart',
            'amount'        => $totalAmount,
            'amount_paid'   => $amountWithTax,
            'taxed_amount'  => $taxRate,
            'coupon_amount' => $couponAmount,
            'payment_status'=> 'initiated',
        ]);

        // Prepare transaction
        $config = config('neoleap');
        $transactionDetails = [
            "id" => $config['tranportal_id'],
            "amt" => $amountWithTax,
            "action" => "1",
            "password" => $config['password'] ?? "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682",
            "trackId" => $referenceNo,
            "langid" => "en",
            "responseURL" => $config['course_success_url'],
            "errorURL" => $config['course_failure_url'],
            "udf1" => $jobseekerId,
            "udf2" => 'cart',
            "udf3" => $paymentRequest->id,
            "udf5" => 'cart',
        ];

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));
        $paymentRequest->update(['request_payload' => $jsonTrandata]);

        $payload = json_encode([[
            "id" => $config['tranportal_id'],
            "trandata" => $trandata,
            "responseURL" => $config['course_success_url'],
            "errorURL" => $config['course_failure_url']
        ]], JSON_UNESCAPED_SLASHES);

        // Redirect to Neoleap
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
        if (!$payment) return redirect()->back()->with('error', 'Payment request not found.');

        $paymentStatus = $data['result'] === 'CAPTURED' ? 'success' : 'failed';
        $payment->update([
            'transaction_id'   => $data['transId'] ?? null,
            'payment_status'   => $paymentStatus,
            'response_payload' => json_encode($data),
        ]);

        if ($paymentStatus === 'success') {
            $materialIds = explode(',', $payment->material_ids ?? ''); // optional if saved
            foreach ($materialIds as $materialId) {
                $material = TrainingMaterial::find($materialId);
                if (!$material) continue;

                JobseekerTrainingMaterialPurchase::create([
                    'jobseeker_id'    => $payment->jobseeker_id,
                    'material_id'     => $material->id,
                    'status'          => 'active',
                    'track_id'        => $payment->track_id,
                    'transaction_id'  => $payment->transaction_id,
                    'payment_status'  => 'success',
                    'response_payload'=> json_encode($data),
                ]);

                // Optionally remove from cart
                JobseekerCartItem::where('jobseeker_id', $payment->jobseeker_id)
                    ->where('material_id', $material->id)
                    ->delete();
            }

            // Log jobseeker in
            $jobseeker = Jobseekers::find($payment->jobseeker_id);
            if ($jobseeker) Auth::guard('jobseeker')->login($jobseeker);
        }

        return redirect()->route('jobseeker.profile')->with('success', 'All cart courses purchased successfully!');
    }


}
