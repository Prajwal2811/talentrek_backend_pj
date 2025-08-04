<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Api\CMS;
use App\Models\Api\Assessors;
use App\Models\Api\Mentors;
use App\Models\Api\Coach;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\TrainingBatch;
use App\Models\Api\Trainers;
use App\Models\Api\Review;
use App\Models\Api\JobseekerTrainingMaterialPurchase;
use App\Models\Api\JobseekerBookingSession;
use App\Models\Api\BookingSession;
use App\Models\Api\BookingSlot;
use App\Models\Api\BookingSlotUnavailableDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MyLearningController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function myLearningTrainingListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerTrainingMaterialPurchase::select('id', 'trainer_id', 'jobseeker_id', 'material_id', 'batch_id')
                ->where('jobseeker_id', $jobseekerId)
                ->whereNotNull('batch_id')
                ->with([
                    'batch', // load full batch info
                    'material' => function ($query) {
                        $query->select('id', 'trainer_id', 'training_title', 'training_price', 'training_offer_price', 'thumbnail_file_path as image')
                            ->with(['trainer:id,name', 'latestWorkExperience'])
                            ->withAvg('trainerReviews', 'ratings');
                    }
                ])
                ->get()
                ->map(function ($purchase) use ($today) {
                    $batch = $purchase->batch;

                    // Assign batch_status
                    if ($batch) {
                        $start = Carbon::parse($batch->start_date);
                        $end = Carbon::parse($batch->end_date);

                        if ($today->lt($start)) {
                            $purchase->batch_status = 'upcoming';
                        } elseif ($today->between($start, $end)) {
                            $purchase->batch_status = 'ongoing';
                        } else {
                            $purchase->batch_status = 'completed';
                        }
                    }

                    // Add material details if present
                    if ($purchase->material) {
                        $materials = $purchase->material;
                        $purchase->trainerName = $materials->trainer->name ?? null;
                        $purchase->training_title = $materials->training_title;
                        $purchase->image = $materials->image;
                        $purchase->job_role = $materials->latestWorkExperience->job_role ?? 'N/A';

                        $avg = $materials->trainer_reviews_avg_ratings;
                        $materials->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                        $purchase->average_rating = $materials->average_rating;

                        unset($purchase->material,$purchase->batch); // if you don’t want to expose all material
                    }

                    return $purchase;
                });
            $myTraining = $allPurchases->filter(function ($item) {
                return in_array($item->batch_status, ['ongoing', 'upcoming']);
            })->values();

            $myCompletedTraining = $allPurchases->filter(function ($item) {
                return $item->batch_status === 'completed';
            })->values();

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => 'My learning training Courses list fetched successfully.',
                'data' => [
                    'myTrainingSession' => $myTraining,
                    'myCompletedTrainingSession' => $myCompletedTraining
                ]
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }

    public function myLearningMentorListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerBookingSession::select('*')
            ->with(['mentorLatestWorkExperience', 'mentorAdditionalInfo','mentors','WorkExperience'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','confirmed')
                ->where('user_type','mentor')
                ->get()
                ->map(function ($session) use ($today) {
                    $totalDays = collect($session->WorkExperience)->reduce(function ($carry, $exp) {
                        $start = \Carbon\Carbon::parse($exp->start_from);
                        $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $session->total_experience_days = $totalDays;
                    $session->experiance = round($totalDays / 365, 1);
                    // Extract start and end times from slot_time
                    $timeRange = explode(' - ', $session->slot_time);
                    $startTime = $timeRange[0] ?? '00:00:00';
                    $endTime = $timeRange[1] ?? '00:00:00';

                    // Build datetime objects
                    $slotEnd = Carbon::parse($session->slot_date . ' ' . $endTime);

                    // Determine status
                    $session->session_status = $slotEnd->isPast() ? 'completed' : 'upcoming';
                    $session->session_start_time = $startTime ;
                    $session->session_end_time = $endTime ;
                    $session->session_date = $session->slot_date ;
                    $session->userName = $session->mentors->name ?? '' ;                   
                    $session->designation = $session->mentorLatestWorkExperience->job_role ?? 'N/A' ;
                    $image = '' ;
                    foreach($session->mentorAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'mentor_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    unset($session->mentorAdditionalInfo, $session->mentorLatestWorkExperience, $session->mentors,$session->WorkExperience);

                    return $session;
                });

            $upcomingSessions = $allPurchases->where('session_status', 'upcoming')->values();
            $completedSessions = $allPurchases->where('session_status', 'completed')->values();

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => 'My learning mentor list fetched successfully.',
                'data' => [
                    'myMentorSession' => $upcomingSessions,
                    'myCompletedMentorSession' => $completedSessions
                ]
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }

    public function myLearningAssessorListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerBookingSession::select('*')
            ->with(['assessorLatestWorkExperience', 'assessorAdditionalInfo','assessors','AssessorWorkExperience'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','confirmed')
                ->where('user_type','assessor')
                ->get()
                ->map(function ($session) use ($today) {
                    $totalDays = collect($session->AssessorWorkExperience)->reduce(function ($carry, $exp) {
                        $start = \Carbon\Carbon::parse($exp->start_from);
                        $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $session->total_experience_days = $totalDays;
                    $session->experiance = round($totalDays / 365, 1);

                    // Extract start and end times from slot_time
                    $timeRange = explode(' - ', $session->slot_time);
                    $startTime = $timeRange[0] ?? '00:00:00';
                    $endTime = $timeRange[1] ?? '00:00:00';

                    // Build datetime objects
                    $slotEnd = Carbon::parse($session->slot_date . ' ' . $endTime);

                    // Determine status
                    $session->session_status = $slotEnd->isPast() ? 'completed' : 'upcoming';
                    $session->session_start_time = $startTime ;
                    $session->session_end_time = $endTime ;
                    $session->session_date = $session->slot_date ;
                    $session->userName = $session->assessors->name ?? '' ;                   
                    $session->designation = $session->assessorLatestWorkExperience->job_role ?? 'N/A' ;
                    
                    $image = '' ;
                    foreach($session->assessorAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'assessor_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    unset($session->assessorAdditionalInfo, $session->assessorLatestWorkExperience, $session->assessors,$session->AssessorWorkExperience);

                    return $session;
                });

            $upcomingSessions = $allPurchases->where('session_status', 'upcoming')->values();
            $completedSessions = $allPurchases->where('session_status', 'completed')->values();

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => 'My learning assessor list fetched successfully.',
                'data' => [
                    'myAssessorSession' => $upcomingSessions,
                    'myCompletedAssessorSession' => $completedSessions
                ]
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }

    public function myLearningCoachListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerBookingSession::select('*')
            ->with(['coachLatestWorkExperience', 'coachAdditionalInfo','coaches','coachWorkExperience'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','confirmed')
                ->where('user_type','coach')
                ->get()
                ->map(function ($session) use ($today) {
                    $totalDays = collect($session->coachWorkExperience)->reduce(function ($carry, $exp) {
                        $start = \Carbon\Carbon::parse($exp->start_from);
                        $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $session->total_experience_days = $totalDays;
                    $session->experiance = round($totalDays / 365, 1);

                    // Extract start and end times from slot_time
                    $timeRange = explode(' - ', $session->slot_time);
                    $startTime = $timeRange[0] ?? '00:00:00';
                    $endTime = $timeRange[1] ?? '00:00:00';

                    // Build datetime objects
                    $slotEnd = Carbon::parse($session->slot_date . ' ' . $endTime);

                    // Determine status
                    $session->session_status = $slotEnd->isPast() ? 'completed' : 'upcoming';
                    $session->session_start_time = $startTime ;
                    $session->session_end_time = $endTime ;
                    $session->session_date = $session->slot_date ;
                    $session->userName = $session->coaches->name ?? '' ;                   
                    $session->designation = $session->coachLatestWorkExperience->job_role ?? 'N/A' ;
                    $image = '' ;
                    foreach($session->coachAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'coach_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    unset($session->coachAdditionalInfo, $session->coachLatestWorkExperience, $session->coaches,$session->WorkExperience);

                    return $session;
                });

            $upcomingSessions = $allPurchases->where('session_status', 'upcoming')->values();
            $completedSessions = $allPurchases->where('session_status', 'completed')->values();

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => 'My learning coach list fetched successfully.',
                'data' => [
                    'mycoachSession' => $upcomingSessions,
                    'myCompletedcoachSession' => $completedSessions
                ]
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }

    public function jobSeekerConsultationSession(Request $request)
    {
       try {  
            
            $validator = Validator::make($request->all(), [
                'jobSeekerId'   => 'required|integer',
                'sessionDate'   => 'required|date', // Optional: validate date format
                'roleType'      => 'required|string',
                'userId'        => 'required|integer',
                'trainingMode'  => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(), // ✅ Shows only the first error
                ], 422);
            }

           $sessionDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->sessionDate)->format('Y-m-d');

            $bookingSlots = BookingSlot::select('id','user_type','user_id','slot_mode','start_time',DB::raw("DATE_FORMAT(start_time, '%h:%i %p') as startTime"))
                ->where('user_id', $request->userId)
                ->where('slot_mode',$request->trainingMode)
                ->where('user_type',$request->roleType)
                ->get()
                ->map(function ($slot) use ($sessionDate) {
                    $isUnavailable = BookingSlotUnavailableDate::where('booking_slot_id', $slot->id)
                                        ->whereDate('unavailable_date', $sessionDate)
                                        ->exists();

                    $slot->is_available = !$isUnavailable;
                   



                    return $slot;
                });


            // Return success with data
            return response()->json([
                'success' => true,
                'message' => ucwords($request->roleType).'  '.ucwords($request->trainingMode).' Booking slot list fetched successfully.',
                'data' => $bookingSlots               
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }

    public function jobSeekerBookAConsultationSession(Request $request)
    {
       
       try {
            $validator = Validator::make($request->all(), [
                'jobSeekerId'   => 'required|integer',
                'sessionDate'   => 'required|date',        // Optional: ensure it's a valid date
                'roleType'      => 'required|string',
                'userId'        => 'required|integer',
                'trainingMode'  => 'required|string',
                'slot_id'       => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(), // ✅ Return only the first error
                ], 422);
            }

            $sessionDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->sessionDate)->format('Y-m-d');

            $exists = BookingSession::where('user_id', $request->userId)
                ->where('user_type', $request->roleType)
                ->whereDate('slot_date', $sessionDate)
                ->where('booking_slot_id', $request->slot_id) // adjust as needed
                ->exists();

            if ($exists) {
                return response()->json(['success' => false, 'message' => ucwords($request->roleType).'  '.ucwords($request->trainingMode).' session booking already exists.']);
            }

            // If not exists, insert
            BookingSession::create([
                'jobseeker_id' => $request->jobSeekerId,
                'user_id' => $request->userId,
                'user_type' => $request->roleType,
                'slot_date' => $sessionDate,
                'slot_time' =>  '',
                'booking_slot_id' => $request->slot_id,
                'slot_mode' => $request->trainingMode,
            ]);

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => ucwords($request->roleType).'  '.ucwords($request->trainingMode).' slot booked successfully.'
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }
}
