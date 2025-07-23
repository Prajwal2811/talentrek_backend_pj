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
use Carbon\Carbon;


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
            ->with(['mentorLatestWorkExperience', 'mentorAdditionalInfo'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','confirmed')
                ->where('user_type','mentor')
                ->get()
                ->map(function ($session) use ($today) {
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
                    $session->mentorLatestWorkExperience = $session->mentorLatestWorkExperience->job_role ?? 'N/A' ;
                    $image = '' ;
                    foreach($session->mentorAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'mentor_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    unset($session->mentorAdditionalInfo, $session->mentorLatestWorkExperience);

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
            ->with(['assessorLatestWorkExperience', 'assessorAdditionalInfo'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','confirmed')
                ->where('user_type','assessor')
                ->get()
                ->map(function ($session) use ($today) {
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
                    $session->assessorLatestWorkExperience = $session->assessorLatestWorkExperience->job_role ?? 'N/A' ;
                    $image = '' ;
                    foreach($session->assessorAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'assessor_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    unset($session->assessorAdditionalInfo, $session->assessorLatestWorkExperience);

                    return $session;
                });

            $upcomingSessions = $allPurchases->where('session_status', 'upcoming')->values();
            $completedSessions = $allPurchases->where('session_status', 'completed')->values();

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => 'My learning assessor list fetched successfully.',
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

    public function myLearningCoachListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerBookingSession::select('*')
            ->with(['coachLatestWorkExperience', 'coachAdditionalInfo'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','confirmed')
                ->where('user_type','coach')
                ->get()
                ->map(function ($session) use ($today) {
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
                    $session->coachLatestWorkExperience = $session->coachLatestWorkExperience->job_role ?? 'N/A' ;
                    $image = '' ;
                    foreach($session->coachAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'coach_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    unset($session->coachAdditionalInfo, $session->coachLatestWorkExperience);

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

    public function jobSeekerBookAConsultationSession(Request $request)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerBookingSession::select('*')
            ->with(['coachLatestWorkExperience', 'coachAdditionalInfo'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','confirmed')
                ->where('user_type','coach')
                ->get()
                ->map(function ($session) use ($today) {
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
                    $session->coachLatestWorkExperience = $session->coachLatestWorkExperience->job_role ?? 'N/A' ;
                    $image = '' ;
                    foreach($session->coachAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'coach_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    unset($session->coachAdditionalInfo, $session->coachLatestWorkExperience);

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
}
