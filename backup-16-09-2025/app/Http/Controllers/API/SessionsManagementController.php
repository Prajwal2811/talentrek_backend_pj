<?php

namespace App\Http\Controllers\API;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\TrainingExperience;
use App\Models\Api\Mentors;
use App\Models\Api\EducationDetails;
use App\Models\Api\WorkExperience;
use App\Models\Api\TrainingMaterialsDocument;
use App\Models\Api\AdditionalInfo;
use App\Models\Api\BookingSlotUnavailableDate;
use App\Models\Api\BookingSession;
use App\Models\SubscriptionPlan;
use App\Models\Api\PurchasedSubscription;

use DB;
use Carbon\Carbon;
class SessionsManagementController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function totalBookedSessionsCountsForMCA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer', // assuming user_id should exist in `users` table
            'type'    => 'required|string|in:trainer,mentor,coach,assessor,jobseeker', // allowed roles/types
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {  
            // Common base query
            $baseQuery = BookingSession::select(
                'id', 'jobseeker_id', 'user_type', 'user_id',
                'booking_slot_id', 'slot_mode', 'slot_date',
                'zoom_meeting_id', 'zoom_join_url', 'zoom_start_url'
            )->where('user_id', $request->user_id)
            ->where('user_type', $request->type)
            ->where('status', 'pending');

            // Clone the base query for each group
            $totalSessions = (clone $baseQuery)->whereDate('slot_date', '>=', Carbon::today())->get()->filter(function ($item) {
                    $now = Carbon::now();
                    $sessionDate = Carbon::parse($item->slot_date);

                    // Only check end_time if slot_date is today
                    if ($sessionDate->isToday()) {
                        $slotEndTime = Carbon::parse($item->bookingSlot->end_time);
                        return $slotEndTime->greaterThan($now);
                    }

                    return true; // keep future dates
                });
            $todaysSessions = (clone $baseQuery)->whereDate('slot_date', '=', Carbon::today())->get();
            $completedSessions = (clone $baseQuery)->whereDate('slot_date', '=', Carbon::today())->get()->filter(function ($item) {
                $now = Carbon::now();
                $sessionDate = Carbon::parse($item->slot_date);

                // Only check end_time if slot_date is today
                if ($sessionDate->isToday()) {
                    $slotEndTime = Carbon::parse($item->bookingSlot->end_time);
                    return $slotEndTime->greaterThan($now);
                }

                return true; // keep future dates
            });

            $pendingSessions = (clone $baseQuery)->whereDate('slot_date', '=', Carbon::today())->get()->filter(function ($item) {
                $now = Carbon::now();
                $sessionDate = Carbon::parse($item->slot_date);

                // Only check end_time if slot_date is today
                if ($sessionDate->isToday()) {
                    $slotEndTime = Carbon::parse($item->bookingSlot->end_time);
                    return $slotEndTime->lessThan($now);
                }

                return true; // keep future dates
            });
            //$completedSessions = (clone $baseQuery)->whereDate('slot_date', '=', Carbon::today())->get();
            //$pendingSessions = collect();
            // $completedSessions = collect();
            // $ongoingSessions = collect();

            // foreach ($todaysSessions as $session) {
            //     // Parse slot time
            //     if (!empty($session->slot_mode) && strpos($session->slot_mode, '-') !== false) {
            //         [$startTimeStr, $endTimeStr] = array_map('trim', explode('-', $session->slot_mode));

            //         // Combine date + time
            //         $startDateTime = Carbon::parse($session->slot_date . ' ' . $startTimeStr);
            //         $endDateTime = Carbon::parse($session->slot_date . ' ' . $endTimeStr);

            //         if ($now->lt($startDateTime)) {
            //             $pendingSessions->push($session); // Not started yet
            //         } elseif ($now->gt($endDateTime)) {
            //             $completedSessions->push($session); // Already ended
            //         } else {
            //             $completedSessions->push($session); // In progress
            //         }
            //     } else {
            //         $pendingSessions->push($session); // Fallback: assume pending
            //     }
            // }

            return response()->json([
                'status' => true,
                'message' => 'Session summary for ' . $request->type,
                'data' => [
                    'totalSessions' => $totalSessions->count(),
                    'todaysSessions' => $todaysSessions->count(),
                    'pendingSessions' => $pendingSessions->count(),
                    'completedSessions' => $completedSessions->count(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function upcomingBookedSessionsForMCA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer', // assuming user_id should exist in `users` table
            'type'    => 'required|string|in:trainer,mentor,coach,assessor,jobseeker', // allowed roles/types
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        //try {
                // Fetch Trainers personal information
                $relationships = [];
                $type = $request->type;
                if ($type === 'mentor') {
                    $relationships = ['mentors', 'WorkExperience', 'mentorAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
                } elseif ($type === 'assessor') {
                    $relationships = ['assessors', 'AssessorWorkExperience', 'assessorAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
                } elseif ($type === 'coach') {
                    $relationships = ['coaches', 'coachWorkExperience', 'coachAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
                }

                $upcomingSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'pending')
                ->whereDate('slot_date', '>=', Carbon::today())
                ->orderBy('slot_date', 'asc')
                ->get()
                ->filter(function ($item) {
                    $now = Carbon::now();
                    $sessionDate = Carbon::parse($item->slot_date);

                    // Only check end_time if slot_date is today
                    if ($sessionDate->isToday()) {
                        $slotEndTime = Carbon::parse($item->bookingSlot->end_time);
                        return $slotEndTime->greaterThan($now);
                    }

                    return true; // keep future dates
                })
                ->map(function ($item) use ($type) {
                    $relationName = 'jobseeker'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'WorkExperience' : ($type === 'assessor' ? 'AssessorWorkExperience' : 'coachWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'mentorAdditionalInfo' : ($type === 'assessor' ? 'assessorAdditionalInfo' : 'coachAdditionalInfo');
                    $profilePicture = $type === 'mentor' ? 'mentor_profile_picture' : ($type === 'assessor' ? 'assessor_profile_picture' : 'coach_profile_picture');

                    //$profilePicture = 'profile_picture';
                    //print_r($item->$expRelation);exit;
                     $jobseekerWorkExperience = 'jobseekerWorkExperience';
                    $jobseekerAdditionalInfo = 'jobseekerAdditionalInfo' ;
                    // Get the most recent job_role based on nearest end_to (null means current)
                    $item->recent_job_role  = collect($item->$jobseekerWorkExperience)->reduce(function ($carry, $exp) {
                        //print_r($exp);exit;
                        $start = Carbon::parse($exp->starts_from);

                        $endRaw = strtolower(trim($exp->end_to));
                         $end = ($endRaw === 'work here' || empty($endRaw)) 
                            ? $exp->job_role
                            : $exp->job_role;

                        return $end;
                    });
                    
                    // Get the most recent job_role based on nearest end_to (null means current)
                    // $mostRecentExp = $item->$expRelation->sortByDesc(function ($exp) {
                    //     return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    // })->first();
                    //$item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->userName = $item->$relationName->name ?? null;
                    $image = '' ;
                    foreach($item->$jobseekerAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $item->image = $image ?? null;
                    $item->startTime =  date('h:i A',strtotime($item->bookingSlot->start_time)) ?? null;
                    $item->endTime =  date('h:i A',strtotime($item->bookingSlot->end_time)) ?? null;
                    $item->slotStartEndTime =  date('h:i A',strtotime($item->bookingSlot->start_time)).' - '.date('h:i A',strtotime($item->bookingSlot->end_time)) ?? null;

                    $now = Carbon::now();
                    $sessionDate = Carbon::parse($item->slot_date);
                    $slotStart = Carbon::parse($item->bookingSlot->start_time);
                    $slotEnd = Carbon::parse($item->bookingSlot->end_time);
                    $slotStartMinus10 = $slotStart->copy()->subMinutes(10);
                    $item->joinLink = $sessionDate->isToday() && $now->between($slotStartMinus10 , $slotEnd);

                    unset($item->$relationName, $item->$expRelation,$item->bookingSlot,$item->$infoRelation);
                    return $item;
                });            

            // Return combined response
            return response()->json([
                    'status' => true,
                    'message' => 'Upcoming session list for '.$request->type,
                    'data' => $upcomingSessions
                ]);   

        // } catch (\Exception $e) {
        //     return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
        //         'error' => $e->getMessage()
        //     ]);
        // }
    }

    public function cancelledBookedSessionsForMCA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer', // assuming user_id should exist in `users` table
            'type'    => 'required|string|in:trainer,mentor,coach,assessor,jobseeker', // allowed roles/types
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        try {
            
            $relationships = [];
            $type = $request->type;
            if ($type === 'mentor') {
                $relationships = ['mentors', 'WorkExperience', 'mentorAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
            } elseif ($type === 'assessor') {
                $relationships = ['assessors', 'AssessorWorkExperience', 'assessorAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
            } elseif ($type === 'coach') {
                $relationships = ['coaches', 'coachWorkExperience', 'coachAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
            }
            $cancelledSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'cancelled')
                ->orderBy('slot_date', 'asc')
                ->get()->map(function ($item) use ($type) {
                    $relationName = 'jobseeker'; // mentors, assessors, coaches
                   $expRelation = $type === 'mentor' ? 'WorkExperience' : ($type === 'assessor' ? 'AssessorWorkExperience' : 'coachWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'mentorAdditionalInfo' : ($type === 'assessor' ? 'assessorAdditionalInfo' : 'coachAdditionalInfo');
                    $profilePicture = $type === 'mentor' ? 'mentor_profile_picture' : ($type === 'assessor' ? 'assessor_profile_picture' : 'coach_profile_picture');


                    // Get the most recent job_role based on nearest end_to (null means current)
                     $jobseekerWorkExperience = 'jobseekerWorkExperience';
                    $jobseekerAdditionalInfo = 'jobseekerAdditionalInfo' ;
                    // Get the most recent job_role based on nearest end_to (null means current)
                    $item->recent_job_role  = collect($item->$jobseekerWorkExperience)->reduce(function ($carry, $exp) {
                        //print_r($exp);exit;
                        $start = Carbon::parse($exp->starts_from);

                        $endRaw = strtolower(trim($exp->end_to));
                         $end = ($endRaw === 'work here' || empty($endRaw)) 
                            ? $exp->job_role
                            : $exp->job_role;

                        return $end;
                    });
                    // $mostRecentExp = $item->$expRelation->sortByDesc(function ($exp) {
                    //     return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    // })->first();
                    // $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->userName = $item->$relationName->name ?? null;
                    $image = '' ;
                    foreach($item->$jobseekerAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    //print_r($item->bookingSlot);exit;
                    $item->image = $image ?? null;
                    $item->startTime =  date('h:i A',strtotime($item->bookingSlot->start_time)) ?? null;
                    $item->endTime =  date('h:i A',strtotime($item->bookingSlot->end_time)) ?? null;
                    $item->slotStartEndTime =  date('h:i A',strtotime($item->bookingSlot->start_time)).' - '.date('h:i A',strtotime($item->bookingSlot->end_time)) ?? null;

                    unset($item->$infoRelation);
                    unset($item->$relationName, $item->$expRelation,$item->bookingSlot);
                    return $item;
                });             

            // Return combined response
            return response()->json([
                    'status' => true,
                    'message' => 'Cancelled session list for '.$request->type,
                    'data' => $cancelledSessions
                ]);   

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }
    public function completedBookedSessionsForMCA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer', // assuming user_id should exist in `users` table
            'type'    => 'required|string|in:trainer,mentor,coach,assessor,jobseeker', // allowed roles/types
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        try {
            
            $relationships = [];
            $type = $request->type;
            if ($type === 'mentor') {
                $relationships = ['mentors', 'WorkExperience', 'mentorAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
            } elseif ($type === 'assessor') {
                $relationships = ['assessors', 'AssessorWorkExperience', 'assessorAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
            } elseif ($type === 'coach') {
                $relationships = ['coaches', 'coachWorkExperience', 'coachAdditionalInfo','bookingSlot','jobseekerWorkExperience','jobseekerAdditionalInfo'];
            }
            $confirmedSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'pending')
                ->whereDate('slot_date', '<=', Carbon::today())                
                ->orderBy('slot_date', 'asc')
                ->get()
                ->filter(function ($item) {
                    $now = Carbon::now();
                    $sessionDate = Carbon::parse($item->slot_date);

                    // Only check end_time if slot_date is today
                    if ($sessionDate->isToday()) {
                        $slotEndTime = Carbon::parse($item->bookingSlot->end_time);
                        return $slotEndTime->lessThan($now);
                    }

                    return true; // keep future dates
                })
                ->map(function ($item) use ($type) {
                    $relationName = 'jobseeker'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'WorkExperience' : ($type === 'assessor' ? 'AssessorWorkExperience' : 'coachWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'mentorAdditionalInfo' : ($type === 'assessor' ? 'assessorAdditionalInfo' : 'coachAdditionalInfo');
                    $profilePicture = $type === 'mentor' ? 'mentor_profile_picture' : ($type === 'assessor' ? 'assessor_profile_picture' : 'coach_profile_picture');

                    // Get the most recent job_role based on nearest end_to (null means current)
                     $jobseekerWorkExperience = 'jobseekerWorkExperience';
                     $jobseekerAdditionalInfo = 'jobseekerAdditionalInfo' ;

                    // Get the most recent job_role based on nearest end_to (null means current)
                    $item->recent_job_role  = collect($item->$jobseekerWorkExperience)->reduce(function ($carry, $exp) {
                        //print_r($exp);exit;
                        $start = Carbon::parse($exp->starts_from);

                        $endRaw = strtolower(trim($exp->end_to));
                         $end = ($endRaw === 'work here' || empty($endRaw)) 
                            ? $exp->job_role
                            : $exp->job_role;

                        return $end;
                    });
                    // $mostRecentExp = $item->$expRelation->sortByDesc(function ($exp) {
                    //     return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    // })->first();
                    // $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->userName = $item->$relationName->name ?? null;
                    $image = '' ;
                                       
                    foreach($item->$jobseekerAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'profile_picture'){ 
                            //$profilePicture){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $item->image = $image ?? null;
                    $startTime = optional($item->bookingSlot)->start_time 
                        ? Carbon::parse($item->bookingSlot->start_time)->format('h:i A') 
                        : '00:00:00';

                    $endTime = optional($item->bookingSlot)->end_time 
                        ? Carbon::parse($item->bookingSlot->end_time)->format('h:i A') 
                        : '00:00:00';
                    $item->slotStartEndTime =  $startTime.' - '.$endTime ?? null;
                    unset($item->$infoRelation);
                    unset($item->$relationName, $item->$expRelation,$item->bookingSlot);
                    return $item;
                });             

            // Return combined response
            return response()->json([
                    'status' => true,
                    'message' => 'Completed session list for '.$request->type,
                    'data' => $confirmedSessions
                ]);  

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function subscriptionPlanListForMCAJT($type='mentor',$userId)
    {
        try {
            // ✅ Validate input
            $validTypes = ['mentor', 'assessor', 'trainer', 'coach', 'jobseeker'];
            if (!in_array($type, $validTypes)) {
                return $this->errorResponse('Invalid user type provided.', 400, []);
            }

            if (empty($userId) || !is_numeric($userId)) {
                return $this->errorResponse('Invalid user ID provided.', 400, []);
            }
            
            $SubscriptionPlan = SubscriptionPlan::select('*')->where('user_type',$type)->where('is_active',1)->get();
            if ($SubscriptionPlan->isEmpty()) {
                return $this->errorResponse('No Subscription list found for '.$type.' .', 200,[]);
            }
            
            // ✅ Check if user has an active subscription
           $activeSubscriptions = PurchasedSubscription::where('user_id', $userId)
                ->where('user_type', $type)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('payment_status', 'paid')
                ->get();

            if ($activeSubscriptions->isNotEmpty()) {
                // user has one or more running subscriptions
                $isRunning = true;
            } else {
                $isRunning = false;
            }

            return response()->json([
                'success' => true,
                'isSubscription' => $isRunning,
                'data' => $SubscriptionPlan,
                'message' => 'Subscription list for '.$type.' fetched successfully.'
            ]);        
            
            
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching Subscription list for '.$type.' .', 500,[]);
        }    
    }
}
