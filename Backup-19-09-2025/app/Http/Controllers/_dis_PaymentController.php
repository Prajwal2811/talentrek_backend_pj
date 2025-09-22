<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\PaymentHelper;
use App\Models\Api\BookingSession;
use App\Models\Payment\JobseekerSessionBookingPaymentRequest;
use App\Models\PurchasedSubscription;
use App\Models\SubscriptionPlan;
use App\Models\Jobseekers;
use Auth;


class PaymentControllersss extends Controller
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
       
        // echo "=----->".urldecode($trandata = PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));exit;
        $config = config('neoleap');

        $responseTrandata = $request->input('trandata');
        $decrypted = $this->decryptTrandata($responseTrandata, $config['secret_key']);
        $data = json_decode($decrypted, true);
        // echo "<pre>"; print_r($data); die;

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
        // echo urldecode($trandata = PaymentHelper::encryptAES($responseTrandata,$config['secret_key']));exit;

        $responseTrandata = $request->input('trandata');
        $decrypted = $this->decryptTrandata($responseTrandata, $config['secret_key']);
        $data = json_decode($decrypted, true);
        echo "<pre>"; print_r($data); die;

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
                    'status'  => $data['result'] === 'CAPTURED' ? 'confirmed' : 'awaiting_payment',
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
                'transaction_id' => $data['transId'] ?? null, // Gateway returned transaction ID
                'payment_status' => $data['result'] === 'CAPTURED' ? 'success' : 'pending', // Default pending
                'response_payload' => !empty($data)
                                        ? json_encode($data)
                                        : null,

                //Amout update
                'amount'       => $data['udf7'] ?? null,   // Payment request amount
                'tax'       => $data['udf8'] ?? null,   // Payment request tax
                'total_amount'       => $data['amt'] ?? null,   // Payment request track id
            ]);
            
            return view('payment.slotSuccess', [
                'transaction_id' => $data['transId'],
                'amount'         => $data['amt'], // usually returned from gateway
                'date'           => date('d M Y h:i A',strtotime($data['paymentTimestamp'])),
                'description'    => "Slot Booking Payment for Session on " . date('d M Y',strtotime($data['udf6']))."."
            ]);

        } else {
            // ❌ Payment failed
            $booking = JobseekerSessionBookingPaymentRequest::where('track_id', $data['trackId'])->first();

            if ($booking) {
                if (in_array($data['result'], ['NOT CAPTURED', 'DECLINED', 'FAILED', 'CANCELED'])) {
                    $status = 'failed';
                }
                $booking->update([
                    'transaction_id'  => $data['transId'],
                    'status'  => $status,
                    'payment_status'  => $status,
                    'response_payload'=> json_encode($request->all()),
                ]);
            }

            return view('payment.slotFailed', [
                'description'        => $data['ErrorText'] ?? 'Payment failed. Please try again.',
                'transaction_id' => $data['tranid'] ?? null,
                'track_id'       => $data['trackId'] ?? null,
                'amount'         => $data['amt'], // usually returned from gateway
                'date'           => date('d M Y h:i A',strtotime($data['paymentTimestamp'])) 
            ]);
        }
         
        //return response()->json($data);
    }

    


    // Subscription
    public function processSubscriptionPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $jobseeker = auth('jobseeker')->user();

        // Save only plan & user info in session (not inserting record here)
        session([
            'pending_plan_id' => $plan->id,
            'pending_amount'  => $plan->price,
            'pending_user_id' => $jobseeker->id,
        ]);

        // echo "<pre>"; print_r(session()->all()); die;
        // Redirect to hosted payment page
        return $this->redirectToGatewaySubscription($plan->price);
    }

    /**
     * Redirect to Neoleap payment gateway
     */
    protected function redirectToGatewaySubscription($amount)
    {
        $config = config('neoleap');
        $orderId = uniqid('ORD-');


        $transactionDetails = [
            "id"           => $config['tranportal_id'],
            "amt"          => $amount,
            "action"       => "1",
            "password" => "T4#2H#ma5yHv\$G7",
            "currencyCode" => "682", // SAR
            "trackId"      => "Talentrek-" . time(),
            "udf1"         => "Talentrek",
            "udf2"         => "Sub-" . uniqid(),
            "udf3"         => session('pending_user_id'),
            "udf4"         => $orderId,
            "langid"       => "en",
            "responseURL"  => $config['subscription_success_url'],
            "errorURL"     => $config['subscription_failure_url']
        ];

        //  echo "<pre>"; print_r($transactionDetails); die;

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata     = PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']);
        $trandata     = strtoupper($trandata);


        $payload = [[
            "id"          => $config['tranportal_id'],
            "trandata"    => $trandata,
            "responseURL" => $config['subscription_success_url'],
            "errorURL"    => $config['subscription_failure_url']
        ]];


        $payloads = json_encode($payload, JSON_UNESCAPED_SLASHES);

        //  echo "<pre>"; print_r($payloads); die;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $payloads,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($response, true);
        $result = $data[0]['result'] ?? null;
        // echo "<pre>"; print_r($data); die;
        // echo "<pre>"; print_r(session()->all()); die;

        if ($result) {
            [$paymentId, $paymentUrl] = explode(":", $result, 2);
            return redirect()->away($paymentUrl . "?PaymentID=" . $paymentId);
        }

        return back()->with('error', 'Unable to initiate payment, please try again.');
    }

    /**
     * Payment success callback
     */
    public function successSubscription(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if (!$responseTrandata) {
            return redirect()->route('jobseeker.dashboard')
                ->with('error', 'Invalid payment response.');
        }

        $decrypted =  urldecode($trandata = PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        $data = json_decode($decrypted, true);
        $data =$data[0];
        // echo "<pre>"; print_r($data); die;
        echo "<pre>"; print_r(session()->all()); die;
         if ($data && $data['result'] === 'CAPTURED') {
            $planId = session('pending_plan_id');
            $amount = session('pending_amount');
            $userId = session('pending_user_id');
            echo "<pre>"; print_r($planId); 
            echo "<pre>"; print_r($amount);
            echo "<pre>"; print_r($userId); die;
            if ($planId && $userId) {
                echo "done"; die;
                $plan = SubscriptionPlan::findOrFail($planId);
                $jobseeker = Jobseekers::find($userId);

                $subscription = PurchasedSubscription::create([
                    'user_id'              => $jobseeker->id,
                    'user_type'            => 'jobseeker',
                    'subscription_plan_id' => $plan->id,
                    'start_date'           => now(),
                    'end_date'             => now()->addDays($plan->duration_days),
                    'amount_paid'          => $amount,
                    'payment_status'       => 'paid',

                    // Gateway details
                    'transaction_id'       => $data['tranid']   ?? null,
                    'payment_id'           => $data['paymentid'] ?? null,
                    'track_id'             => $data['trackid'] ?? null,
                    'order_id'             => $data['udf4']    ?? null,
                    'currency'             => $data['currency'] ?? null,
                    'result'               => $data['result'] ?? null,
                    'raw_response'         => json_encode($data, JSON_UNESCAPED_SLASHES),
                ]);

                // Update jobseeker subscription
                $jobseeker->isSubscriptionBuy = 'yes';
                $jobseeker->active_subscription_plan_id = $subscription->id;
                $jobseeker->save();

                // 🔑 Re-login the jobseeker so they don't get logged out after redirect
                Auth::guard('jobseeker')->login($jobseeker);
            }

             echo "not-done"; die;

            // Clear session
            session()->forget(['pending_plan_id', 'pending_amount', 'pending_user_id']);

            return redirect()->route('jobseeker.dashboard')
                ->with('success', 'Subscription purchased successfully!');
        }

        return redirect()->route('jobseeker.dashboard')
            ->with('error', 'Payment failed or cancelled.');
    }



    /**
     * Payment failure callback
     */
    public function failureSubscription(Request $request)
    {
        session()->forget(['pending_plan_id', 'pending_amount', 'pending_user_id']);

        return redirect()->route('jobseeker.dashboard')
            ->with('error', 'Payment failed. Please try again.');
    }

}
