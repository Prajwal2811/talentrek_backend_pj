<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\PaymentHelper;
use App\Models\Api\BookingSession;
use App\Models\Payment\JobseekerSessionBookingPaymentRequest;
use App\Models\Payment\PurchasedSubscriptionPaymentRequest;
use App\Models\PurchasedSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Jobseekers;
use App\Models\Mentors;
use App\Models\Assessors;
use App\Models\Recruiters;
use App\Models\Trainers;
use App\Models\Coach;

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

    


    // Suscription 
    // public function processSubscriptionPayment(Request $request)
    // {
    //     $request->validate([
    //         'plan_id' => 'required|exists:subscription_plans,id',
    //     ]);

    //     $plan = SubscriptionPlan::findOrFail($request->plan_id);
    //     $jobseeker = auth('jobseeker')->user();

    //     // Save only plan & user info in session (not inserting record here)
    //     session([
    //         'pending_plan_id' => $plan->id,
    //         'pending_amount'  => $plan->price,
    //         'pending_user_id' => $jobseeker->id,
    //     ]);

    //     // Redirect to hosted payment page
    //     return $this->redirectToGatewaySubscription($plan->price);
    // }

    // /**
    //  * Redirect to Neoleap payment gateway
    //  */
    // protected function redirectToGatewaySubscription($amount)
    // {
    //     $config = config('neoleap');
    //     $orderId   = uniqid('ORD-');

    //     $transactionDetails = [
    //         "id"          => $config['tranportal_id'],
    //         "amt"         => $amount,
    //         "action"      => "1", // Purchase
    //         "password"     => "T4#2H#ma5yHv\$G7",
    //         "currencyCode"=> "682", // SAR
    //         "trackId"     => "Talentrek-" . time(),
    //         "udf1"        => "Talentrek",
    //         "udf2"        => "Sub-" . uniqid(),
    //         "udf3"        => auth('jobseeker')->id(),
    //         "udf4"        => $orderId,
    //         "langid"      => "en",
    //         "responseURL" => $config['subscription_success_url'],
    //         "errorURL"    => $config['subscription_failure_url']
    //     ];

    //     $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
    //     $trandata     = PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']);
    //     $trandata     = strtoupper($trandata);

    //     $payload = [[
    //         "id"          => $config['tranportal_id'],
    //         "trandata"    => $trandata,
    //         "responseURL" => $config['subscription_success_url'],
    //         "errorURL"    => $config['subscription_failure_url']
    //     ]];

    //     $payloads = json_encode($payload, JSON_UNESCAPED_SLASHES);

    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => $payloads,
    //         CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
    //     ));

    //     $response = curl_exec($curl);
    //     curl_close($curl);

    //     $data = json_decode($response, true);
    //     $result = $data[0]['result'] ?? null;

    //     if ($result) {
    //         list($paymentId, $paymentUrl) = explode(":", $result, 2);
    //         $redirectUrl = $paymentUrl . "?PaymentID=" . $paymentId;
    //         return redirect()->away($redirectUrl);
    //     }

    //     return back()->with('error', 'Unable to initiate payment, please try again.');
    // }

    // /**
    //  * Payment success callback
    //  */
    // public function successSubscription(Request $request)
    // {
    //     $config = config('neoleap');
    //     $responseTrandata = $request->input('trandata');

    //     $decrypted = PaymentHelper::decryptAES($responseTrandata, $config['secret_key']);
    //     $data = json_decode($decrypted, true);

    //     if ($data && ($data['result'] ?? null) === 'CAPTURED') {
    //         $planId = session('pending_plan_id');
    //         $amount = session('pending_amount');
    //         $userId = session('pending_user_id');
    //         $jobseeker = auth('jobseeker')->user();

    //         if ($planId && $userId && $jobseeker) {
    //             $plan = SubscriptionPlan::findOrFail($planId);

    //             // Create subscription only after success
    //             $subscription = PurchasedSubscription::create([
    //                 'user_id'              => $jobseeker->id,
    //                 'user_type'            => 'jobseeker',
    //                 'subscription_plan_id' => $plan->id,
    //                 'start_date'           => now(),
    //                 'end_date'             => now()->addDays($plan->duration_days),
    //                 'amount_paid'          => $amount,
    //                 'payment_status'       => 'paid',
    //             ]);

    //             // Update jobseeker active subscription
    //             $jobseeker->isSubscribtionBuy = 'yes';
    //             $jobseeker->active_subscription_plan_id = $subscription->id;
    //             $jobseeker->save();
    //         }

    //         // Clear session
    //         session()->forget(['pending_plan_id', 'pending_amount', 'pending_user_id']);

    //         return redirect()->route('jobseeker.dashboard')
    //             ->with('success', 'Subscription purchased successfully!');
    //     }

    //     return redirect()->route('jobseeker.dashboard')
    //         ->with('error', 'Payment failed or cancelled.');
    // }

    // /**
    //  * Payment failure callback
    //  */
    // public function failureSubscription(Request $request)
    // {
    //     // Just clear pending session (no record created)
    //     session()->forget(['pending_plan_id', 'pending_amount', 'pending_user_id']);

    //     return redirect()->route('jobseeker.dashboard')
    //         ->with('error', 'Payment failed. Please try again.');
    // }

    public function paymentResponseSubscription(Request $request)
    {
        Log::info("=== Neoleap Response ===", $request->all());
        return response()->json($request->all());
    }

    public function paymentErrorSubscription(Request $request)
    {
        session()->forget(['pending_plan_id', 'pending_amount', 'pending_user_id']);
        return redirect()->route('jobseeker.dashboard')
            ->with('error', 'Payment cancelled or failed.');
    }

    public function successSubscriptionMobile(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');
       
        $decrypted =  urldecode($trandata = PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
        
        $data = json_decode($decrypted, true);
        $data =$data[0];
        //print_r($data);exit;
        if ($data && $data['result'] === 'CAPTURED') {
            //echo "<pre>";print_r($data);
            // 🔎 Find the booking using trackId (reference_no in your DB)
            $booking = PurchasedSubscriptionPaymentRequest::where('track_id', $data['trackId'])->first();
            if ($booking) {
                $booking->update([
                    'transaction_id'  => $data['transId'],
                    'status'  => $data['result'] === 'CAPTURED' ? 'active' : 'pending',
                    'payment_status'  => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
                    'response_payload'=> json_encode($request->all()),
                ]);
            }

            // ✅ Payment success
            // update order as paid
            // PurchasedSubscription::create([
            //     'user_id' => $jobseeker->id,
            //     'user_type' => $request->type,
            //     'subscription_plan_id' => $plan->id,
            //     'start_date' => now(),
            //     'end_date' => now()->addDays($plan->duration_days),
            //     'amount_paid' => $plan->price,
            //     //'status' => 'active',
            //     'payment_status' => 'paid',
            // ]);
            // Get the user's latest subscription
            $latestSubscription = PurchasedSubscription::where('user_id', $data['udf1'])
                ->where('user_type', $data['udf2'])
                ->where('payment_status', 'paid')
                ->orderBy('end_date', 'desc')
                ->first();

            // Determine start date
            if ($latestSubscription && Carbon::parse($latestSubscription->end_date)->isFuture()) {
                $startDate = Carbon::parse($latestSubscription->end_date)->addDay();
            } else {
                $startDate = now();
            }

            // Calculate end date (based on duration from udf6)
            $endDate = $startDate->copy()->addDays($data['udf6']);

            PurchasedSubscription::create([
                'user_id' => $data['udf1'],
                'user_type' => $data['udf2'],
                'subscription_plan_id' => $data['udf4'],
                'amount_paid' => $data['amt'],
                'tax' => $data['udf7'],
                'amount' => $data['udf8'],
                //'status' => 'active',
                // Newly added fields
                'track_id'       => $data['trackId'] ?? null,   // Payment request track id
                'transaction_id' => $data['transId'] ?? null, // Gateway returned transaction ID
                'payment_status' => $data['result'] === 'CAPTURED' ? 'paid' : 'pending', // Default pending
                'response_payload' => !empty($data)
                                        ? json_encode($data)
                                        : null,
                'start_date'       => $startDate,
                'end_date'         => $endDate,
            ]);          
            
            return view('payment.slotSuccess', [
                'transaction_id' => $data['transId'],
                'amount'         => $data['amt'], // usually returned from gateway
                'date'           => date('d M Y h:i A',strtotime($data['paymentTimestamp'])),
                'description'    => "Subscription Payment for ".$data['udf5']." on " . date('d M Y h:i A',strtotime($data['paymentTimestamp']))."."
            ]);

        } else {
            // ❌ Payment failed
            $booking = PurchasedSubscriptionPaymentRequest::where('track_id', $data['trackId'])->first();

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





    public function processSubscriptionPayment(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:subscription_plans,id',
            'user_id' => 'required',
            'type'    => 'required|in:jobseeker,mentor,assessor,coach,trainer,recruiter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Map user type to model
        $modelMap = [
            'jobseeker' => Jobseekers::class,
            'mentor'    => Mentors::class,
            'assessor'  => Assessors::class,
            'coach'     => Coach::class,
            'trainer'   => Trainers::class,
            'recruiter' => Recruiters::class,
        ];
        $model = $modelMap[$request->type];

        $user = $model::findOrFail($request->user_id);

        // Generate reference
        $referenceNo = "TRK-SUB-" . strtoupper(substr($request->type, 0, 3)) . '-' . $request->plan_id . '-' . $request->user_id . '-' . date('YmdHi');

        // Create payment request record
        $booking = PurchasedSubscriptionPaymentRequest::create([
            'subscription_plan_id' => $plan->id,
            'user_id'              => $request->user_id,
            'user_type'            => $request->type,
            'status'               => 'pending',
            'track_id'             => $referenceNo,
            'amount'               => $plan->price,
            'tax'                  => 0.00,
            'total_amount'         => $plan->price,
            'currency'             => 'SAR',
            'payment_gateway'      => 'Al Rajhi',
            'request_payload'      => null,
            'transaction_id'       => null,
            'payment_status'       => 'initiated',
            'response_payload'     => null,
        ]);

        // Prepare transaction payload for Neoleap
        $config = config('neoleap');
        $transactionDetails = [
            "id"           => $config['tranportal_id'],
            "amt"          => number_format($plan->price, 2, '.', ''),
            "action"       => "1",
            "password"     => "T4#2H#ma5yHv\$G7", // moved to config
            "currencyCode" => "682",
            "trackId"      => $referenceNo,
            "udf1"         => $request->user_id,
            "udf2"         => $request->type,
            "udf3"         => $booking->id,
            "udf4"         => $plan->id,
            "udf5"         => $request->type,
            "udf6"         => $plan->duration_days,
            "udf7"         => '0.00',
            "udf8"         => number_format($plan->price, 2, '.', ''),
            "langid"       => "en",
            "responseURL"  => $config['subscription_success_url'],
            "errorURL"     => $config['subscription_failure_url'],
        ];

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata     = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));

        $booking->update(['request_payload' => $jsonTrandata]);

        $payload = [[
            "id"          => $config['tranportal_id'],
            "trandata"    => $trandata,
            "responseURL" => $config['subscription_success_url'],
            "errorURL"    => $config['subscription_failure_url']
        ]];

        $payloads = json_encode($payload, JSON_UNESCAPED_SLASHES);

        // Send request to Neoleap
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payloads,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
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
    public function successSubscription(Request $request)
    {
        $data = $request->all();

        $trackId   = $data['trackId'] ?? null;
        $paymentId = $data['transId'] ?? null;
        $result    = $data['result'] ?? null;

        if (!$trackId) {
            return redirect()->back()->with('error', 'Invalid payment response. Track ID missing.');
        }

        $booking = PurchasedSubscriptionPaymentRequest::where('track_id', $trackId)->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Subscription payment request not found.');
        }

        $booking->update([
            'transaction_id'   => $paymentId,
            'status'           => $result === 'CAPTURED' ? 'confirmed' : 'awaiting_payment',
            'payment_status'   => $result === 'CAPTURED' ? 'success' : 'failed',
            'response_payload' => json_encode($data),
        ]);

        if ($result === 'CAPTURED') {
            $plan = SubscriptionPlan::find($booking->subscription_plan_id);

            PurchasedSubscription::create([
                'subscription_plan_id' => $booking->subscription_plan_id,
                'user_id'              => $booking->user_id,
                'user_type'            => $booking->user_type,
                'start_date'           => now(),
                'end_date'             => now()->addDays($plan->duration_days),
            ]);

            return redirect()->back()->with('success', 'Subscription purchased successfully!');
        }

        return redirect()->back()->with('error', 'Payment not captured. Please try again.');
    }

    /**
     * Failure callback
     */
    public function failureSubscription(Request $request)
    {
        $data      = $request->all();
        $trackId   = $data['trackId'] ?? null;
        $paymentId = $data['transId'] ?? null;
        $result    = $data['result'] ?? null;

        if ($trackId) {
            $booking = PurchasedSubscriptionPaymentRequest::where('track_id', $trackId)->first();

            if ($booking) {
                $status = in_array($result, ['NOT CAPTURED', 'DECLINED', 'FAILED', 'CANCELED'])
                    ? 'failed'
                    : 'awaiting_payment';

                $booking->update([
                    'transaction_id'   => $paymentId,
                    'status'           => $status,
                    'payment_status'   => $status,
                    'response_payload' => json_encode($data),
                ]);
            }
        }

        return redirect()->back()->with('error', 'Payment failed or cancelled. Please try again.');
    }

}
