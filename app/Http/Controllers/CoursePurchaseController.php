<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentHelper;
use App\Models\PaymentHistory;
use App\Models\Jobseekers;
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
        // echo "processPurchaseCoursePayment"; die;
        $validator = Validator::make($request->all(), [
            'material_id' => 'required|exists:training_materials,id',
            'training_type' => 'required|in:online,classroom,recorded',
            'batch_id'      => 'required_if:training_type,online,required_if:training_type,classroom|nullable',
            'user_id'     => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $material = TrainingMaterial::findOrFail($request->material_id);

        // Generate reference
        $referenceNo = "TRK-COURSE-" 
             . strtoupper(substr($request->training_type, 0, 3)) // ONL, CLA, REC
             . '-' . $request->material_id 
             . '-' . $request->batch_id
             . '-' . $request->user_id;
             
        // Create payment request record
        $paymentRequest = JobseekerTrainingMaterialPurchasePaymentRequest::create([
            'jobseeker_id'     => $request->user_id,   // use correct column name
            'trainer_id'       => $material->trainer_id, // assuming material belongs to a trainer
            'material_id'      => $material->id,
            'batch_id'         => $request->batch_id ?? null,

            'request_payload'  => null,
            'track_id'         => $referenceNo,

            'type'             => 'buyNow', // or buyForCorporate/cart from request
            'training_type'    => $material->training_type ?? 'recorded', 

            'transaction_id'   => null,
            'payment_status'   => 'initiated',

            'tax'              => $request->tax_rate,
            'amount'           => $request->original_price,
            'amount_paid'      => $request->amount_paid, // total after tax/discount
            'currency'         => 'SAR',
            'payment_gateway'  => 'Al Rajhi',

            'coupon_type'      => $request->coupon_type ,
            'coupon_code'      => $request->coupon_code ,
            'coupon_amount'    => $request->coupon_amount ,
        ]);

        // Prepare Neoleap payload
        $config = config('neoleap');
        $transactionDetails = [
            "id"           => $config['tranportal_id'],       // Neoleap config
            "amt"          => $paymentRequest->amount_paid,   // Final payable amount
            "action"       => "1",
            "password"     => "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682",                          // SAR currency code
            "trackId"      => $paymentRequest->track_id,      // Reference no.

            // UDFs (custom values you want to send)
            "udf1"         => $paymentRequest->jobseeker_id,   // user_id
            "udf2"         => $paymentRequest->type,           // buyNow / cart / corporate
            "udf3"         => $paymentRequest->id,             // payment_request id
            "udf4"         => $paymentRequest->material_id,    // course id
            "udf5"         => 'course',                        // static value

            // Extra UDFs you can send (udfnext = udf6, udf7, etc.)
            "udf6"         => $paymentRequest->batch_id ?? 'N/A',        // batch id if any
            "udf7"         => $paymentRequest->trainer_id,               // trainer id
            "udf8"         => $paymentRequest->training_type,            // online / classroom / recorded
            "udf9"         => $paymentRequest->coupon_code ?? 'N/A',     // coupon code
            "udf10"        => $paymentRequest->coupon_amount ?? 0,       // discount value
            "udf11"        => $paymentRequest->amount,                   // original price
            "udf12"        => $paymentRequest->tax ?? 0,                 // tax rate
            "udf13"        => $paymentRequest->currency,                 // SAR
            "udf14"        => $paymentRequest->payment_gateway,          // Al Rajhi
            "udf15"        => $paymentRequest->payment_status,           // initiated

            "langid"       => "en",
            "responseURL"  => $config['course_success_url'],
            "errorURL"     => $config['course_failure_url'],
        ];

        // echo "<pre>"; print_r($transactionDetails); die;
        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata     = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));


        $paymentRequest->update(['request_payload' => $jsonTrandata]);

        $payload = [[
            "id"          => $config['tranportal_id'],
            "trandata"    => $trandata,
            "responseURL" => $config['course_success_url'],
            "errorURL"    => $config['course_failure_url']
        ]];
        
        $payloads = json_encode($payload, JSON_UNESCAPED_SLASHES);
        // echo "<pre>"; print_r($payloads); die;
        // Send request to Neoleap
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payloads,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        $result = $data[0]['result'] ?? null;
        // echo "<pre>"; print_r($data); die;

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

        echo "done"; die;
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
                'user_id'     => $data['udf1'],
                'user_type'   => $data['udf2'],
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
                'tax'            => $data['tax'] ?? 0.00,
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
