<?php

namespace App\Http\Controllers\API\Jobseeker;

use App\Models\Api\CMS;
use App\Models\Api\Assessors;
use App\Models\Api\Mentors;
use App\Models\Api\Coach;
use App\Models\Api\TrainingMaterial;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DB;


class ExplorerController extends Controller
{
    use ApiResponse;
    
    public function index($tags = 'training')
    {
        if($tags == 'training'){
            $TrainingMaterial = TrainingMaterial::select('id', 'training_title','training_price','training_offer_price','thumbnail_file_path')->addSelect(DB::raw("'UI UX Designer' as designation"))->get();
            $cmsData = CMS::whereIn('slug', ['course-overview', 'benefits-of-training'])
            ->select('heading', 'description') // optional: include slug for identification
            ->get();
            return $this->successwithCMSResponse($TrainingMaterial,$cmsData , ucwords($tags).' list fetched successfully.');
        }elseif($tags == 'assessor'){
            $assessorList = Assessors::select('id','company_name','company_email','phone_code','company_phone_number','company_instablishment_date','industry_type','company_website')->get();
            return $this->successResponse($assessorList, ucwords($tags).' list fetched successfully.');
        }elseif($tags == 'mentor'){
            $mentorList = Mentors::select('id','name','email','phone_code','phone_number','date_of_birth','city',)->get();
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
        $TrainingMaterial = TrainingMaterial::select('id', 'training_title','training_price','training_offer_price','thumbnail_file_path')->addSelect(DB::raw("'UI UX Designer' as designation"))->get();
        $cmsData = CMS::whereIn('slug', ['course-overview', 'benefits-of-training'])
        ->select('heading', 'description') // optional: include slug for identification
        ->get();        
        return $this->successwithCMSResponse($TrainingMaterial,$cmsData , 'Training Courses list fetched successfully.');
    }

    public function mentorsExplorerList()
    {
        $mentorsList = Mentors::select('id','name','email','phone_code','phone_number','date_of_birth','city',)->get();
        $cmsData = CMS::whereIn('slug', ['mentorship-overview', 'benefits-of-mentorship'])
        ->select('heading', 'description') // optional: include slug for identification
        ->get();
        return $this->successwithCMSResponse($mentorsList,$cmsData , 'Mentor list fetched successfully.');
    }

    public function assesserList()
    {
        $assessorList = Assessors::select('id','company_name','company_email','phone_code','company_phone_number','company_instablishment_date','industry_type','company_website')->get();
        return $this->successResponse($assessorList, 'Assessor list fetched successfully.');
    }

    public function coachList()
    {
        $coachList = Coach::select('id','name','email','phone_code','phone_number','date_of_birth','city')->get();
        return $this->successResponse($coachList, 'Coach list fetched successfully.');
    }

    public function trainingMaterialDetailById($trainingId)
    {
        $TrainingMaterial = TrainingMaterial::select('*')->where('id',$trainingId)->first();
        return $this->successResponse($TrainingMaterial, 'Training course details fetched successfully.');
    }

    public function mentorDetailById($mentorId)
    {
        $MentorsDetails = Mentors::select('*')->where('id',$mentorId)->first();
        return $this->successResponse($MentorsDetails, 'Mentor details fetched successfully.');
    }
}
