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
            $totalSessions = (clone $baseQuery)->get();
            $todaysSessions = (clone $baseQuery)->whereDate('slot_date', '=', Carbon::today())->get();
            $upcomingSessions = (clone $baseQuery)->whereDate('slot_date', '=', Carbon::today())->get();
            //$completedSessions = (clone $baseQuery)->whereDate('slot_date', '=', Carbon::today())->get();
            $pendingSessions = collect();
            $completedSessions = collect();
            $ongoingSessions = collect();

            foreach ($todaysSessions as $session) {
                // Parse slot time
                if (!empty($session->slot_mode) && strpos($session->slot_mode, '-') !== false) {
                    [$startTimeStr, $endTimeStr] = array_map('trim', explode('-', $session->slot_mode));

                    // Combine date + time
                    $startDateTime = Carbon::parse($session->slot_date . ' ' . $startTimeStr);
                    $endDateTime = Carbon::parse($session->slot_date . ' ' . $endTimeStr);

                    if ($now->lt($startDateTime)) {
                        $pendingSessions->push($session); // Not started yet
                    } elseif ($now->gt($endDateTime)) {
                        $completedSessions->push($session); // Already ended
                    } else {
                        $ongoingSessions->push($session); // In progress
                    }
                } else {
                    $pendingSessions->push($session); // Fallback: assume pending
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Session summary for ' . $request->type,
                'data' => [
                    'totalSessions' => $totalSessions->count(),
                    'todaysSessions' => $todaysSessions->count(),
                    'pendingSessions' => $upcomingSessions->count(),
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

        try {
                // Fetch Trainers personal information
                $relationships = [];
                $type = $request->type;
                if ($type === 'mentor') {
                    $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
                } elseif ($type === 'assessor') {
                    $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
                } elseif ($type === 'coach') {
                    $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
                }

                $upcomingSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'pending')
                ->whereDate('slot_date', '>=', Carbon::today())
                ->orderBy('slot_date', 'asc')
                ->get()
                ->map(function ($item) use ($type) {
                    $relationName = 'jobseeker'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'jobseekerWorkExperience' : ($type === 'assessor' ? 'jobseekerWorkExperience' : 'jobseekerWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'jobseekerAdditionalInfo' : ($type === 'assessor' ? 'jobseekerAdditionalInfo' : 'jobseekerAdditionalInfo');

                    $profilePicture = 'profile_picture';


                    // Get the most recent job_role based on nearest end_to (null means current)
                    $mostRecentExp = $item->$expRelation->sortByDesc(function ($exp) {
                        return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    })->first();
                    $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->userName = $item->$relationName->name ?? null;
                    $image = '' ;
                    foreach($item->$infoRelation as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == $profilePicture){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $item->image = $image ?? null;
                    $item->startTime =  date('H:i A',strtotime($item->bookingSlot->start_time)) ?? null;
                    $item->endTime =  date('H:i A',strtotime($item->bookingSlot->end_time)) ?? null;
                    $item->slotStartEndTime =  date('H:i A',strtotime($item->bookingSlot->start_time)).' - '.date('H:i A',strtotime($item->bookingSlot->end_time)) ?? null;
                    unset($item->$relationName, $item->$expRelation,$item->bookingSlot,$item->$infoRelation);
                    return $item;
                });            

            // Return combined response
            return response()->json([
                    'status' => true,
                    'message' => 'Upcoming session list for '.$request->type,
                    'data' => $upcomingSessions
                ]);   

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
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
                $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
            } elseif ($type === 'assessor') {
                $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
            } elseif ($type === 'coach') {
                $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
            }
            $cancelledSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'cancelled')
                ->orderBy('slot_date', 'asc')
                ->get()->map(function ($item) use ($type) {
                    $relationName = 'jobseeker'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'jobseekerWorkExperience' : ($type === 'assessor' ? 'jobseekerWorkExperience' : 'jobseekerWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'jobseekerAdditionalInfo' : ($type === 'assessor' ? 'jobseekerAdditionalInfo' : 'jobseekerAdditionalInfo');

                    $profilePicture = 'profile_picture';


                    // Get the most recent job_role based on nearest end_to (null means current)
                    $mostRecentExp = $item->$expRelation->sortByDesc(function ($exp) {
                        return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    })->first();
                    $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->userName = $item->$relationName->name ?? null;
                    $image = '' ;
                    foreach($item->$infoRelation as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == $profilePicture){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $item->image = $image ?? null;
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
                $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
            } elseif ($type === 'assessor') {
                $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
            } elseif ($type === 'coach') {
                $relationships = ['jobseeker', 'jobseekerWorkExperience', 'jobseekerAdditionalInfo','bookingSlot'];
            }
            $confirmedSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'pending')
                ->whereDate('slot_date', '<', Carbon::today())                
                ->orderBy('slot_date', 'asc')
                ->get()->map(function ($item) use ($type) {
                    $relationName = 'jobseeker'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'jobseekerWorkExperience' : ($type === 'assessor' ? 'jobseekerWorkExperience' : 'jobseekerWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'jobseekerAdditionalInfo' : ($type === 'assessor' ? 'jobseekerAdditionalInfo' : 'jobseekerAdditionalInfo');

                    $profilePicture = 'profile_picture';

                    // Get the most recent job_role based on nearest end_to (null means current)
                    $mostRecentExp = $item->$expRelation->sortByDesc(function ($exp) {
                        return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    })->first();
                    $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->userName = $item->$relationName->name ?? null;
                    $image = '' ;
                    foreach($item->$infoRelation as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == $profilePicture){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $item->image = $image ?? null;
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
}
