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
                $item->total_experience_years = round($totalDays / 365, 1);
                
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
        $TrainingMaterial = TrainingMaterial::select('id','trainer_id', 'training_title','training_price','training_offer_price','thumbnail_file_path')
         ->with(['trainer:id,name','latestWorkExperience']) 
         ->withAvg('trainerReviews', 'ratings')->get()->map(function ($item) {
            $avg = $item->trainer_reviews_avg_ratings;
            $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            unset($item->trainer_reviews_avg_ratings); // remove raw field
            return $item;
        });
        $cmsData = CMS::whereIn('slug', ['course-overview', 'benefits-of-training'])
        ->select('heading', 'description') // optional: include slug for identification
        ->get();        
        return $this->successwithCMSResponse($TrainingMaterial,$cmsData , 'Training Courses list fetched successfully.');
    }

    public function mentorsExplorerList()
    {
        $mentorsList = Mentors::select('id','name','email','phone_code','phone_number','date_of_birth','city',)->addSelect(DB::raw("'UI UX Designer' as designation"))
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
            $item->total_experience_years = round($totalDays / 365, 1);

            $avg = $item->mentor_reviews_avg_ratings;
            $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            unset($item->mentor_reviews_avg_ratings); // remove raw avg field
            unset($item->WorkExperience);
            return $item;
        });
        $cmsData = CMS::whereIn('slug', ['mentorship-overview', 'benefits-of-mentorship'])
        ->select('heading', 'description') // optional: include slug for identification
        ->get();
        return $this->successwithCMSResponse($mentorsList,$cmsData , 'Mentor list fetched successfully.');
    }

    public function assesserList()
    {
        $assessorList = Assessors::select('id','company_name','company_email','phone_code','company_phone_number','company_instablishment_date','industry_type','company_website')
        ->with('WorkExperience') // only fetch trainer id & name
        ->withAvg('assessorReviews', 'ratings')
        ->get()->map(function ($item) {
            $totalDays = collect($item->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);

            $item->total_experience_days = $totalDays;
            $item->total_experience_years = round($totalDays / 365, 1);

            $avg = $item->assessor_reviews_avg_ratings;
            $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            unset($item->assessor_reviews_avg_ratings); // remove raw avg field
            unset($item->WorkExperience);
            return $item;
        });
        return $this->successResponse($assessorList, 'Assessor list fetched successfully.');
    }

    public function coachList()
    {
        $coachList = Coach::select('id','name','email','phone_code','phone_number','date_of_birth','city')
        ->with('WorkExperience') // only fetch trainer id & name
        ->withAvg('coachReviews', 'ratings')
        ->get()->map(function ($item) {
            $totalDays = collect($item->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);

            $item->total_experience_days = $totalDays;
            $item->total_experience_years = round($totalDays / 365, 1);

            $avg = $item->coach_reviews_avg_ratings;
            $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
            unset($item->coach_reviews_avg_ratings); // remove raw avg field
            unset($item->WorkExperience);
            return $item;
        });
        return $this->successResponse($coachList, 'Coach list fetched successfully.');
    }

    public function trainingMaterialDetailById($trainingId)
    {
        
        $TrainingMaterial = TrainingMaterial::select('*')->withCount('trainingMaterialDocuments')->with(['trainer:id,name','latestWorkExperience']) ->with('trainerReviews')->withAvg('trainerReviews', 'ratings')->where('id',$trainingId)->first();
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

        return $this->successwithCMSResponse( $TrainingMaterial,$TrainingReviewsPercentage, 'Training course details with review  percentage fetched successfully.');
    }

    public function mentorDetailById($mentorId)
    {
        $MentorsDetails = Mentors::select('*')->with('mentorReviews')->with('WorkExperience')->with('mentorEducations')->withAvg('mentorReviews', 'ratings')->where('id',$mentorId)->first();

        if ($MentorsDetails) {
            $totalDays = collect($MentorsDetails->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);

            $MentorsDetails->total_experience_days = $totalDays;
            $MentorsDetails->total_experience_years = round($totalDays / 365, 1);

            $reviews = $MentorsDetails->mentorReviews;

            $total = $reviews->count();
            $ratingPercentages = [];

            // Initialize rating counts
            foreach (range(1, 5) as $rating) {
                $count = $reviews->where('ratings', $rating)->count();
                $ratingPercentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            }

            $MentorReviewsPercentage = $ratingPercentages;
            unset($MentorsDetails->mentorReviews);
            unset($MentorsDetails->WorkExperience);

        }
        return $this->successwithCMSResponse( $MentorsDetails,$MentorReviewsPercentage, 'Mentor details with review  percentage fetched successfully.');
    }

    public function assesserDetailById($mentorId)
    {
        $AssessorDetails = Assessors::select('*')->with('assessorReviews')->with('assessorEducations')->with('WorkExperience')->withAvg('assessorReviews', 'ratings')->where('id',$mentorId)->first();
        if ($AssessorDetails) {
            $totalDays = collect($AssessorDetails->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);

            $AssessorDetails->total_experience_days = $totalDays;
            $AssessorDetails->total_experience_years = round($totalDays / 365, 1);
            $reviews = $AssessorDetails->assessorReviews;

            $total = $reviews->count();
            $ratingPercentages = [];

            // Initialize rating counts
            foreach (range(1, 5) as $rating) {
                $count = $reviews->where('ratings', $rating)->count();
                $ratingPercentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            }

            $AssessorReviewsPercentage = $ratingPercentages;
            unset($AssessorDetails->assessorReviews);
            unset($AssessorDetails->WorkExperience);

        }
        return $this->successwithCMSResponse( $AssessorDetails,$AssessorReviewsPercentage, 'Assessor details with review  percentage fetched successfully.');
    }

    public function coachDetailById($mentorId)
    {
        $CoachDetails = Coach::select('*')->with('coachReviews')->with('coachEducations')->with('WorkExperience')->withAvg('coachReviews', 'ratings')->where('id',$mentorId)->first();

        if ($CoachDetails) {
            $totalDays = collect($CoachDetails->WorkExperience)->reduce(function ($carry, $exp) {
                $start = \Carbon\Carbon::parse($exp->start_from);
                $end = \Carbon\Carbon::parse($exp->end_to ?? now());
                return $carry + $start->diffInDays($end);
            }, 0);

            $CoachDetails->total_experience_days = $totalDays;
            $CoachDetails->total_experience_years = round($totalDays / 365, 1);
            $reviews = $CoachDetails->coachReviews;

            $total = $reviews->count();
            $ratingPercentages = [];

            // Initialize rating counts
            foreach (range(1, 5) as $rating) {
                $count = $reviews->where('ratings', $rating)->count();
                $ratingPercentages[$rating] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            }

            $CoachReviewsPercentage = $ratingPercentages;
            unset($CoachDetails->coachReviews);
            unset($CoachDetails->WorkExperience);

        }
        return $this->successwithCMSResponse( $CoachDetails,$CoachReviewsPercentage, 'Assessor details with review  percentage fetched successfully.');
    }

    public function reviewsDetailById($mentorId,$tags = 'trainer')
    {
        if ($tags == 'trainer') {
            $reviewsDetails = Review::select('reviews', 'ratings', 'trainer_material')->with('trainer:id,name')
                ->where('trainer_material', $mentorId)
                ->where('user_type', $tags)
                ->get()
                ->map(function ($item) {
                    $item->user_name = $item->trainer->name ?? null;
                    unset($item->trainer);
                    return $item;
                });
        } elseif ($tags == 'mentor') {
            $reviewsDetails = Review::select('reviews', 'ratings', 'trainer_material')->with('mentor:id,name')
                ->where('trainer_material', $mentorId)
                ->where('user_type', $tags)
                ->get()
                ->map(function ($item) {
                    $item->user_name = $item->mentor->name ?? null;
                    unset($item->mentor);
                    return $item;
                });
        } elseif ($tags == 'coach') {
            $reviewsDetails = Review::select('reviews', 'ratings', 'trainer_material')->with('coach:id,name')
                ->where('trainer_material', $mentorId)
                ->where('user_type', $tags)
                ->get()
                ->map(function ($item) {
                    $item->user_name = $item->coach->name ?? null;
                    unset($item->coach);
                    return $item;
                });
        } elseif ($tags == 'assessor') {
            $reviewsDetails = Review::select('reviews', 'ratings', 'trainer_material')->with('assessor:id,company_name')
                ->where('trainer_material', $mentorId)
                ->where('user_type', $tags)
                ->get()
                ->map(function ($item) {
                    $item->user_name = $item->assessor->company_name ?? null;
                    unset($item->assessor);
                    return $item;
                });
        }
        return $this->successResponse( $reviewsDetails, ucwords($tags).' reviews fetched successfully.');
    }

    public function submitQuizAnswer(Request $request)
    {
        $request->validate([
            'training_id'    => 'required|integer',
            'trainer_id'     => 'required|integer',
            'jobseeker_id'   => 'required|integer',
            'assessment_id'  => 'required|integer',
            'question_id'    => 'required|integer',
            'selected_answer'=> 'required|integer',
        ]);

        // Optional: You may fetch correct_answer from the options table
        $correctAnswerId = AssessmentOption::where('question_id', $request->question_id)
            ->where('correct_option', 1) // Assuming a boolean column
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

        // Update or create based on composite keys
        AssessmentJobseekerData::updateOrCreate(
            [
                'assessment_id' => $request->assessment_id,
                'jobseeker_id'  => $request->jobseeker_id,
                'question_id'   => $request->question_id,
            ],
            $data
        );

        return $this->successResponse(null, 'Answer submitted successfully.');
    }

    public function submitReviewByJobSeeker(Request $request)
    {
        $request->validate([
            'jobseeker_id'    => 'required|integer',
            'user_id'     => 'required|integer',
            'review'   => 'required',
            'rating '  => 'required|integer',
            'reviewType'    => 'required'
        ]);

       Review::create([
            'jobseeker_id'  => $request->jobseeker_id,
            'user_type'     => $request->reviewType,
            'user_id'      => $request->user_id,
            'reviews' => $request->review,
            'ratings' => $request->rating,
            'trainer_material' => $request->reviewType === 'training' ? $request->user_id : null,
        ]);

        return $this->successResponse(null, 'Job seeker review added successfully.');
    }
}
