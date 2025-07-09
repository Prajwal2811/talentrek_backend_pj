<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Models\Api\CMS;
use App\Models\Api\Mentors;
use App\Models\Api\Testimonial;
use App\Models\Api\TrainingMaterial;

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
        $banners = CMS::select('heading', 'description', 'file_path')->get();
        return $this->successResponse($banners, 'Banner list fetched successfully.');
    }

    public function trainingPrograms()
    {
        $TrainingMaterial = TrainingMaterial::select('id', 'training_title','training_price','training_offer_price','thumbnail_file_path')->addSelect(DB::raw("'UI UX Designer' as designation"))->limit(4)->get();
        return $this->successResponse($TrainingMaterial, 'Training Programs list fetched successfully.');
    }

    public function trainingCourses()
    {
        $TrainingMaterial = TrainingMaterial::select('id', 'training_title','training_price','training_offer_price','thumbnail_file_path')->addSelect(DB::raw("'UI UX Designer' as designation"))->limit(4)->get();
        return $this->successResponse($TrainingMaterial, 'Training Courses list fetched successfully.');
    }

    public function mentorsList()
    {
        $mentorsList = Mentors::select('id','name','email','phone_code','phone_number','date_of_birth','city',)->limit(10)->get();
        return $this->successResponse($mentorsList, 'Mentor list fetched successfully.');
    }

    public function testimonialsList()
    {
       $testimonialsList = Testimonial::select('name','designation','message','file_path')->get();
       return $this->successResponse($testimonialsList, 'Testimonial list fetched successfully.');
    }

}
