<?php
namespace App\Http\Controllers\API\Jobseeker;
use App\Models\Api\CMS;
use App\Models\Api\Assessors;
use App\Models\Api\Mentors;
use App\Models\Api\Coach;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\Trainers;
use App\Models\Api\Review;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DB;
class ExplorerController extends Controller
{
    use ApiResponse;
    public function index($tags = 'training')
    {
        if($tags == 'training'){
                $TrainingMaterial = TrainingMaterial::select('id','trainer_id', 'training_title','training_price','training_offer_price','thumbnail_file_path')->addSelect(DB::raw("'UI UX Designer' as designation"))->with(['trainer:id,name']) // only fetch trainer id & name
             ->with(['trainer:id,name','latestWorkExperience'])->withAvg('trainerReviews', 'ratings')->get()->map(function ($item) {
                $avg = $item->trainer_reviews_avg_ratings;
                $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                unset($item->trainer_reviews_avg_ratings); // remove raw field
                return $item;
            });
            $cmsData = CMS::whereIn('slug', ['course-overview', 'benefits-of-training'])
            ->select('heading', 'description') // optional: include slug for identification
            ->get();
            return $this->successwithCMSResponse($TrainingMaterial,$cmsData , ucwords($tags).' list fetched successfully.');
        }elseif($tags == 'assessor'){
            $assessorList = Assessors::select('id','company_name','company_email','phone_code','company_phone_number','company_instablishment_date','industry_type','company_website')->get();
            return $this->successResponse($assessorList, ucwords($tags).' list fetched successfully.');
        }elseif($tags == 'mentor'){
            $mentorList = Mentors::select('id','name','email','phone_code','phone_number','date_of_birth','city',)->addSelect(DB::raw("'UI UX Designer' as designation"))
            ->with('WorkExperience') // only fetch trainer id & name
            ->withAvg('mentorReviews', 'ratings')
            ->get()
            ->map(function ($item) {
                    $totalDays = collect($item->WorkExperience)->reduce(function ($carry, $exp) {
                    $start = \Carbon\Carbon::parse($exp->start_from);
                    $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                    return $carry + $start->diffInDays($end);
                }, 0);
                $item->total_experience_days = $totalDays;
               $years = floor($totalDays / 365);
                $months = floor(($totalDays % 365) / 30);
                $item->total_experience_years =  $years.'.'.$months ;
                $avg = $item->mentor_reviews_avg_ratings;
                $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                unset($item->mentor_reviews_avg_ratings); // remove raw avg field
                unset($item->WorkExperience);
                return $item;
            });
             $cmsData = CMS::whereIn('slug', ['mentorship-overview', 'benefits-of-mentorship'])
            ->select('heading', 'description') // optional: include slug for identification
            ->get();
            return $this->successwithCMSResponse($mentorList,$cmsData , ucwords($tags).' list fetched successfully.');
        }
        elseif($tags == 'coach'){
            $coachList = Coach::select('id','name','email','phone_code','phone_number','date_of_birth','city')->get();
            return $this->successResponse($coachList, ucwords($tags).' list fetched successfully.');
        }
    }
    public function trainingList()
    {
        try {
            // Fetch training materials with relationships and average ratings
            $trainingMaterials = TrainingMaterial::select('id', 'trainer_id', 'training_title', 'training_price', 'training_offer_price', 'thumbnail_file_path as image')
                ->with(['trainer:id,name', 'latestWorkExperience'])
                ->withAvg('trainerReviews', 'ratings')
                ->get()
                ->map(function ($item) {
                    $avg = $item->trainer_reviews_avg_ratings;
                    $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                    unset($item->trainer_reviews_avg_ratings);
                    return $item;
                });
            // Fetch CMS data
            $cmsData = CMS::whereIn('slug', ['course-overview', 'benefits-of-training'])
                ->select('heading', 'description')
                ->get();
            // Handle empty results
            if ($trainingMaterials->isEmpty() && $cmsData->isEmpty()) {
                return $this->errorResponse( 'No training courses or CMS content found.', 404,[]);
            }
            // Return success with data
            return $this->successwithCMSResponse($trainingMaterials, $cmsData, 'Training Courses list fetched successfully.');
        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }
    public function mentorsExplorerList()
    {
        try {
            // Fetch mentors with work experience and review averages
            $mentorsList = Mentors::select('id', 'name', 'email', 'phone_code', 'phone_number', 'date_of_birth', 'city')
                ->with('WorkExperience','additionalInfo')
                ->withAvg('mentorReviews', 'ratings')
                ->get()
                ->map(function ($item) {
                    $totalDays = collect($item->WorkExperience)->reduce(function ($carry, $exp) {
                        $start = \Carbon\Carbon::parse($exp->start_from);
                        $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $item->total_experience_days = $totalDays;
                    $years = floor($totalDays / 365);
                $months = floor(($totalDays % 365) / 30);
                $item->total_experience_years =  $years.'.'.$months ;
                    // Get the most recent job_role based on nearest end_to (null means current)
                    $mostRecentExp = $item->WorkExperience->sortByDesc(function ($exp) {
                        return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    })->first();
                    $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $avg = $item->mentor_reviews_avg_ratings;
                    $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                    $item->image = $item->additionalInfo->document_path ?? null;
                    unset($item->additionalInfo);
                    unset($item->mentor_reviews_avg_ratings, $item->WorkExperience);
                    return $item;
                });
            // Fetch CMS data
            $cmsData = CMS::whereIn('slug', ['mentorship-overview', 'benefits-of-mentorship'])
                ->select('heading', 'description')
                ->get();
            // Handle empty conditions
            if ($mentorsList->isEmpty() && $cmsData->isEmpty()) {
                return $this->errorResponse('No mentors or CMS content found.', 404,[]);
            }            
            // Return successful response
            return $this->successwithCMSResponse($mentorsList, $cmsData, 'Mentor list fetched successfully.');
        } catch (\Exception $e) {
            // Optional: Log the error
            // Log::error('Mentor fetch error: ' . $e->getMessage());
            return $this->errorResponse('An error occurred while fetching mentors.', 500,[]);
        }
    }
    public function assesserList()
    {
        try {
            // Fetch assessors with work experience and average ratings
            $assessorList = Assessors::select(
                    'id',
                    'company_name as name',
                    'company_email as email',
                    'phone_code',
                    'company_phone_number as phone_number',
                    'company_instablishment_date',
                    'industry_type',
                    'company_website'
                )
                ->with('WorkExperience','additionalInfo')
                ->withAvg('assessorReviews', 'ratings')
                ->get()
                ->map(function ($item) {
                    $totalDays = collect($item->WorkExperience)->reduce(function ($carry, $exp) {
                        $start = \Carbon\Carbon::parse($exp->start_from);
                        $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $item->total_experience_days = $totalDays;
                    $years = floor($totalDays / 365);
                $months = floor(($totalDays % 365) / 30);
                $item->total_experience_years =  $years.'.'.$months ;
                    $avg = $item->assessor_reviews_avg_ratings;
                    $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                    // Get the most recent job_role based on nearest end_to (null means current)
                    $mostRecentExp = $item->WorkExperience->sortByDesc(function ($exp) {
                        return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    })->first();
                    $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->image = $item->additionalInfo->document_path ?? null;
                    unset($item->additionalInfo);
                    unset($item->assessor_reviews_avg_ratings, $item->WorkExperience);
                    return $item;
                });
            // Check if data is empty
            if ($assessorList->isEmpty()) {
                return $this->errorResponse( 'No assessors found.', 404,[]);
            }
          // Fetch CMS data
            $cmsData = CMS::whereIn('slug', ['assessor-overview', 'benefits-of-assessor'])
                ->select('heading', 'description')
                ->get();
                        return $this->successwithCMSResponse($assessorList, $cmsData, 'Assessor list fetched successfully.');
        } catch (\Exception $e) {
            // Optional: Log the error for debugging
            // Log::error('Assessor fetch error: ' . $e->getMessage());
            return $this->errorResponse( 'An error occurred while fetching assessors.', 500,[]);
        }
    }
    public function coachList()
    {
        try {
            // Fetch coach list with experience and average ratings
            $coachList = Coach::select('id', 'name', 'email', 'phone_code', 'phone_number', 'date_of_birth', 'city')
                ->with('WorkExperience','additionalInfo')
                ->withAvg('coachReviews', 'ratings')
                ->get()
                ->map(function ($item) {
                    $totalDays = collect($item->WorkExperience)->reduce(function ($carry, $exp) {
                        $start = \Carbon\Carbon::parse($exp->start_from);
                        $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                        return $carry + $start->diffInDays($end);
                    }, 0);
                    $item->total_experience_days = $totalDays;
                    $years = floor($totalDays / 365);
                    $months = floor(($totalDays % 365) / 30);
                    $item->total_experience_years =  $years.'.'.$months ;
                    $avg = $item->coach_reviews_avg_ratings;
                    $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                    // Get the most recent job_role based on nearest end_to (null means current)
                    $mostRecentExp = $item->WorkExperience->sortByDesc(function ($exp) {
                        return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    })->first();
                    $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->image = $item->additionalInfo->document_path ?? null;
                unset($item->additionalInfo);
                    unset($item->coach_reviews_avg_ratings, $item->WorkExperience);
                    return $item;
                });
            // Handle empty data case
            if ($coachList->isEmpty()) {
                return $this->errorResponse( 'No coaches found.', 404,[]);
            }
          // Fetch CMS data
            $cmsData = CMS::whereIn('slug', ['coaching-overview', 'benefits-of-coaching'])
                ->select('heading', 'description')
                ->get();
                        return $this->successwithCMSResponse($coachList, $cmsData, 'Coach list fetched successfully.');
        } catch (\Exception $e) {
            // Optional: Log::error('Coach fetch error: ' . $e->getMessage());
            return $this->errorResponse( 'An error occurred while fetching coaches.', 500,[]);
        }
    }
    public function trainingMaterialDetailById($trainingId)
    {
        try {
            $TrainingMaterial = TrainingMaterial::select('id','trainer_id','training_type','training_title','training_sub_title','training_descriptions','training_category','training_offer_price','training_price','thumbnail_file_path as image','thumbnail_file_name','training_objective','session_type','admin_status','rejection_reason','created_at','updated_at')
                ->withCount('trainingMaterialDocuments')
                ->with(['trainer:id,name', 'latestWorkExperience'])
                ->with('trainerReviews')
                ->withAvg('trainerReviews', 'ratings')
                ->where('id', $trainingId)
                ->first();
            if ($TrainingMaterial) {
                $avg = $TrainingMaterial->trainer_reviews_avg_ratings;
                $TrainingMaterial->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                // Optional: remove the raw field if not needed in response
                unset($TrainingMaterial->trainer_reviews_avg_ratings);
                unset($TrainingMaterial->trainerReviews);
            }
            if ($TrainingMaterial) {
                $reviews = $TrainingMaterial->trainerReviews;
                $total = $reviews->count();
                $ratingPercentages = [];
                // Initialize rating counts
                foreach (range(1, 5) as $rating) {
                    $count = $reviews->where('ratings', $rating)->count();
                    $ratingPercentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                }
                $TrainingReviewsPercentage = $ratingPercentages;
                unset($TrainingMaterial->trainerReviews);
            }
            return $this->successwithCMSResponse($TrainingMaterial, $TrainingReviewsPercentage, 'Training course details with review  percentage fetched successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function mentorDetailById($mentorId)
    {
        try {
            // Fetch mentor with all required relationships
            $MentorsDetails = Mentors::select('*')
                ->with(['mentorReviews', 'WorkExperience', 'mentorEducations','additionalInfo'])
                ->withAvg('mentorReviews', 'ratings')
                ->where('id', $mentorId)
                ->first();
            if (!$MentorsDetails) {
                return $this->errorResponse( 'Mentor not found.', 404,[]);
            }
            // Calculate total experience
            $totalDays = collect($MentorsDetails->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);
            $MentorsDetails->total_experience_days = $totalDays;
            $MentorsDetails->total_experience_years = round($totalDays / 365, 1);
            // Calculate rating percentages
            $reviews = $MentorsDetails->mentorReviews ?? collect();
            $total = $reviews->count();
            $ratingPercentages = [];
            foreach (range(1, 5) as $rating) {
                $count = $reviews->where('ratings', $rating)->count();
                $ratingPercentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            }
            $MentorReviewsPercentage = $ratingPercentages;
            // Set average rating and clean raw data
            $avg = $MentorsDetails->mentor_reviews_avg_ratings;
            $MentorsDetails->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            // Get the most recent job_role based on nearest end_to (null means current)
            $mostRecentExp = $MentorsDetails->WorkExperience->sortByDesc(function ($exp) {
                return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
            })->first();
                $MentorsDetails->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                $MentorsDetails->image = $MentorsDetails->additionalInfo->document_path ?? null;
                unset($MentorsDetails->additionalInfo);
                unset(
                    $MentorsDetails->mentorReviews,
                    $MentorsDetails->mentor_reviews_avg_ratings,
                    $MentorsDetails->WorkExperience // if not needed on frontend
                );
            return $this->successwithCMSResponse(
                $MentorsDetails,
                $MentorReviewsPercentage,
                'Mentor details with review percentage fetched successfully.'
            );
        } catch (\Exception $e) {
            // Log::error('Mentor detail fetch failed: ' . $e->getMessage());
            return $this->errorResponse( 'An error occurred while fetching mentor details.', 500,[]);
        }
    }
    public function assesserDetailById($assessorId)
    {
        try {
            $AssessorDetails = Assessors::select('*')
                ->with(['assessorReviews', 'assessorEducations', 'WorkExperience','additionalInfo'])
                ->withAvg('assessorReviews', 'ratings')
                ->where('id', $assessorId)
                ->first();
            if (!$AssessorDetails) {
                return $this->errorResponse( 'Assessor not found.', 404,[]);
            }
            // Calculate total experience
            $totalDays = collect($AssessorDetails->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);
            $AssessorDetails->total_experience_days = $totalDays;
            $AssessorDetails->total_experience_years = round($totalDays / 365, 1);
            // Calculate review percentages
            $reviews = $AssessorDetails->assessorReviews ?? collect();
            $total = $reviews->count();
            $ratingPercentages = [];
            foreach (range(1, 5) as $rating) {
                $count = $reviews->where('ratings', $rating)->count();
                $ratingPercentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            }
            $AssessorReviewsPercentage = $ratingPercentages;
            // Set average rating
            $avg = $AssessorDetails->assessor_reviews_avg_ratings;
            $AssessorDetails->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            // Get the most recent job_role based on nearest end_to (null means current)
            $mostRecentExp = $AssessorDetails->WorkExperience->sortByDesc(function ($exp) {
                return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
            })->first();
            $AssessorDetails->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
            $AssessorDetails->image = $AssessorDetails->additionalInfo->document_path ?? null;
            unset($AssessorDetails->additionalInfo);
            // Cleanup raw data
            unset($AssessorDetails->assessorReviews, $AssessorDetails->assessor_reviews_avg_ratings, $AssessorDetails->WorkExperience);
            return $this->successwithCMSResponse(
                $AssessorDetails,
                $AssessorReviewsPercentage,
                'Assessor details with review percentage fetched successfully.'
            );
        } catch (\Exception $e) {
            // Log::error('Assessor detail fetch error: ' . $e->getMessage());
            return $this->errorResponse( 'An error occurred while fetching assessor details.', 500,[]);
        }
    }
    public function coachDetailById($coachId)
    {
        try {
            $CoachDetails = Coach::select('*')
                ->with(['coachReviews', 'coachEducations', 'WorkExperience','additionalInfo'])
                ->withAvg('coachReviews', 'ratings')
                ->where('id', $coachId)
                ->first();
            if (!$CoachDetails) {
                return $this->errorResponse( 'Coach not found.', 404,[]);
            }
            // Calculate total experience
            $totalDays = collect($CoachDetails->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);
            $CoachDetails->total_experience_days = $totalDays;
            $CoachDetails->total_experience_years = round($totalDays / 365, 1);
            // Calculate review percentages
            $reviews = $CoachDetails->coachReviews ?? collect();
            $total = $reviews->count();
            $ratingPercentages = [];
            foreach (range(1, 5) as $rating) {
                $count = $reviews->where('ratings', $rating)->count();
                $ratingPercentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            }
            $CoachReviewsPercentage = $ratingPercentages;
            // Set average rating
            $avg = $CoachDetails->coach_reviews_avg_ratings;
            $CoachDetails->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            // Get the most recent job_role based on nearest end_to (null means current)
            $mostRecentExp = $CoachDetails->WorkExperience->sortByDesc(function ($exp) {
                return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
            })->first();
            $CoachDetails->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                $CoachDetails->image = $CoachDetails->additionalInfo->document_path ?? null;
                unset($CoachDetails->additionalInfo);
            // Cleanup
            unset($CoachDetails->coachReviews, $CoachDetails->coach_reviews_avg_ratings, $CoachDetails->WorkExperience);
            return $this->successwithCMSResponse(
                $CoachDetails,
                $CoachReviewsPercentage,
                'Coach details with review percentage fetched successfully.'
            );
        } catch (\Exception $e) {
            return $this->errorResponse( 'An error occurred while fetching coach details.', 500,[]);
        }
    }
    public function reviewsDetailById($mentorId, $tags = 'trainer')
    {
        try {
            $validTags = ['trainer', 'mentor', 'coach', 'assessor'];
            if (!in_array($tags, $validTags)) {
                return $this->errorResponse('Invalid user type provided.', 422, []);
            }
            $relationMap = [
                'trainer' => ['relation' => 'trainer', 'selectField' => 'name'],
                'mentor' => ['relation' => 'mentor', 'selectField' => 'name'],
                'coach' => ['relation' => 'coach', 'selectField' => 'name'],
                'assessor' => ['relation' => 'assessor', 'selectField' => 'company_name'],
            ];
            $relation = $relationMap[$tags]['relation'];
            $selectField = $relationMap[$tags]['selectField'];
            $reviewsDetails = Review::select('reviews', 'ratings', 'trainer_material')
                ->with([$relation => function ($q) use ($selectField) {
                    $q->select('id', $selectField);
                }])
                ->where('trainer_material', $mentorId)
                ->where('user_type', $tags)
                ->get()
                ->map(function ($item) use ($relation, $selectField) {
                    $item->user_name = $item->$relation->$selectField ?? null;
                    unset($item->$relation);
                    return $item;
                });
            if ($reviewsDetails->isEmpty()) {
                return $this->successResponse([], ucfirst($tags) . ' has no reviews yet.');
            }
            return $this->successResponse($reviewsDetails, ucfirst($tags) . ' reviews fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong while fetching reviews.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }
    public function submitQuizAnswer(Request $request)
    {
        try {
            $request->validate([
                'training_id'     => 'required|integer',
                'trainer_id'      => 'required|integer',
                'jobseeker_id'    => 'required|integer',
                'assessment_id'   => 'required|integer',
                'question_id'     => 'required|integer',
                'selected_answer' => 'required|integer',
            ]);
            // Get correct answer for the question
            $correctAnswerId = AssessmentOption::where('question_id', $request->question_id)
                ->where('correct_option', 1)
                ->value('id');
            $data = [
                'training_id'      => $request->training_id,
                'trainer_id'       => $request->trainer_id,
                'jobseeker_id'     => $request->jobseeker_id,
                'assessment_id'    => $request->assessment_id,
                'question_id'      => $request->question_id,
                'selected_answer'  => $request->selected_answer,
                'correct_answer'   => $correctAnswerId ?? null,
            ];
            AssessmentJobseekerData::updateOrCreate(
                [
                    'assessment_id' => $request->assessment_id,
                    'jobseeker_id'  => $request->jobseeker_id,
                    'question_id'   => $request->question_id,
                ],
                $data
            );
            return $this->successResponse(null, 'Answer submitted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong while submitting the answer.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }    
    public function submitReviewByJobSeeker(Request $request)
    {
        try {
            $request->validate([
                'jobseeker_id' => 'required|integer',
                'user_id'      => 'required|integer',
                'review'       => 'required|string',
                'rating'       => 'required|integer|min:1|max:5',
                'reviewType'   => 'required|string|in:trainer,mentor,coach,assessor,training'
            ]);
            Review::create([
                'jobseeker_id'      => $request->jobseeker_id,
                'user_type'         => $request->reviewType,
                'user_id'           => $request->user_id,
                'reviews'           => $request->review,
                'ratings'           => $request->rating,
                'trainer_material'  => $request->reviewType === 'trainer' ? $request->user_id : null,
            ]);
            return $this->successResponse(null, 'Job seeker review added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong while submitting the review.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }
}
