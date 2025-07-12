<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Models\Api\CMS;
use App\Models\Api\Mentors;
use App\Models\Api\Testimonial;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\TrainingPrograms;
use App\Models\Api\Trainers;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DB;

class AppHomeController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return view('home');
    }

    public function bannersList()
    {
        try {
            $banners = CMS::select('heading', 'description', 'file_path')
                        ->where('slug', 'banner')
                        ->get();

            if ($banners->isEmpty()) {
                return $this->errorResponse('No banners found.', 200,[]);
            }

            return $this->successResponse($banners, 'Banner list fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching banners.', 500,[]);
        }
    }


    public function trainingPrograms()
    {
        try {
            $TrainingPrograms = TrainingPrograms::select('id', 'category','image_path')->get();
            if ($TrainingPrograms->isEmpty()) {
                return $this->errorResponse('No Training programs found.', 200,[]);
            }
            return $this->successResponse($TrainingPrograms, 'Training Programs list fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching Training Programs list.', 500,[]);
        }
    }

    public function trainingCourses()
    {   
        try {
            $TrainingMaterial = TrainingMaterial::select(
                'id',
                'trainer_id',
                'training_title',
                'training_price',
                'training_offer_price',
                'thumbnail_file_path'
            )
            ->with(['trainer:id,name','latestWorkExperience']) // only fetch trainer id & name
            //->with('') // only fetch trainer id & name
            ->withAvg('trainerReviews', 'ratings')->limit(4)->get()->map(function ($item) {
                $avg = $item->trainer_reviews_avg_ratings;
                $item->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                unset($item->trainer_reviews_avg_ratings);
            
                // Add job role from latest work experience
                //$item->job_role = $item->trainerUserId->latestWorkExperience->job_role ?? null;
                return $item;
            });
            if ($TrainingMaterial->isEmpty()) {
                return $this->errorResponse('No Training Courses list found.', 200,[]);
            }
            return $this->successResponse($TrainingMaterial, 'Training Courses list fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching Training Courses list.', 500,[]);
        }
      
    }

    public function mentorsList()
    {
        try {
            $mentorsList = Mentors::select(
                'id',
                'name',
                'email',
                'phone_code',
                'phone_number',
                'date_of_birth',
                'city'
            )
            ->with('WorkExperience') // only fetch trainer id & name        
            ->withAvg('mentorReviews', 'ratings')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                //print_r($item->WorkExperience);
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
            if ($mentorsList->isEmpty()) {
                return $this->errorResponse('No Mentor list found.', 200,[]);
            }
        
            return $this->successResponse($mentorsList, 'Mentor list fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching Mentor list.', 500,[]);
        }
    }

    public function testimonialsList()
    {
       try {
            $testimonialsList = Testimonial::select('name','designation','message','file_path')->get();
            if ($testimonialsList->isEmpty()) {
                return $this->errorResponse('No Testimonial list found.', 200,[]);
            }
            return $this->successResponse($testimonialsList, 'Testimonial list fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while fetching Testimonial list.', 500,[]);
        }
    }

}
