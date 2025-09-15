<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\PurchasedSubscription;
use App\Services\PaymentHelper;
use App\Models\PurchasedSubscriptionPaymentRequest;
use App\Models\Jobseekers;
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
