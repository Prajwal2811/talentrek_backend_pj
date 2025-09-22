<?php

namespace App\Http\Controllers\API;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\SubscriptionPlan;
use App\Models\Api\PurchasedSubscription;
use App\Models\Payment\PurchasedSubscriptionPaymentRequest;
use App\Services\PaymentHelper;

use App\Models\Jobseekers;
use App\Models\Assessors;
use App\Models\Mentors;
use App\Models\Coach;
use App\Models\Trainers;

use DB;
use Carbon\Carbon;
class SubscriptionManagementController extends Controller
{
    public function subscriptionPlanListForMCAJT($type='mentor')
    {
    //    try {
            $SubscriptionPlan = SubscriptionPlan::select('*')->where('user_type',$type)->where('is_active',1)->get();
            if ($SubscriptionPlan->isEmpty()) {
                return $this->errorResponse('No Subscription list found for '.$type.' .', 200,[]);
            }
            return $this->successResponse($SubscriptionPlan, 'Subscription list for '.$type.' fetched successfully.');
        // } catch (\Exception $e) {
        //     return $this->errorResponse('An error occurred while fetching Subscription list for '.$type.' .', 500,[]);
        // }
    }

    public function processSubscriptionPaymentForMCAJT(Request $request)
    {        
         $validator = Validator::make($request->all(), [
            'plan_id'     => 'required|exists:subscription_plans,id',
            'card_number' => 'required|string|min:12|max:19',
            'expiry'      => 'required',
            'user_id'     => 'required',
            'type'        => 'required|in:jobseeker,mentor,assessor,coach,trainer',
            'cvv'         => 'required|string|min:3|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }        
      
        //DB::beginTransaction();
        // try {
            $plan = SubscriptionPlan::findOrFail($request->plan_id);
           
            $map = [
                'mentor'    => 'mentors',
                'assessor'  => 'assessors',
                'jobseeker' => 'jobseeker',
                'trainer' => 'trainer',
            ];
            $type = $map[$request->type] ?? 'coach';
            $modelMap = [
                'jobseeker' => \App\Models\Jobseekers::class,
                'mentors' => \App\Models\Mentors::class,
                'assessors' => \App\Models\Assessors::class,
                'coach' => \App\Models\Coach::class,
                'trainer' => \App\Models\Trainers::class,
            ];
            $model = $modelMap[$type];
            $jobseeker = $model::where('id', $request->user_id)->first();           
            $referenceNo0 = 'TRK-' . strtoupper(substr($type, 0, 3)) . '-' .$request->plan_id . '-' . $request->user_id . '-' . date('YmdHi', strtotime(now()));
            $booking = PurchasedSubscriptionPaymentRequest::create([
                'subscription_plan_id' => $plan->id,
                'user_id' => $request->user_id,
                'user_type' => $request->type,
                'status' => 'pending', // or 'pending' until payment confirms
                'track_id' => $referenceNo0, // you can generate a unique track ID
                'amount' => $plan->price,
                'tax' => 0.00, // set actual tax if applicable
                'total_amount' => $plan->price, // price + tax
                'currency' => 'SAR',
                'payment_gateway' => 'Al Rajhi', // or paypal/stripe etc.
                'request_payload' => null, // store API request JSON here
                'transaction_id' => null, // update after successful payment
                'payment_status' => 'initiated', // will be updated after gateway callback
                'response_payload' => null, // store API response JSON here
            ]);
            $trackId = strtoupper(substr($request->type, 0, 3)). '-' . $booking->id . '-' .$request->plan_id . '-' .$request->user_id .'-' . date('YmdHi', strtotime(now()));
            $referenceNo =  "TRK-SUB-" .  $trackId ;

            $booking->update(['track_id' => $referenceNo]);

            $config = config('neoleap');
            $transactionDetails = [
                "id"          => $config['tranportal_id'], // Your Merchant ID from Al Rajhi
                "amt"         => number_format($plan->price, 2, '.', ''),          // Transaction amount
                "action"      => "1",               // 1 = Purchase, 4 = Authorization
                "password"    => "T4#2H#ma5yHv\$G7",
                "currencyCode"=> "682",             // ISO numeric code (682 = SAR)
                "trackId"     => "TRK-SUB-" .  $trackId ,          // Unique tracking ID
                "udf1"        => $request->user_id,     // Mentor/Coach/Assessor/Trainer/Jobseeker Id
                "udf2"        => $request->type,        // Mentor/Coach/Assessor
                "udf3"        => $booking->id,          // Booking Id
                "udf4"        => $request->plan_id,         // Booking Plan id 
                "udf5"        => $request->type,    // Online/Classroom
                "udf6"        => $plan->duration_days,              // Subscription Plan days
                "udf7"        => '0.00',              // Mentor Session Tax
                "udf8"        => number_format($plan->price, 2, '.', ''),              // Mentor session Slot Price
                "langid"      => "en",                      // change to ar when goes live for arabic default
                "responseURL" => $config['success_subscription_mobile_url'],
                "errorURL"    => $config['success_subscription_mobile_url']
            ];
            $payload = [$transactionDetails];

            $jsonTrandata = json_encode($payload, JSON_UNESCAPED_SLASHES);
        
            $trandata = PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']);
            $trandatas = strtoupper($trandata) ;
            $booking->update(['request_payload' => $jsonTrandata]);
            $payload = [[
                "id"          => $config['tranportal_id'],
                "trandata"    => $trandatas, // ✅ variable here
                "responseURL" => $config['success_subscription_mobile_url'],
                "errorURL"    => $config['success_subscription_mobile_url']
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
            // If not exists, insert
            // BookingSession::create([
            //     'jobseeker_id' => $request->jobSeekerId,
            //     'user_id' => $request->userId,
            //     'user_type' => $request->roleType,
            //     'slot_date' => $sessionDate,
            //     'slot_time' =>  '',
            //     'booking_slot_id' => $request->slot_id,
            //     'slot_mode' => $request->trainingMode,
            // ]);

            // Return success with data
            return response()->json([
                'success' => true,
                'redirectUrl' => $redirectUrl,
                'message' => ucwords($request->type).' payment redirection url.'
            ]);

            // echo "done"; exit; 
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
            // echo "done"; exit; 
            // $jobseeker->isSubscribtionBuy = 'yes';
            // $jobseeker->save();

            //DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription purchased successfully!'
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Something went wrong while purchasing the subscription.'
        //     ], 500);
        // }
    }
}
