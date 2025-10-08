<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentHelper;
use App\Models\PaymentHistory;
use App\Models\SessionBooking;
use App\Models\SessionBookingPaymentRequest;
use App\Models\Mentors;
use App\Models\Assessors;
use App\Models\BookingSlot;
use App\Models\PurchasedSubscription;
use App\Models\BookingSession;
use App\Models\Coach;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobseekers; // make sure to import your model
use App\Services\ZoomService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingZoomLinkToJobseeker;
use App\Mail\BookingZoomLinkToMentor;
use Illuminate\Support\Facades\Http;


class SessionBookingController extends Controller
{
    /**
     * Initiate session booking payment
     */
    public function processBookingPayment(Request $request)
    {
        $jobseekerId = $request->jobseeker_id;

        // Check if jobseeker has a valid subscription
        $subscription = PurchasedSubscription::where('user_id', $jobseekerId)
            ->where('user_type', 'jobseeker')
            ->where('payment_status', 'paid')
            ->orderBy('end_date', 'desc')
            ->first();

        $hasValidSubscription = false;

        if ($subscription) {
            $endDate = \Carbon\Carbon::parse($subscription->end_date);
            $hasValidSubscription = !$endDate->isPast(); // true if not expired
        }

        if (!$hasValidSubscription) {
            return redirect()->back()->with('error', 'You need an active subscription to book a session.');
        }
        // echo "<pre>"; print_r( $request->all() ); die;
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
                . '-' . date('YmdHi')
                . '-' . time();


        // Create payment request record
        $paymentRequest = SessionBookingPaymentRequest::create([ 
            'jobseeker_id' => $request->jobseeker_id, 
            'user_type' => $request->user_type, 
            'user_id' => $request->user_id, 
            'slot_mode' => $request->mode, 
            'slot_date' => date('Y-m-d', strtotime($request->date)),
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

        //  echo "<pre>"; print_r($paymentRequest); die;
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
            "password"     => $config['tranportal_password'],
            "currencyCode" => "682",
            "trackId"      => $paymentRequest->track_id,
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
            "udf13"        => $paymentRequest->slot_date,
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
            CURLOPT_URL => $config['curlopt_url'],
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
        // echo "<pre>"; print_r($data); die;

        if ($result) {
            [$paymentId, $paymentUrl] = explode(":", $result, 2);
            return redirect()->away($paymentUrl . "?PaymentID=" . $paymentId);
        }

        return redirect()->back()->with('error', 'Unable to initiate payment. Please try again.');
    }

  
    public function createZoomMeeting($topic, $startTime)
    {
        $token = getAccessToken();
        if (!$token) {
            return ['error' => 'Failed to fetch access token'];
        }
        $email = env('ZOOM_USER_EMAIL');
        $response = Http::withToken($token)->post("https://api.zoom.us/v2/users/{$email}/meetings", [
            'topic' => $topic,
            'type' => 2,
            'start_time' => $startTime,
            'duration' => 30,
            'timezone' => 'Asia/Kolkata',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
            ],
        ]);

        return $response->json();
    }

    public function successBooking(Request $request)
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

        if ($data['result'] === 'CAPTURED') {
            $paymentRequest = SessionBookingPaymentRequest::where('track_id', $data['trackId'])->first();

            if (!$paymentRequest) {
                return redirect()->back()->with('error', 'Booking not found.');
            }

            $paymentRequest->update([
                'transaction_id'   => $data['transId'] ?? null,
                'payment_status'   => 'success',
                'status'           => 'confirmed',
                'response_payload' => json_encode($data),
            ]);

            $booking = BookingSession::updateOrCreate(
                ['track_id' => $data['trackId']],
                [
                    'jobseeker_id'    => $paymentRequest->jobseeker_id,
                    'user_type'       => $paymentRequest->user_type,
                    'user_id'         => $paymentRequest->user_id,
                    'booking_slot_id' => $paymentRequest->booking_slot_id,
                    'slot_mode'       => $paymentRequest->slot_mode,
                    'slot_date'       => $paymentRequest->slot_date,
                    'slot_time'       => $paymentRequest->slot_time,
                    'coupon_type'     => $paymentRequest->coupon_type,
                    'coupon_code'     => $paymentRequest->coupon_code,
                    'coupon_amount'   => $paymentRequest->coupon_amount ?? 0.00,
                    'order_id'        => 'ORD-' . ($data['ref'] ?? time()),
                    'track_id'        => $paymentRequest->track_id,
                    'transaction_id'  => $data['transId'] ?? $paymentRequest->transaction_id,
                    'response_payload'=> $paymentRequest->response_payload,
                    'slot_amount'     => $paymentRequest->amount,
                    'tax_percentage'  => $paymentRequest->tax_percentage,
                    'taxed_amount'    => $paymentRequest->taxed_amount ?? 0.00,
                    'amount_paid'     => $paymentRequest->total_amount,
                    'payment_status'  => 'success',
                    'updated_at'      => now(),
                ]
            );

            // ✅ If online, create Zoom meeting
            if ($booking->slot_mode === 'online') {
                $zoom = new ZoomService();

                // Safe parsing to avoid double time errors
                $dateOnly = \Carbon\Carbon::parse($booking->slot_date)->format('Y-m-d');
                $startTime = $dateOnly . ' ' . explode(' - ', $booking->slot_time)[0];

                $zoomMeeting = $this->createZoomMeeting("Consultation with #{$booking->jobseeker_id}", $startTime);
                //$zoomMeeting = $zoom->createMeeting("Consultation with #{$booking->jobseeker_id}", $startTime);

                if ($zoomMeeting) {
                    $booking->update([
                        'zoom_start_url' => $zoomMeeting['start_url'] ?? null,
                        'zoom_join_url'  => $zoomMeeting['join_url'] ?? null,
                        'zoom_meeting_id'=> $zoomMeeting['id'] ?? null,
                    ]);

                    // Send email to Jobseeker
                    //Mail::to($booking->jobseeker->email)->send(new BookingZoomLinkToJobseeker($booking));

                    $emails = $booking->jobseeker->email;

                    Mail::raw("Join Zoom Meeting: " . $zoomMeeting['join_url'], function($message) use ($emails) {
                        $message->to($emails)
                                ->subject('Zoom Meeting Invitation');
                    });
                    // Send email to Mentor/Coach
                    if($paymentRequest->user_type == 'mentor'){
                        $mentor = Mentors::find($booking->user_id); // adjust based on user_type
                        
                        if ($mentor) {
                            $mentorEmail = $mentor->email;
                            //Mail::to($mentor->email)->send(new BookingZoomLinkToMentor($booking));
                            Mail::raw("Join Zoom Meeting: " . $zoomMeeting['start_url'], function($message) use ($mentorEmail) {
                                $message->to($mentorEmail)
                                        ->subject('Zoom Meeting Invitation');
                            });
                        }
                    }
                    if($paymentRequest->user_type == 'coach'){
                        $coach = Coach::find($booking->user_id); // adjust based on user_type
                        if ($coach) {
                            //Mail::to($mentor->email)->send(new BookingZoomLinkToMentor($booking));
                            $coachEmail = $coach->email;
                            //Mail::to($mentor->email)->send(new BookingZoomLinkToMentor($booking));
                            Mail::raw("Join Zoom Meeting: " . $zoomMeeting['start_url'], function($message) use ($coachEmail) {
                                $message->to($coachEmail)
                                        ->subject('Zoom Meeting Invitation');
                            });
                        }
                    }
                    if($paymentRequest->user_type == 'assessor'){
                        $assessor = Assessors::find($booking->user_id); // adjust based on user_type
                        if ($assessor) {
                            //Mail::to($mentor->email)->send(new BookingZoomLinkToMentor($booking));
                            $assessorEmail = $assessor->email;
                            //Mail::to($mentor->email)->send(new BookingZoomLinkToMentor($booking));
                            Mail::raw("Join Zoom Meeting: " . $zoomMeeting['start_url'], function($message) use ($assessorEmail) {
                                $message->to($assessorEmail)
                                        ->subject('Zoom Meeting Invitation');
                            });
                        }
                    }

                } else {
                    Log::error('Zoom creation failed for booking', [
                        'jobseeker_id' => $booking->jobseeker_id,
                        'user_id'      => $booking->user_id,
                        'slot_time'    => $booking->slot_time,
                    ]);
                    return redirect()->back()->with('error', 'Zoom meeting creation failed. Please try again later.');
                }
            } elseif ($booking->slot_mode === 'offline') {
                // Get mentor/coach address for offline
                $mentor = Mentors::find($booking->user_id); // adjust if user_type = mentor/coach
                $mentorAddress = $mentor?->address ?? 'Address not available';
                $booking->update(['offline_address' => $mentorAddress]);
            }

            // 🔑 Log the jobseeker in
            $jobseeker = Jobseekers::find($paymentRequest->jobseeker_id);
            if ($jobseeker) {
                Auth::guard('jobseeker')->login($jobseeker); 
            }

            // 💰 Save PaymentHistory
            PaymentHistory::create([
                'user_type'      => 'jobseeker',
                'user_id'        => $paymentRequest->jobseeker_id,
                'receiver_type'  => 'mentor', // or 'trainer' based on your logic
                'receiver_id'    => $paymentRequest->user_id,
                'payment_for'    => 'booking_slot',
                'amount_paid'    => $paymentRequest->total_amount,
                'tax'            => $paymentRequest->taxed_amount ?? 0,
                'applied_coupon' => $paymentRequest->coupon_code,
                'payment_status' => 'completed',
                'transaction_id' => $paymentRequest->transaction_id,
                'track_id'       => $paymentRequest->track_id,
                'order_id'       => 'ORD-' . $paymentRequest->jobseeker_id . '-' . $paymentRequest->booking_slot_id . '-' . now()->format('YmdHi'),
                'currency'       => 'SAR',
                'payment_method' => $paymentRequest->payment_gateway,
                'paid_at'        => now(),
            ]);
        }

        return redirect()->route('jobseeker.profile')
                        ->with('success', 'Session booked successfully!');
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
