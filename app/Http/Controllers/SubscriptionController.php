<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\PurchasedSubscription;
use App\Services\PaymentHelper;
use App\Models\PurchasedSubscriptionPaymentRequest;
use App\Models\PaymentHistory;
use App\Models\Jobseekers;
use App\Models\RecruiterCompany;
use App\Models\Mentors;
use App\Models\Assessors;
use App\Models\Recruiters;
use App\Models\Trainers;
use App\Models\Coach;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class SubscriptionController extends Controller
{
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
            "password"     => "T4#2H#ma5yHv\$G7",
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
            "responseURL"  => $config['subscription_sub_success_url'],
            "errorURL"     => $config['subscription_sub_success_url'],
        ];

        $jsonTrandata = json_encode([$transactionDetails], JSON_UNESCAPED_SLASHES);
        $trandata     = strtoupper(PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']));

        $booking->update(['request_payload' => $jsonTrandata]);

        $payload = [[
            "id"          => $config['tranportal_id'],
            "trandata"    => $trandata,
            "responseURL" => $config['subscription_sub_success_url'],
            "errorURL"    => $config['subscription_sub_success_url']
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

         // Map user type to model
        $modelMap = [
            'jobseeker' => Jobseekers::class,
            'mentor'    => Mentors::class,
            'assessor'  => Assessors::class,
            'coach'     => Coach::class,
            'trainer'   => Trainers::class,
            'recruiter' => Recruiters::class,
        ];

        // Map user type to guard
        $guardMap = [
            'jobseeker' => 'jobseeker',
            'mentor'    => 'mentor',
            'assessor'  => 'assessor',
            'coach'     => 'coach',
            'trainer'   => 'trainer',
            'recruiter' => 'recruiter',
        ];

        $type = $data['udf2'] ?? null;

        if (!$type || !isset($modelMap[$type])) {
            abort(400, 'Invalid user type');
        }

        $model = $modelMap[$type];
        $user  = $model::findOrFail($data['udf1']);

        if ($user) {
            $guard = $guardMap[$type];
            Auth::guard($guard)->login($user); // ✅ correct guard & type from Neoleap payload
        }

        $booking = PurchasedSubscriptionPaymentRequest::where('track_id', $data['trackId'])->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Payment request not found.');
        }

        $booking->update([
            'transaction_id'  => $data['transId'] ?? null,
            'status'          => $data['result'] === 'CAPTURED' ? 'active' : 'pending',
            'payment_status'  => $data['result'] === 'CAPTURED' ? 'success' : 'failed',
            'response_payload'=> json_encode($data),
        ]);

        // Only create subscription if captured
        if ($data['result'] === 'CAPTURED') {
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

            // Calculate end date
            $endDate = $startDate->copy()->addDays($data['udf6']);

            $subscription = PurchasedSubscription::create([
                'user_id'              => $data['udf1'],
                'user_type'            => $data['udf2'],
                'subscription_plan_id' => $data['udf4'],
                'amount_paid'          => $data['amt'],
                'tax'                  => $data['udf7'],
                'amount'               => $data['udf8'],
                'track_id'             => $data['trackId'] ?? null,
                'currency'             => 'SAR',
                'transaction_id'       => $data['transId'] ?? null,
                'payment_status'       => 'paid',
                'response_payload'     => json_encode($data),
                'start_date'           => $startDate,
                'end_date'             => $endDate,
            ]);

            // 🔹 Add entry in payments_history
            PaymentHistory::create([
                'user_type'     => $data['udf2'],       // payer type
                'user_id'       => $data['udf1'],       // payer id
                'receiver_type' => 'talentrek',         // always platform
                'receiver_id'   => null,                // or 1 if you want fixed id
                'payment_for'   => 'subscription',
                'amount_paid'   => $data['amt'],
                'payment_status'=> 'completed',
                'transaction_id'=> $data['transId'] ?? null,
                'track_id'      => $data['trackId'] ?? null,
                'order_id'      => 'ORD-' . $data['udf1'] . '-' . $data['udf4'] . '-' . now()->format('YmdHis'),
                'currency'      => 'SAR',
                'payment_method'=> 'Al Rajhi',
                'paid_at'       => now(),
            ]);
        }


        // 🔹 Update user's subscription flag
        $userModelMap = [
            'jobseeker' => Jobseekers::class,
            'mentor'    => Mentors::class,
            'assessor'  => Assessors::class,
            'coach'     => Coach::class,
            'trainer'   => Trainers::class,
            // 'recruiter' => Recruiters::class, // ❌ handled separately below
        ];

        if ($data['udf2'] === 'recruiter') {
            // Recruiter case → update companies table instead
            RecruiterCompany::where('recruiter_id', $data['udf1'])
                ->update(['isSubscribtionBuy' => 'yes']);
        } elseif (isset($userModelMap[$data['udf2']])) {
            // Other user types
            $userModel = $userModelMap[$data['udf2']];
            $userModel::where('id', $data['udf1'])->update([
                'isSubscribtionBuy' => 'yes'
            ]);
        }


        // Redirect based on type
        $redirectRoutes = [
            'jobseeker' => 'jobseeker.profile',
            'mentor'    => 'mentor.dashboard',
            'assessor'  => 'assessor.dashboard',
            'coach'     => 'coach.dashboard',
            'trainer'   => 'trainer.dashboard',
            'recruiter' => 'recruiter.dashboard',
        ];

        $type = $data['udf2'] ?? null;

        $route = $redirectRoutes[$type];

        return redirect()->route($route)->with('success', 'Subscription purchased successfully!');

    }


    /**
     * Failure callback
     */
    public function failureSubscription(Request $request)
    {
        $config = config('neoleap');
        $responseTrandata = $request->input('trandata');

        if ($responseTrandata) {
            $decrypted = urldecode(PaymentHelper::decryptAES($responseTrandata, $config['secret_key']));
            $data = json_decode($decrypted, true);
            $data = $data[0] ?? [];

            if (!empty($data['trackId'])) {
                $booking = PurchasedSubscriptionPaymentRequest::where('track_id', $data['trackId'])->first();
                if ($booking) {
                    $status = in_array($data['result'], ['NOT CAPTURED', 'DECLINED', 'FAILED', 'CANCELED'])
                        ? 'failed'
                        : 'awaiting_payment';

                    $booking->update([
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
