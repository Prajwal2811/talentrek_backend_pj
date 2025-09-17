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
use App\Models\Api\AssessmentJobseekerDataStatus;
use App\Models\Api\Trainers;
use App\Models\Api\Review;
use App\Models\Api\JobseekerTrainingMaterialPurchase;
use App\Models\Api\JobseekerBookingSession;
use App\Models\Api\BookingSession;
use App\Models\Api\BookingSlot;
use App\Models\Api\BookingSlotUnavailableDate;
use App\Models\Setting;
use App\Models\Payment\JobseekerSessionBookingPaymentRequest;
use Carbon\Carbon;
use App\Services\PaymentHelper;
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
        //try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerTrainingMaterialPurchase::select('id', 'trainer_id', 'jobseeker_id', 'material_id', 'batch_id')
                ->where('jobseeker_id', $jobseekerId)
                //->whereNotNull('batch_id')
                ->with([
                    'batch', // load full batch info
                    'material' => function ($query) {
                        $query->select('id', 'trainer_id', 'training_title', 'training_price', 'training_offer_price', 'thumbnail_file_path as image','training_type')
                            ->with(['trainer:id,name', 'latestWorkExperience'])
                            ->withAvg('trainerReviews', 'ratings');
                    }
                ])                
                ->get()
                ->map(function ($purchase) use ($today,$jobseekerId) {
                    $batch = $purchase->batch;

                    // Assign batch_status
                    if ($batch) {
                        $start = Carbon::parse($batch->start_date);
                        $end = Carbon::parse($batch->end_date);

                        $endDateTime = Carbon::parse($batch->end_date . ' ' . $batch->end_timing);
                        if ($today->lt($start)) {
                            //echo "if";
                            $purchase->batch_status = 'upcoming';
                        } elseif (Carbon::now()->gte($endDateTime)) {
                            //echo "iffff";
                            $hasSubmitted = AssessmentJobseekerDataStatus::where('material_id', $purchase->material_id)
                            ->where('jobseeker_id', $jobseekerId)
                            ->where('submitted', 1)
                            ->exists();
                            $purchase->batch_status = $hasSubmitted ? 'completed' : 'ongoing';
                        } elseif ($today->between($start, $end, false)) {
                            //echo "elseif";
                            $purchase->batch_status = 'ongoing';
                        }
                    }
                    else{
                        $purchase->batch_status = 'upcoming';
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

        // } catch (\Exception $e) {
        //     // Log error if needed: Log::error($e);
        //     return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        // }
    }

    public function myLearningMentorListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerBookingSession::select('*')
                ->with(['mentorLatestWorkExperience', 'mentorAdditionalInfo','mentors','WorkExperience','bookingSlot'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','pending')
                ->where('user_type','mentor')
                ->get()
                ->map(function ($session) use ($today) {
                    $totalDays = collect($session->WorkExperience)->reduce(function ($carry, $exp) {
                        $start = Carbon::parse($exp->starts_from);

                        $endRaw = strtolower(trim($exp->end_to));
                        $end = ($endRaw === 'work here' || empty($endRaw)) 
                            ? now() 
                            : Carbon::parse($endRaw);

                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $session->total_experience_days = $totalDays;
                    $session->experiance = round($totalDays / 365, 1);
                    // Extract start and end times from slot_time
                    $timeRange = explode(' - ', $session->slot_time);
                    // $startTime = $timeRange[0] ?? '00:00:00';
                    // $endTime = $timeRange[1] ?? '00:00:00';
                    $startTime = optional($session->bookingSlot)->start_time 
                        ? Carbon::parse($session->bookingSlot->start_time)->format('H:i') 
                        : '00:00:00';

                    $endTime = optional($session->bookingSlot)->end_time 
                        ? Carbon::parse($session->bookingSlot->end_time)->format('H:i') 
                        : '00:00:00';

                    $slotEnd = $endTime 
                        ? Carbon::parse($session->slot_date . ' ' . $endTime)
                        : null;


                    // Determine status
                    $session->session_status = $slotEnd->isPast() ? 'completed' : 'upcoming';
                    $session->session_start_time = $startTime ;
                    $session->session_end_time = $endTime ;
                    $session->session_date = $session->slot_date ;
                    $session->userName = $session->mentors->name ?? '' ;                   
                    $session->address = $session->mentors->address ?? '' ;
                    $mostRecentExp = $session->WorkExperience
                    ->sortByDesc(function ($exp) {
                        $endTo = strtolower(trim($exp->end_to));
                        return $endTo === 'work here'
                            ? Carbon::now()->timestamp
                            : Carbon::parse($exp->end_to)->timestamp;
                    })
                    ->first();
                    $session->designation = $mostRecentExp ? $mostRecentExp->job_role : 'N/A';                    
                    
                    $image = '' ;
                    foreach($session->mentorAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'mentor_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    $session->sessionStartTime = optional($session->bookingSlot)->start_time 
                        ? Carbon::parse($session->bookingSlot->start_time)->format('h:i A') 
                        : '00:00:00';
                    $session->sessionEndTime   = optional($session->bookingSlot)->end_time 
                        ? Carbon::parse($session->bookingSlot->end_time)->format('h:i A') 
                        : '00:00:00';  // 19:35

                    $now = Carbon::now();
                    $sessionDate = Carbon::parse($session->slot_date);
                    $slotStart = Carbon::parse($session->sessionStartTime);
                    $slotEnd = Carbon::parse($session->sessionEndTime);
                    $slotStartMinus10 = $slotStart->copy()->subMinutes(10);
                    $session->joinLink = $sessionDate->isToday() && $now->between($slotStartMinus10 , $slotEnd);
                    unset($session->mentorAdditionalInfo, $session->mentorLatestWorkExperience, $session->mentors,$session->WorkExperience,$session->bookingSlot);

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
            ->with(['assessorLatestWorkExperience', 'assessorAdditionalInfo','assessors','AssessorWorkExperience','bookingSlot'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','pending')
                ->where('user_type','assessor')
                ->get()
                ->map(function ($session) use ($today) {
                    $totalDays = collect($session->AssessorWorkExperience)->reduce(function ($carry, $exp) {
                        $start = Carbon::parse($exp->starts_from);

                        $endRaw = strtolower(trim($exp->end_to));
                        $end = ($endRaw === 'work here' || empty($endRaw)) 
                            ? now() 
                            : Carbon::parse($endRaw);

                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $session->total_experience_days = $totalDays;
                    $years = floor($totalDays / 365);
                    $months = floor(($totalDays % 365) / 30);
                    $session->experiance =  $years.'.'.$months ;
                    //$session->experiance = round($totalDays / 365, 1);

                    // Extract start and end times from slot_time
                    $timeRange = explode(' - ', $session->slot_time);
                    // $startTime = $timeRange[0] ?? '00:00:00';
                    // $endTime = $timeRange[1] ?? '00:00:00';
                    // $startTime = Carbon::parse($session->bookingSlot->start_time)->format('H:i A') ?? '00:00:00';
                    // $endTime = Carbon::parse($session->bookingSlot->end_time)->format('H:i A') ?? '00:00:00';
                    $startTime = optional($session->bookingSlot)->start_time 
                        ? Carbon::parse($session->bookingSlot->start_time)->format('H:i') 
                        : '00:00:00';

                    $endTime = optional($session->bookingSlot)->end_time 
                        ? Carbon::parse($session->bookingSlot->end_time)->format('H:i') 
                        : '00:00:00';

                    // Build datetime objects
                    $slotEnd = Carbon::parse($session->slot_date . ' ' . $endTime);

                    // Determine status
                    $session->session_status = $slotEnd->isPast() ? 'completed' : 'upcoming';
                    $session->session_start_time = $startTime ;
                    $session->session_end_time = $endTime ;
                    $session->session_date = $session->slot_date ;
                    $session->userName = $session->assessors->name ?? '' ; 
                    $session->address = $session->assessors->address ?? '' ;
                    $mostRecentExp = $session->AssessorWorkExperience
                    ->sortByDesc(function ($exp) {
                        $endTo = strtolower(trim($exp->end_to));
                        return $endTo === 'work here'
                            ? Carbon::now()->timestamp
                            : Carbon::parse($exp->end_to)->timestamp;
                    })
                    ->first();
                    $session->designation = $mostRecentExp ? $mostRecentExp->job_role : 'N/A'; //$session->assessorLatestWorkExperience->job_role ?? 'N/A' ;
                    
                    $image = '' ;
                    foreach($session->assessorAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'assessor_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    
                    $session->sessionStartTime  = optional($session->bookingSlot)->start_time 
                        ? Carbon::parse($session->bookingSlot->start_time)->format('h:i A') 
                        : '00:00:00';

                    $session->sessionEndTime = optional($session->bookingSlot)->end_time 
                        ? Carbon::parse($session->bookingSlot->end_time)->format('h:i A') 
                        : '00:00:00';
                    $now = Carbon::now();
                    $sessionDate = Carbon::parse($session->slot_date);
                    $slotStart = Carbon::parse($session->sessionStartTime);
                    $slotEnd = Carbon::parse($session->sessionEndTime);
                    $slotStartMinus10 = $slotStart->copy()->subMinutes(10);
                    $session->joinLink = $sessionDate->isToday() && $now->between($slotStartMinus10 , $slotEnd);
                    unset($session->assessorAdditionalInfo, $session->assessorLatestWorkExperience, $session->assessors,$session->AssessorWorkExperience,$session->bookingSlot);

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
            ->with(['coachLatestWorkExperience', 'coachAdditionalInfo','coaches','coachWorkExperience','bookingSlot'])
                ->where('jobseeker_id', $jobseekerId)
                ->where('status','pending')
                ->where('user_type','coach')
                ->get()
                ->map(function ($session) use ($today) {
                    $totalDays = collect($session->coachWorkExperience)->reduce(function ($carry, $exp) {
                        $start = Carbon::parse($exp->starts_from);

                        $endRaw = strtolower(trim($exp->end_to));
                        $end = ($endRaw === 'work here' || empty($endRaw)) 
                            ? now() 
                            : Carbon::parse($endRaw);

                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $session->total_experience_days = $totalDays;
                    $session->experiance = round($totalDays / 365, 1);

                    // Extract start and end times from slot_time
                    $timeRange = explode(' - ', $session->slot_time);
                    // $startTime = $timeRange[0] ?? '00:00:00';
                    // $endTime = $timeRange[1] ?? '00:00:00';
                    $startTime = optional($session->bookingSlot)->start_time 
                        ? Carbon::parse($session->bookingSlot->start_time)->format('H:i') 
                        : '00:00:00';

                    $endTime = optional($session->bookingSlot)->end_time 
                        ? Carbon::parse($session->bookingSlot->end_time)->format('H:i') 
                        : '00:00:00';
                    

                    // Build datetime objects
                    $slotEnd = Carbon::parse($session->slot_date . ' ' . $endTime);

                    // Determine status
                    $session->session_status = $slotEnd->isPast() ? 'completed' : 'upcoming';
                    $session->session_start_time = $startTime ;
                    $session->session_end_time = $endTime ;
                    $session->session_date = $session->slot_date ;
                    $session->userName = $session->coaches->name ?? '' ; 
                    $session->address = $session->coaches->address ?? '' ;                   
                    $mostRecentExp = $session->coachWorkExperience
                    ->sortByDesc(function ($exp) {
                        $endTo = strtolower(trim($exp->end_to));
                        return $endTo === 'work here'
                            ? Carbon::now()->timestamp
                            : Carbon::parse($exp->end_to)->timestamp;
                    })
                    ->first();
                    $session->designation = $mostRecentExp ? $mostRecentExp->job_role : 'N/A'; 
                   
                    $image = '' ;
                    foreach($session->coachAdditionalInfo as $jobseekerAdditionalInfos){
                        if($jobseekerAdditionalInfos->doc_type == 'coach_profile_picture'){
                            $image = $jobseekerAdditionalInfos->document_path ;
                        }                
                    }
                    $session->image = $image ;
                    $session->sessionStartTime  = optional($session->bookingSlot)->start_time 
                        ? Carbon::parse($session->bookingSlot->start_time)->format('h:i A') 
                        : '00:00:00';

                    $session->sessionEndTime = optional($session->bookingSlot)->end_time 
                        ? Carbon::parse($session->bookingSlot->end_time)->format('h:i A') 
                        : '00:00:00';
                    $now = Carbon::now();
                    $sessionDate = Carbon::parse($session->slot_date);
                    $slotStart = Carbon::parse($session->sessionStartTime);
                    $slotEnd = Carbon::parse($session->sessionEndTime);
                    $slotStartMinus10 = $slotStart->copy()->subMinutes(10);
                    $session->joinLink = $sessionDate->isToday() && $now->between($slotStartMinus10 , $slotEnd);
                    unset($session->coachAdditionalInfo, $session->coachLatestWorkExperience, $session->coaches,$session->coachWorkExperience,$session->bookingSlot);

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
        $data = $request->all();

        $rules = [
            'jobSeekerId'   => 'required|integer',
            'sessionDate'   => 'required', // Optional: validate date format
            'roleType'      => 'required|string',
            'userId'        => 'required|integer',
            'trainingMode'  => 'required|string',
        ];
        $rules["sessionDate"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);                    
                    $today = Carbon::today();

                    if ($date < $today) {
                        $fail("The session date must not be in the past.");
                    }
                } catch (\Exception $e) {
                    $fail("The session date must be a valid date in d/m/Y format.");
                }
            },
        ]; 
        $validator = Validator::make($data, $rules);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }   
       try {  
            
            // $validator = Validator::make($request->all(), [
            //     'jobSeekerId'   => 'required|integer',
            //     'sessionDate'   => 'required|date', // Optional: validate date format
            //     'roleType'      => 'required|string',
            //     'userId'        => 'required|integer',
            //     'trainingMode'  => 'required|string',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => $validator->errors()->first(), // ✅ Shows only the first error
            //     ], 422);
            // }

           $sessionDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->sessionDate)->format('Y-m-d');

            $bookingSlots = BookingSlot::select('id','user_type','user_id','slot_mode','start_time','end_time',DB::raw("DATE_FORMAT(start_time, '%h:%i %p') as startTime"))
                ->where('user_id', $request->userId)
                ->where('slot_mode',$request->trainingMode)
                ->where('user_type',$request->roleType)
                ->get()
                ->map(function ($slot) use ($sessionDate) {

                    $now = Carbon::now();
                    $sessionDateCarbon = Carbon::parse($sessionDate);
                    
                    $isUnavailable = BookingSlotUnavailableDate::where('booking_slot_id', $slot->id)
                                        ->whereDate('unavailable_date', $sessionDate)
                                        ->exists();
                    $isBooked = DB::table('jobseeker_saved_booking_session')
                        ->where('booking_slot_id', $slot->id)
                        ->where('slot_date', $sessionDate)
                        ->where('status', 'pending')
                        ->exists();

                    // 3. Check if time has already passed (only for today)
                    $isTimePassed = false;
                    if ($sessionDateCarbon->isToday()) {
                        $slotStartTime = Carbon::parse($slot->start_time)->format('H:i:s');
                        $currentTime   = $now->format('H:i:s');
                        $isTimePassed  = $slotStartTime < $currentTime;
                    }
                    
                    if($isBooked){
                        $is_available = false ;
                        $availabilityStatus = 'Booked';
                    } elseif($isUnavailable){
                        $is_available = false ;
                        $availabilityStatus = 'Unavailable';
                    } elseif ($isTimePassed) {
                        $is_available = false;
                        $availabilityStatus = 'Time Passed';
                    } 
                    else{
                        $is_available = true ; 
                        $availabilityStatus = '';
                    }
                    $slot->is_available = $is_available;
                    $slot->availabilityStatus = $availabilityStatus;

                    return $slot;
            });
            $slotPrice = $this->getUserSlotPrice($request->userId,$request->roleType);
            $slotTax = $this->getSlotPercentage($request->roleType) ;
            $slotTotalAmount = $slotPrice + ($slotPrice * $slotTax / 100); 
            // Return success with data
            return response()->json([
                'success' => true,
                'message' => ucwords($request->roleType).'  '.ucwords($request->trainingMode).' Booking slot list fetched successfully.',
                'perSlotPrice' => $slotPrice, 
                'slotTaxPercentage' => $slotTax, 
                'slotTotalAmount' => $slotTotalAmount, 
                'data' => $bookingSlots               
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }

    public function jobSeekerBookAConsultationSession(Request $request)
    {
        $data = $request->all();

        $rules = [
            'jobSeekerId'   => 'required|integer',
            'sessionDate'   => 'required|date',        // Optional: ensure it's a valid date
            'roleType'      => 'required|string',
            'userId'        => 'required|integer',
            'trainingMode'  => 'required|string',
            'slot_id'       => 'required|integer',
        ];
        $rules["sessionDate"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);                    
                    $today = Carbon::today();

                    if ($date < $today) {
                        $fail("The session date must not be in the past.");
                    }
                } catch (\Exception $e) {
                    $fail("The session date must be a valid date in d/m/Y format.");
                }
            },
        ]; 
        $validator = Validator::make($data, $rules);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }
       //try {
            // $validator = Validator::make($request->all(), [
            //     'jobSeekerId'   => 'required|integer',
            //     'sessionDate'   => 'required|date',        // Optional: ensure it's a valid date
            //     'roleType'      => 'required|string',
            //     'userId'        => 'required|integer',
            //     'trainingMode'  => 'required|string',
            //     'slot_id'       => 'required|integer',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => $validator->errors()->first(), // ✅ Return only the first error
            //     ], 422);
            // }

            $sessionDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->sessionDate)->format('Y-m-d');

            $exists = BookingSession::where('user_id', $request->userId)
                ->where('user_type', $request->roleType)
                ->whereDate('slot_date', $sessionDate)
                ->where('booking_slot_id', $request->slot_id) // adjust as needed
                ->exists();

            if ($exists) {
                return response()->json(['success' => false, 'message' => ucwords($request->roleType).'  '.ucwords($request->trainingMode).' session booking already exists.']);
            }            
            
            $slotPrice = $this->getUserSlotPrice($request->userId,$request->roleType);
            $slotTax = $this->getSlotPercentage($request->roleType) ;
            $slotTotalAmount = $slotPrice + ($slotPrice * $slotTax / 100); 

            $referenceNo0 = 'TRK-' . strtoupper(substr($request->roleType, 0, 3)) . '-' .$request->userId . '-' .
               $request->slot_id . '-' . 
               $request->jobSeekerId . '-' . 
               date('YmdHi', strtotime($sessionDate));
            $booking = JobseekerSessionBookingPaymentRequest::create([
                'jobseeker_id' => $request->jobSeekerId,
                'user_type' => $request->roleType,
                'user_id' => $request->userId,
                'booking_slot_id' => $request->slot_id,
                'slot_mode' => $request->trainingMode,
                'slot_date' => $sessionDate,
                'slot_time' => '',
                'track_id' => $referenceNo0,
                'status' => 'awaiting_payment',
                'reserved_until' => now()->addDays(5), // hold slot for 15 min
                'amount' => $slotPrice,
                'tax' => $slotTax,
                'total_amount' => $slotTotalAmount,
                'currency' => 'SAR',
                'payment_gateway' => 'Al-Rajhi',
            ]);

            $trackId = strtoupper(substr($request->roleType, 0, 3)). '-' . $booking->id . '-' .$request->userId . '-' .
               $request->slot_id . '-' . 
               $request->jobSeekerId . '-' . 
               date('YmdHi', strtotime($sessionDate));
            $referenceNo =  "TRK-" .  $trackId ;

            $booking->update(['track_id' => $referenceNo]);
            $config = config('neoleap');
            $transactionDetails = [
                "id"          => $config['tranportal_id'], // Your Merchant ID from Al Rajhi
                "amt"         => number_format($slotTotalAmount, 2, '.', ''),          // Transaction amount
                "action"      => "1",               // 1 = Purchase, 4 = Authorization
                "password"    => "T4#2H#ma5yHv\$G7",
                "currencyCode"=> "682",             // ISO numeric code (682 = SAR)
                "trackId"     => "TRK-" .  $trackId ,          // Unique tracking ID
                "udf1"        => $request->jobSeekerId,     // Jobseeker Id
                "udf2"        => $request->roleType,        // Mentor/Coach/Assessor
                "udf3"        => $request->userId,          // Mentor/Coach/Assessor Id
                "udf4"        => $request->slot_id,         // Booking slot id 
                "udf5"        => $request->trainingMode,    // Online/Classroom
                "udf6"        => $sessionDate,              // Session booking Date
                "udf7"        => $slotTax,              // Mentor Session Tax
                "udf8"        => $slotPrice,              // Mentor session Slot Price
                "langid"      => "en",                      // change to ar when goes live for arabic default
                "responseURL" => $config['success_booking_session_url'],
                "errorURL"    => $config['success_booking_session_url']
            ];
            $payload = [$transactionDetails];

            $jsonTrandata = json_encode($payload, JSON_UNESCAPED_SLASHES);
        
            $trandata = PaymentHelper::encryptAES($jsonTrandata, $config['secret_key']);
            $trandatas = strtoupper($trandata) ;
            $booking->update(['request_payload' => $jsonTrandata]);
            $payload = [[
                "id"          => $config['tranportal_id'],
                "trandata"    => $trandatas, // ✅ variable here
                "responseURL" => $config['success_booking_session_url'],
                "errorURL"    => $config['success_booking_session_url']
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
                'message' => ucwords($request->roleType).'  '.ucwords($request->trainingMode).' slot booked successfully.'
            ]);

        // } catch (\Exception $e) {
        //     // Log error if needed: Log::error($e);
        //     return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        // }
    }

    

    public function sessionDetailByIdForMCA($id,$sessionId,$type)
    {
        //try {
            $relationships = [];
            //$type = $request->type;
            if ($type === 'mentor') {
                $relationships = ['mentors', 'WorkExperience', 'mentorAdditionalInfo','bookingSlot'];
            } elseif ($type === 'assessor') {
                $relationships = ['assessors', 'AssessorWorkExperience', 'assessorAdditionalInfo','bookingSlot'];
            } elseif ($type === 'coach') {
                $relationships = ['coaches', 'coachWorkExperience', 'coachAdditionalInfo','bookingSlot'];
            }
            // Fetch mentor with all required relationships
            $MentorsDetails = Mentors::select('*')
                ->with(['mentorReviews', 'WorkExperience', 'mentorEducations','additionalInfo'])
                ->withAvg('mentorReviews', 'ratings')
                ->where('id', $id)
                ->first();
            if (!$MentorsDetails) {
                return $this->errorResponse( 'Mentor not found.', 404,[]);
            }
            // Calculate total experience
            $totalDays = collect($MentorsDetails->WorkExperience)->reduce(function ($carry, $exp) {
                $start = Carbon::parse($exp->starts_from);

                $endRaw = strtolower(trim($exp->end_to));
                $end = ($endRaw === 'work here' || empty($endRaw)) 
                    ? now() 
                    : Carbon::parse($endRaw);

                return $carry + $start->diffInDays($end);
            }, 0);
            $MentorsDetails->total_experience_days = $totalDays;
            $years = floor($totalDays / 365);
            $months = floor(($totalDays % 365) / 30);
            $MentorsDetails->total_experience_years =  $years.'.'.$months ;
            
            // Set average rating and clean raw data
            $avg = $MentorsDetails->mentor_reviews_avg_ratings;
            $MentorsDetails->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            // Get the most recent job_role based on nearest end_to (null means current)
                $mostRecentExp = $MentorsDetails->WorkExperience
                ->sortByDesc(function ($exp) {
                    $endTo = strtolower(trim($exp->end_to));
                    return $endTo === 'work here'
                        ? Carbon::now()->timestamp
                        : Carbon::parse($exp->end_to)->timestamp;
                })
                ->first();
                $MentorsDetails->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                
                $MentorsDetails->image = $MentorsDetails->additionalInfo->document_path ?? null;

              $booking = JobseekerBookingSession::where('id', $sessionId)
                    ->with('bookingSlot')
                    ->first();
                // $MentorsDetails->booking = $booking;
                if ($booking) {
                    // If postponed date exists, use that, otherwise use slot_date
                    $slotDate = $booking->slot_date;
                    

                    // Related booking slot times
                    $startTime = $booking->bookingSlot->start_time ?? null;
                    $endTime   = $booking->bookingSlot->end_time ?? null;

                    // Default joinLink flag
                    $joinLink = false;

                    if ($slotDate && $startTime && $endTime) {
                        $today = Carbon::today()->toDateString();
                        $now   = Carbon::now()->format('H:i:s');

                        if ($slotDate == $today && $now >= $startTime && $now <= $endTime) {
                            $joinLink = true;
                        }
                    }

                    // Append to response
                   $MentorsDetails->joinLink = $joinLink;
                }
                
                unset($MentorsDetails->additionalInfo);
                unset(
                    $MentorsDetails->mentorReviews,
                    $MentorsDetails->mentorEducations,
                    $MentorsDetails->mentor_reviews_avg_ratings,
                    $MentorsDetails->WorkExperience // if not needed on frontend
                );
            return $this->successResponse($MentorsDetails,'Mentor details with review percentage fetched successfully.');
        // } catch (\Exception $e) {
        //     // Log::error('Mentor detail fetch failed: ' . $e->getMessage());
        //     return $this->errorResponse( 'An error occurred while fetching mentor details.', 500,[]);
        // }
    }

    private function getUserSlotPrice($id,$type)
    {
        if ($type == 'mentor') 
        {
            $MentorsDetails = Mentors::select('per_slot_price')->where('id', $id)->first();
            return $MentorsDetails->per_slot_price ;
        } 
        elseif ($type == 'coach') 
        {
            $MentorsDetails = Coach::select('per_slot_price')->where('id', $id)->first();
            return $MentorsDetails->per_slot_price ;
        }
        elseif ($type == 'assessor') 
        {
            $MentorsDetails = Assessors::select('per_slot_price')->where('id', $id)->first();
            return $MentorsDetails->per_slot_price ;
        }
       
        return 1 ;
    }

    private function getSlotPercentage($type)
    {
        if ($type == 'mentor') 
        {
            $MentorsDetails = Setting::select('mentorTax')->where('id', 1)->first();
            return $MentorsDetails->mentorTax ;
        } 
        elseif ($type == 'coach') 
        {
            $MentorsDetails = Setting::select('coachTax')->where('id', 1)->first();
            return $MentorsDetails->coachTax ;
        }
        elseif ($type == 'assessor') 
        {
            $MentorsDetails = Setting::select('assessorTax')->where('id', 1)->first();
            return $MentorsDetails->assessorTax ;
        }
       
        return 1 ;
    }
}
