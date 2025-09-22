<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App;
use App\Models\PurchasedSubscription;
use App\Models\SubscriptionPlan;
use App\Models\Notification;
use App\Models\Trainers;
use App\Models\Coach;
use App\Models\Recruiters;
use App\Models\Mentors;
use App\Models\Jobseekers;
use App\Models\Assessors;
use App\Models\TrainingBatch;
use App\Models\TrainingMaterial;
use App\Models\BookingSession;
use App\Models\BookingSlot;

class CronController extends Controller

{

    public function index()

    {

        return view('lang');

    }

    public function change(Request $request)

    {

        App::setLocale($request->lang);

        session()->put('locale', $request->lang);

        return redirect()->back();

    }

    public function subscriptionExpired()

    {
        $today = date('Y-m-d');

        $userTypes = [
            'trainer'   => Trainers::class,
            'assessor'  => Assessors::class,
            'coach'     => Coach::class,
            'recruiter' => Recruiters::class,
            'jobseeker' => Jobseekers::class,
            'mentor'    => Mentors::class,
        ];

        foreach ($userTypes as $type => $model) {
            $plans = PurchasedSubscription::where('user_type', $type)->get();
            //print_r($plans);
            if ($plans->isNotEmpty()) {
                foreach ($plans as $plan) {
                    $tenDaysBefore = date('Y-m-d', strtotime($plan->end_date . ' -10 days'));

                    if ($today == $tenDaysBefore) {
                        $userDetails = $model::find($plan->user_id);

                        if ($userDetails) {
                            Notification::insert([
                                'sender_id' => $userDetails->id,
                                'sender_type' => ucfirst($type) . ' subscription will expire soon',
                                'receiver_id' => '1',
                                'message' => ucfirst($type) . ' ' . $userDetails->name . ' subscription will expire on ' . date('d-m-Y', strtotime($plan->end_date)) . '.',
                                'is_read_users' => 0,
                                'user_type' => $type
                            ]);
                        }
                    }
                }
            }
        }

    }

    public function batchSchedule()

    {
        
        $batches = TrainingBatch::where('start_date', date('Y-m-d'))->get();
        if ($batches->isNotEmpty()) {
            foreach ($batches as $batch) {
                $material = TrainingMaterial::where([
                    'id' => $batch->training_material_id,
                    'trainer_id' => $batch->trainer_id
                ])->first();
                if ($material && $batch->start_timing) {
                    $startTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' ' . $batch->start_timing));

                    $oneHourBefore = date('Y-m-d H:i:s', strtotime($startTime . ' -1 hour'));

                    $now = date('Y-m-d H:i:s');

                    if ($now >= $oneHourBefore && $now < $startTime) {
                        $userDetails = Trainers::find($batch->trainer_id);
                        $data = [
                            'sender_id' => $userDetails->id,
                            'sender_type' => 'Your batch will start soon',
                            'receiver_id' => '1',
                            'message' => 'Your ' . $material->training_title . ' batch will start soon at ' . date('h:i A', strtotime($startTime)),
                            'is_read_users' => 0,
                            'user_type' => 'trainer'
                        ];

                        Notification::insert($data);
                    }
                }
            }
        }
    }

    public function bookingSlot()

    {
       
        $today = date('Y-m-d');
        $now   = date('Y-m-d H:i:s');

        $userTypes = [
            'mentor'   => Mentors::class,
            'coach'    => Coach::class,
            'assessor' => Assessors::class,
        ];

        foreach ($userTypes as $type => $model) {
            $slots = BookingSession::where([
                'user_type' => $type,
                'slot_date' => $today
            ])->get();

            if ($slots->isNotEmpty()) {
                foreach ($slots as $slot) {
                    $material = BookingSlot::find($slot->booking_slot_id);

                    if ($material && $material->start_time) {
                        $startTime = date('Y-m-d H:i:s', strtotime($today . ' ' . $material->start_time));

                        $thirtyMinutesBefore = date('Y-m-d H:i:s', strtotime($startTime . ' -30 minutes'));

                        if ($now >= $thirtyMinutesBefore && $now < $startTime) {
                            $userDetails = $model::find($slot->user_id);

                            // Notification to mentor/coach/assessor
                            Notification::insert([
                                'sender_id' => $userDetails->id,
                                'sender_type' => 'Your schedule booking slot will start soon',
                                'receiver_id' => '1',
                                'message' => 'Your schedule booking slot will start soon at ' . date('h:i A', strtotime($startTime)),
                                'is_read_users' => 0,
                                'user_type' => $type
                            ]);

                            // Notification to jobseeker
                            Notification::insert([
                                'sender_id' => $userDetails->id,
                                'sender_type' => ucfirst($type) . ' schedule booking slot will start soon',
                                'receiver_id' => '1',
                                'message' => ucfirst($type) . ' schedule booking slot will start soon at ' . date('h:i A', strtotime($startTime)),
                                'is_read_users' => 0,
                                'user_type' => 'jobseeker'
                            ]);
                        }
                    }
                }
            }
        }
    }

}