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
use Illuminate\Support\Facades\Validator;
use App\Models\PurchasedSubscriptionPaymentRequest;
use App\Models\Mentors;
use App\Models\Assessors;
use App\Models\Recruiters;
use App\Models\Trainers;
use App\Models\Coach;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    /**
     * Process subscription payment
     */
    
//  public function successSubscription(Request $request)
//     {
       
//         $config = config('neoleap');
//         $responseTrandata = $request->input('trandata');

//         if (!$responseTrandata) {
//             return redirect()->back()->with('error', 'Missing payment response.');
//         }

//         $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
//         $data = json_decode($decrypted, true);

//         if (!$data || !isset($data[0])) {
//             return redirect()->back()->with('error', 'Invalid payment response.');
//         }

//         $data = $data[0];
//         $user = Jobseekers::findOrFail($data['udf1']);
       
//         //die;
//         // Generate reference
//         if ($user) {
//             // 🔹 Re-login user manually
//             Auth::guard('jobseeker')->login($user);
//         }
//         $booking = PurchasedSubscriptionPaymentRequest::where('track_id', $data['trackId'])->first();
//         if (!$booking) {
//             return redirect()->back()->with('error', 'Payment request not found.');
//         }

//         $booking->update([
//             'transaction_id'  => $data['transId'] ?? null,
//             'status'          => $data['result'] === 'CAPTURED' ? 'active' : 'pending',
//             'payment_status'  => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
//             'response_payload'=> json_encode($data),
//         ]);

//         // Only create subscription if captured
//         if ($data['result'] === 'CAPTURED') {
//             // Get the user's latest subscription
//             $latestSubscription = PurchasedSubscription::where('user_id', $data['udf1'])
//                 ->where('user_type', $data['udf2'])
//                 ->where('payment_status', 'paid')
//                 ->orderBy('end_date', 'desc')
//                 ->first();

//             // Determine start date
//             if ($latestSubscription && Carbon::parse($latestSubscription->end_date)->isFuture()) {
//                 $startDate = Carbon::parse($latestSubscription->end_date)->addDay();
//             } else {
//                 $startDate = now();
//             }

//             // Calculate end date
//             $endDate = $startDate->copy()->addDays($data['udf6']);

//             PurchasedSubscription::create([
//                 'user_id'           => $data['udf1'],
//                 'user_type'         => $data['udf2'],
//                 'subscription_plan_id' => $data['udf4'],
//                 'amount_paid'       => $data['amt'],
//                 'tax'               => $data['udf7'],
//                 'amount'            => $data['udf8'],
//                 'track_id'          => $data['trackId'] ?? null,
//                 'currency'          => 'SAR',   
//                 'transaction_id'    => $data['transId'] ?? null,
//                 'payment_status'    => 'paid',
//                 'response_payload'  => json_encode($data),
//                 'start_date'        => $startDate,
//                 'end_date'          => $endDate,
//             ]);
//         }

//         // 🔹 Update user's subscription flag
//         $userModelMap = [
//             'jobseeker' => Jobseekers::class,
//             'mentor'    => Mentors::class,
//             'assessor'  => Assessors::class,
//             'coach'     => Coach::class,
//             'trainer'   => Trainers::class,
//             'recruiter' => Recruiters::class,
//         ];

//         if (isset($userModelMap[$data['udf2']])) {
//             $userModel = $userModelMap[$data['udf2']];
//             $userModel::where('id', $data['udf1'])->update([
//                 'isSubscribtionBuy' => 'yes'
//             ]);
//         }

//         // Redirect based on type
//         $redirectRoutes = [
//             'jobseeker' => 'jobseeker.dashboard',
//             'mentor'    => 'mentor.dashboard',
//             'assessor'  => 'assessor.dashboard',
//             'coach'     => 'coach.dashboard',
//             'trainer'   => 'trainer.dashboard',
//             'recruiter' => 'recruiter.dashboard',
//         ];
//         $type = $data['udf2'];
//         $route = $redirectRoutes[$type];


        
//         return redirect()->route('jobseeker.profile')->with('success', 'Subscription purchased successfully!');
//     }


}
