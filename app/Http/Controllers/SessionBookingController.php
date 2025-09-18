<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentHelper;
use App\Models\PaymentHistory;
use App\Models\SessionBooking;
use App\Models\SessionBookingPaymentRequest;
use App\Models\Mentors;
use App\Models\Assessors;
use App\Models\Coach;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class SessionBookingController extends Controller
{
    /**
     * Initiate session booking payment
     */
    public function processBookingPayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_type'       => 'required|in:mentor,assessor,coach',
            'user_id'         => 'required', // the mentor/assessor/coach id
            'mode'            => 'required|in:online,offline',
            'date'            => 'required|date|after_or_equal:today',
            'slot_id'         => 'required',
            'slot_time'       => 'required',
            'jobseeker_id'    => 'required|exists:jobseekers,id',
            'original_price'  => 'required|numeric',
            'tax_rate'        => 'required|numeric',
            'amount_paid'     => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Validate user existence
        $userTypeModelMap = [
            'mentor'   => Mentors::class,
            'assessor' => Assessors::class,
            'coach'    => Coach::class,
        ];

        $modelClass = $userTypeModelMap[$request->user_type];
        $user = $modelClass::findOrFail($request->user_id);

        // Generate unique reference
        $referenceNo = 'TRK-' . strtoupper($request->user_type)
                . '-' . $request->user_id
                . '-' . $request->slot_id
                . '-' . $request->jobseeker_id
                . '-' . date('YmdHi');


        // Create payment request record
        $paymentRequest = SessionBookingPaymentRequest::create([ 
            'jobseeker_id' => $request->jobseeker_id, 
            'user_type' => $request->user_type, 
            'user_id' => $request->user_id, 
            'slot_mode' => $request->mode, 
            'slot_date' => $request->date, 
            'booking_slot_id' => $request->slot_id, 
            'slot_time' => $request->slot_time, 
            'request_payload' => null, 
            'track_id' => $referenceNo, 
            'payment_status' => 'initiated', 
            'tax' => $request->tax_rate, 
            'amount' => $request->original_price, 
            'total_amount' => $request->amount_paid, 
            'currency' => 'SAR', 
            'payment_gateway' => 'Al Rajhi',
            'response_payload'     => null,
            'tax_percentage'    => $request->tax_rate,
            'coupon_type'       => $request->coupon_type,
            'coupon_code'       => $request->coupon_code,
            'coupon_amount'     => $request->coupon_amount,
         ]);

        $paymentRequest->track_id = 'TRK-' . strtoupper($request->user_type) . '-' . $paymentRequest->id. time();
        $paymentRequest->save();
        // Prepare Neoleap payload
        $config = config('neoleap');

        $timeRange = $request->slot_time;  // e.g. "11:00:00 - 16:00:00"

        if ($timeRange) {
            [$start, $end] = explode(' - ', $timeRange);

            // Format to 12-hour with AM/PM
            $formattedTime = date("h:i A", strtotime($start)) . " - " . date("h:i A", strtotime($end));
        } else {
            $formattedTime = '';
        }

        $transactionDetails = [
            "id"           => $config['tranportal_id'],
            "amt"          => $paymentRequest->total_amount,
            "action"       => "1",
            "password"     => "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682",
            "trackId"      => $paymentRequest->track_id,
            // UDFs
            "udf1"         => $paymentRequest->jobseeker_id,
            "udf2"         => $paymentRequest->user_type,
            "udf3"         => $paymentRequest->id,
            "udf4"         => $paymentRequest->user_id,
            "udf5"         => $paymentRequest->slot_mode,
            "udf6"         => $paymentRequest->booking_slot_id,
            "udf7"         => str_replace([' ', ':'], ['', ''], $formattedTime),
            "udf8"         => $paymentRequest->tax ?? 0,
            "udf9"         => $paymentRequest->amount,
            "udf10"        => $paymentRequest->currency,
            "udf11"        => $paymentRequest->payment_gateway,
            "udf12"        => $paymentRequest->payment_status,
            "langid"       => "en",
            "responseURL"  => $config['session_success_url'],
            "errorURL"     => $config['session_failure_url'],
        ];

        // echo "<pre>"; print_r($transactionDetails); die;

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata     = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));


        $payload = [[
            "id"          => $config['tranportal_id'],
            "trandata"    => $trandata,
            "responseURL" => $config['session_success_url'],
            "errorURL"    => $config['session_failure_url']
        ]];

        
        $payloads = json_encode($payload, JSON_UNESCAPED_SLASHES);
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
        // echo "<pre>"; print_r($response); die;

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
    public function successBooking(Request $request)
    {
        echo "done"; die;
        
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if (!$responseTrandata) {
            return redirect()->back()->with('error', 'Missing payment response.');
        }

        $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $data = json_decode($decrypted, true)[0] ?? null;

        if (!$data) {
            return redirect()->back()->with('error', 'Invalid payment response.');
        }

        $paymentRequest = SessionBookingPaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$paymentRequest) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        $status = $data['result'] === 'CAPTURED' ? 'completed' : 'pending';
        $paymentRequest->update([
            'transaction_id'  => $data['transId'] ?? null,
            'payment_status'  => $status === 'completed' ? 'success' : 'failed',
            'response_payload'=> json_encode($data),
        ]);

        if ($status === 'completed') {
            // Create session booking
            SessionBooking::create([
                'jobseeker_id' => $data['udf1'],
                'user_type'    => $data['udf2'],
                'user_id'      => $data['udf4'],
                'mode'         => $data['udf5'],
                'date'         => $paymentRequest->date,
                'slot_id'      => $paymentRequest->slot_id,
                'slot_time'    => $paymentRequest->slot_time,
                'status'       => 'active',
                'track_id'     => $data['trackId'],
                'transaction_id'=> $data['transId'],
            ]);

            // Add entry in payment history
            PaymentHistory::create([
                'user_type'      => 'jobseeker',
                'user_id'        => $data['udf1'],
                'receiver_type'  => $data['udf2'], // mentor/assessor/coach
                'receiver_id'    => $data['udf4'],
                'payment_for'    => 'session_booking',
                'amount_paid'    => $data['amt'],
                'tax'            => $data['udf8'] ?? 0,
                'payment_status' => 'completed',
                'transaction_id' => $data['transId'] ?? null,
                'track_id'       => $data['trackId'],
                'currency'       => 'SAR',
                'payment_method' => 'Al Rajhi',
                'paid_at'        => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Session booked successfully!');
    }

    /**
     * Failure callback
     */
    public function failureBooking(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if ($responseTrandata) {
            $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
            $data = json_decode($decrypted, true)[0] ?? [];

            if (!empty($data['trackId'])) {
                $paymentRequest = SessionBookingPaymentRequest::where('track_id', $data['trackId'])->first();
                if ($paymentRequest) {
                    $status = in_array($data['result'], ['NOT CAPTURED', 'DECLINED', 'FAILED', 'CANCELED'])
                        ? 'failed'
                        : 'awaiting_payment';

                    $paymentRequest->update([
                        'transaction_id'   => $data['transId'] ?? null,
                        'payment_status'   => $status,
                        'response_payload' => json_encode($data),
                    ]);
                }
            }
        }

        return redirect()->back()->with('error', 'Payment failed or cancelled. Please try again.');
    }
}
