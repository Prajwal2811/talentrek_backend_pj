<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\PaymentHelper;
use App\Models\Api\BookingSession;
use App\Models\Payment\JobseekerSessionBookingPaymentRequest;



class PaymentController extends Controller
{
    public function checkout()
    {
        return view('payment.checkout');
    }

    public function pay()
    {
        
        $config = config('neoleap');

        $orderId   = uniqid('ORD-');
        $amount    = 1000.00; // example SAR

        $transactionDetails = [
            "id"          => $config['tranportal_id'], // Your Merchant ID from Al Rajhi
            "amt"         => $amount,          // Transaction amount
            "action"      => "1",               // 1 = Purchase, 4 = Authorization
            "password"    => "T4#2H#ma5yHv\$G7",
            "currencyCode"=> "682",             // ISO numeric code (682 = SAR)
            "trackId"     => "Talentrek-" . time() ,          // Unique tracking ID
            "udf1"        => "Talentrek",     // Optional user-defined fields
            "udf2"        => "Order456",
            "udf3"        => "sdf",
            "udf4"        => "sdfsd",
            "udf5"        => "sdfsd",
            "langid"      => "en",
            "responseURL" => $config['return_url'],
            "errorURL"    => $config['return_url']
        ];
        $payload = [$transactionDetails];

        $jsonTrandata = json_encode($payload, JSON_UNESCAPED_SLASHES);
        
        $trandata = PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']);
        $trandatas = strtoupper($trandata) ;

        

        $payload = [[
            "id"          => "6Q3zyPDZl20yj3E",
            "trandata"    => $trandatas, // ✅ variable here
            "responseURL" => $config['return_url'],
            "errorURL"    => $config['return_url']
        ]];

       $payloads = json_encode($payload, JSON_UNESCAPED_SLASHES) ;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payloads,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;exit;
        // Decode JSON
        $data = json_decode($response, true);

        // Get the "result" value
        $result = $data[0]['result'];

        // Split by colon
        list($paymentId, $paymentUrl) = explode(":", $result, 2);

        
        // Final redirect URL
        $redirectUrl = $paymentUrl . "?PaymentID=" . $paymentId;

        // Redirect user
        return redirect()->away($redirectUrl);
        exit;
        // Redirect to bank payment page
        // return view('payment.redirect', [
        //     'paymentUrl' => $config['payment_url'],
        //     'payload'   => $payload
        // ]);
    }

    public function success(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');
       
        echo "=----->".urldecode($trandata = PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));exit;
        $config = config('neoleap');

        $responseTrandata = $request->input('trandata');
        $decrypted = $this->decryptTrandata($responseTrandata, $config['secret_key']);
        $data = json_decode($decrypted, true);

        if ($data && $data['result'] === 'CAPTURED') {
            // ✅ Payment success
            // update order as paid
        } else {
            // ❌ Payment failed
        }

        return response()->json($data);
    }
    public function failure(Request $request)
    {
        //echo $responseTrandata = $request->input('trandata');
        $config = config('neoleap');
        echo urldecode($trandata = PaymentHelper::encryptAES($responseTrandata,$config['secret_key']));exit;

        $responseTrandata = $request->input('trandata');
        $decrypted = $this->decryptTrandata($responseTrandata, $config['secret_key']);
        $data = json_decode($decrypted, true);

        if ($data && $data['result'] === 'CAPTURED') {
            // ✅ Payment success
            // update order as paid
        } else {
            // ❌ Payment failed
        }

        return response()->json($data);
    }

    public function successBookingSlot(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');
       
        $decrypted =  urldecode($trandata = PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        
        $data = json_decode($decrypted, true);
        $data =$data[0];

        if ($data && $data['result'] === 'CAPTURED') {
            //echo "<pre>";print_r($data);
            // 🔎 Find the booking using trackId (reference_no in your DB)
            $booking = JobseekerSessionBookingPaymentRequest::where('track_id', $data['trackId'])->first();
            if ($booking) {
                $booking->update([
                    'transaction_id'  => $data['transId'],
                    'payment_status'  => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
                    'response_payload'=> json_encode($request->all()),
                ]);
            }

            // ✅ Payment success
            // update order as paid
             BookingSession::create([
                'jobseeker_id'   => $data['udf1'],
                'user_type'      => $data['udf2'],
                'user_id'        => $data['udf3'],
                'booking_slot_id'=> $data['udf4'],
                'slot_mode'      => $data['udf5'],
                'slot_date'      => $data['udf6'],
                'slot_time'      => '',

                // Newly added fields
                'track_id'       => $data['trackId'] ?? null,   // Payment request track id
                'transaction_id' => $data['transaction_id'] ?? null, // Gateway returned transaction ID
                'payment_status' => $data['payment_status'] ?? 'pending', // Default pending
                'response_payload' => !empty($data['response_payload'])
                                        ? json_encode($data['response_payload'])
                                        : null,
            ]);

        } else {
            // ❌ Payment failed
        }
         return view('payment.slotSuccess', [
            'transaction_id' => $data['transId'],
            'amount'         => $data['amt'], // usually returned from gateway
            'date'           => date('d M Y h:i A',strtotime($data['paymentTimestamp'])),
        ]);
        //return response()->json($data);
    }

    
}
