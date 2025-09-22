<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentHelper;
use App\Models\PaymentHistory;
use App\Models\Jobseekers;
use App\Models\Setting;
use App\Models\TrainingMaterial;
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
        // Validate input
        $validator = Validator::make($request->all(), [
            'items' => 'required|array', // array of items
            'items.*.material_id' => 'required|exists:training_materials,id',
            'items.*.training_type' => 'required|in:online,classroom,recorded',
            'items.*.batch_id' => 'nullable|required_if:items.*.training_type,online|required_if:items.*.training_type,classroom',
            'user_id' => 'required|exists:jobseekers,id',
            'buy_type' => 'required|in:buyNow,cart,corporate',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $userId = $request->user_id;
        $buyType = $request->buy_type;
        $config = config('neoleap');

        $totalAmount = 0;
        $paymentRequests = [];

        foreach ($request->items as $item) {
            $material = TrainingMaterial::findOrFail($item['material_id']);
            $offerPrice = floatval($item['offer_price'] ?? $material->training_offer_price ?? $material->training_price);
            $taxRate = floatval($item['tax_rate'] ?? Setting::first()->trainingMaterialTax ?? 0);
            $tax = round($offerPrice * ($taxRate / 100), 2);
            $amountPaid = round($offerPrice + $tax - ($item['coupon_amount'] ?? 0), 2);

            // Generate reference per item
            $referenceNo = "TRK-COURSE-" 
                . strtoupper(substr($material->training_type, 0, 3)) 
                . '-' . $material->id
                . '-' . ($item['batch_id'] ?? '0')
                . '-' . $userId
                . '-' . date('YmdHi')
                . '-' . time();

            // Create payment request record
            $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
                'jobseeker_id'    => $userId,
                'trainer_id'      => $material->trainer_id,
                'material_id'     => $material->id,
                'batch_id'        => $item['batch_id'] ?? null,
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
                'coupon_type'     => $item['coupon_type'] ?? null,
                'coupon_code'     => $item['coupon_code'] ?? null,
                'coupon_amount'   => $item['coupon_amount'] ?? 0,
            ]);

            $paymentRequests[] = $paymentRequest;
            $totalAmount += $amountPaid;
        }

        // Prepare Neoleap payload for **all items**
        $transactionDetails = [
            "id" => $config['tranportal_id'],
            "amt" => $totalAmount,
            "action" => "1",
            "password" => $config['password'] ?? "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682", // SAR
            "trackId" => "TRK-CART-" . time(),
            "langid" => "en",
            "responseURL" => $config['course_success_url'],
            "errorURL" => $config['course_failure_url'],
            "udf1" => $userId,
            "udf2" => $buyType,
            "udf3" => implode(',', $paymentRequests->pluck('id')->toArray()), // comma separated payment_request ids
            "udf5" => 'course', // static
            // add more udf fields if needed
        ];

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));

        // Update all paymentRequests with request payload
        foreach ($paymentRequests as $req) {
            $req->update(['request_payload' => $jsonTrandata]);
        }

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

        $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $data = json_decode($decrypted, true);

        if (!$data || !isset($data[0])) {
            return redirect()->back()->with('error', 'Invalid payment response.');
        }

        $data = $data[0];

        $payment = JobseekerTrainingMaterialPurchasePaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        $payment->update([
            'transaction_id'  => $data['transId'] ?? null,
            'status'          => $data['result'] === 'CAPTURED' ? 'completed' : 'pending',
            'payment_status'  => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
            'response_payload'=> json_encode($data),
        ]);

        if ($data['result'] === 'CAPTURED') {
            // Create purchase record
            $purchase = JobseekerTrainingMaterialPurchase::create([
                'jobseeker_id'     => $data['udf1'],
                // 'user_type'   => $data['udf2'],
                'material_id' => $data['udf4'],
                'status'      => 'active',
                'track_id'    => $data['trackId'],
                'transaction_id' => $data['transId'],
                'payment_status' => 'paid',
                'response_payload'=> json_encode($data),
            ]);

            // Add entry in payment history
            PaymentHistory::create([
                'user_type'      => $data['udf2'],        // e.g. jobseeker
                'user_id'        => $data['udf1'],        // payer id
                'receiver_type'  => 'trainer',          // always platform
                'receiver_id'    => $data['udf7'],                  // null for system
                
                // Purpose
                'payment_for'    => 'course',             // ⚠ make sure migration enum allows 'course'
                
                // Payment details
                'amount_paid'    => $data['amt'],
                'tax'            => $data['taxed_amount'] ?? 0.00,
                'applied_coupon' => $data['coupon_code'] ?? null,  // coupon code if any
                'payment_status' => 'completed',
                'transaction_id' => $data['transId'] ?? null,
                'track_id'       => $data['trackId'] ?? null,
                'order_id'       => 'ORD-' . $data['udf1'] . '-' . $data['udf4'] . '-' . $data['ref'],
                'currency'       => 'SAR',
                'payment_method' => 'Al Rajhi',
                'paid_at'        => now(),
            ]);

        }

        return redirect()->back()->with('success', 'Course purchased successfully!');
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
                        : 'awaiting_payment';

                    $payment->update([
                        'transaction_id'   => $data['transId'] ?? null,
                        'status'           => $status,
                        'payment_status'   => $status,
                        'response_payload' => json_encode($data),
                    ]);
                }
            }
        }

        return redirect()->back()->with('error', 'Payment failed or cancelled. Please try again.');
    }
}
