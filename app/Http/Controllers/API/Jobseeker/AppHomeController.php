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
        $banners = CMS::select('heading', 'description', 'file_path')->where('slug','banner')->get();
        return $this->successResponse($banners, 'Banner list fetched successfully.');
    }

    public function trainingPrograms()
    {
        $TrainingPrograms = TrainingPrograms::select('id', 'category','image_path')->get();
        return $this->successResponse($TrainingPrograms, 'Training Programs list fetched successfully.');
    }

    public function trainingCourses()
    {   
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
      
        return $this->successResponse($TrainingMaterial, 'Trainingddd Courses list fetched successfully.');
    }

    public function mentorsList()
    {
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

       // $mentorsList = Mentors::select('id','name','email','phone_code','phone_number','date_of_birth','city',)->limit(10)->get();
        return $this->successResponse($mentorsList, 'Mentor list fetched successfully.');
    }

    public function testimonialsList()
    {
       $testimonialsList = Testimonial::select('name','designation','message','file_path')->get();
       return $this->successResponse($testimonialsList, 'Testimonial list fetched successfully.');
    }

}
