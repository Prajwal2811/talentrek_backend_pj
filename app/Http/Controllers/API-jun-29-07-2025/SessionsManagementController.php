<?php

namespace App\Http\Controllers\API;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

use App\Models\Api\TrainingExperience;
use App\Models\Api\Mentors;
use App\Models\Api\EducationDetails;
use App\Models\Api\WorkExperience;
use App\Models\Api\TrainingMaterialsDocument;
use App\Models\Api\AdditionalInfo;
use App\Models\Api\BookingSlotUnavailableDate;
use App\Models\Api\BookingSession;
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
        try {  
            // Common base query
            $baseQuery = BookingSession::select(
                'id', 'jobseeker_id', 'user_type', 'user_id',
                'booking_slot_id', 'slot_mode', 'slot_date',
                'zoom_meeting_id', 'zoom_join_url', 'zoom_start_url'
            )->where('user_id', $request->user_id)
            ->where('user_type', $request->type)
            ->where('status', 'confirmed');

            // Clone the base query for each group
            $totalSessions = (clone $baseQuery)->get();
            $upcomingSessions = (clone $baseQuery)->whereDate('slot_date', '>=', Carbon::today())->get();
            $completedSessions = (clone $baseQuery)->whereDate('slot_date', '<', Carbon::today())->get();

            return response()->json([
                'status' => true,
                'message' => 'Session summary for ' . $request->type,
                'data' => [
                    'totalSessions' => $totalSessions->count(),
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
        try {
                // Fetch Trainers personal information
                $relationships = [];
                $type = $request->type;
                if ($type === 'mentor') {
                    $relationships = ['mentors', 'WorkExperience', 'mentorAdditionalInfo','bookingSlot'];
                } elseif ($type === 'assessor') {
                    $relationships = ['assessors', 'AssessorWorkExperience', 'assessorAdditionalInfo','bookingSlot'];
                } elseif ($type === 'coach') {
                    $relationships = ['coaches', 'CoachWorkExperience', 'coachAdditionalInfo','bookingSlot'];
                }

                $upcomingSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'confirmed')
                ->whereDate('slot_date', '>=', Carbon::today())
                ->orderBy('slot_date', 'asc')
                ->get()
                ->map(function ($item) use ($type) {
                    $relationName = $type . 's'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'WorkExperience' : ($type === 'assessor' ? 'AssessorWorkExperience' : 'CoachWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'mentorAdditionalInfo' : ($type === 'assessor' ? 'assessorAdditionalInfo' : 'coachAdditionalInfo');

                    $profilePicture = $type === 'mentor' ? 'mentor_profile_picture' : ($type === 'assessor' ? 'assessor_profile_picture' : 'coach_profile_picture');


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
        try {
            $relationships = [];
            $type = $request->type;
            if ($type === 'mentor') {
                $relationships = ['mentors', 'WorkExperience', 'mentorAdditionalInfo'];
            } elseif ($type === 'assessor') {
                $relationships = ['assessors', 'AssessorWorkExperience', 'assessorAdditionalInfo'];
            } elseif ($type === 'coach') {
                $relationships = ['coaches', 'CoachWorkExperience', 'coachAdditionalInfo'];
            }
            $cancelledSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'cancelled')
                ->orderBy('slot_date', 'asc')
                ->get()->map(function ($item) use ($type) {
                    $relationName = $type . 's'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'WorkExperience' : ($type === 'assessor' ? 'AssessorWorkExperience' : 'CoachWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'mentorAdditionalInfo' : ($type === 'assessor' ? 'assessorAdditionalInfo' : 'coachAdditionalInfo');
                    $profilePicture = $type === 'mentor' ? 'mentor_profile_picture' : ($type === 'assessor' ? 'assessor_profile_picture' : 'coach_profile_picture');


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
                    unset($item->$relationName, $item->$expRelation);
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
        try {
            $relationships = [];
            $type = $request->type;
            if ($type === 'mentor') {
                $relationships = ['mentors', 'WorkExperience', 'mentorAdditionalInfo'];
            } elseif ($type === 'assessor') {
                $relationships = ['assessors', 'AssessorWorkExperience', 'assessorAdditionalInfo'];
            } elseif ($type === 'coach') {
                $relationships = ['coaches', 'CoachWorkExperience', 'coachAdditionalInfo'];
            }
            $confirmedSessions = BookingSession::select('id', 	'jobseeker_id', 	'user_type','user_id', 	'booking_slot_id' ,	'slot_mode' ,	'slot_date','zoom_meeting_id', 	'zoom_join_url', 	'zoom_start_url')->with($relationships)->where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'confirmed')
                ->whereDate('slot_date', '<', Carbon::today())                
                ->orderBy('slot_date', 'asc')
                ->get()->map(function ($item) use ($type) {
                    $relationName = $type . 's'; // mentors, assessors, coaches
                    $expRelation = $type === 'mentor' ? 'WorkExperience' : ($type === 'assessor' ? 'AssessorWorkExperience' : 'CoachWorkExperience');
                    $infoRelation = $type === 'mentor' ? 'mentorAdditionalInfo' : ($type === 'assessor' ? 'assessorAdditionalInfo' : 'coachAdditionalInfo');

                    $profilePicture = $type === 'mentor' ? 'mentor_profile_picture' : ($type === 'assessor' ? 'assessor_profile_picture' : 'coach_profile_picture');

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
                    unset($item->$relationName, $item->$expRelation);
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

}
