<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

use App\Models\Api\Jobseekers;
use App\Models\Api\EducationDetails;
use App\Models\Api\WorkExperience;
use App\Models\Api\Skills;
use App\Models\Api\AdditionalInfo;

class SeekerProfileController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function jobSeekerProfileById($id)
    {
        $jobseekerPersonal = Jobseekers::select('id','name','email','gender','phone_code','phone_number', 'date_of_birth', 'city','address',)->where('id', $id)->first();
        $jobseekerEducation = EducationDetails::select('id' ,'user_id' ,'user_type' ,'high_education' ,'field_of_study' ,'institution' ,'graduate_year' )->where('user_id', $id)->where('user_type','jobseeker')->get();
        $jobseekerWorkExp = WorkExperience::select('id' ,'user_id' ,'user_type' ,'job_role', 'organization' ,'starts_from' ,'end_to')->where('user_id', $id)->where('user_type','jobseeker')->get();
        $jobseekerSkill = Skills::select('id', 'jobseeker_id' ,'skills' ,'interest' ,'job_category' ,'website_link', 'portfolio_link')->where('jobseeker_id', $id)->get();
        $jobseekerAdditionalInfo = AdditionalInfo::select('id' ,'user_id' ,'user_type' ,'doc_type' ,'document_name' ,'document_path')->where('user_id', $id)->where('user_type','jobseeker')->get();
                
        return $this->successResponse(['jobseekerPersonal' => $jobseekerPersonal,'jobseekerEducation' => $jobseekerEducation,'jobseekerWorkExp' => $jobseekerWorkExp,'jobseekerSkill' => $jobseekerSkill,'jobseekerAdditionalInfo' => $jobseekerAdditionalInfo], 'Job Seeker profile fetched successfully.');       
        
    }

    public function updatePersonalInfoDetails(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'gender'       => 'required|in:Male,Female,Other',
            'date_of_birth'=> 'required|date|before:today',
            'location'     => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'password'     => 'required|string|min:6|confirmed',
            'jobseeker_id' => 'required'            
        ]);
        $jobseekerId = $request->jobseeker_id ;
        $jobseeker = Jobseekers::where('id', $jobseekerId)->first();

        if (!$jobseeker) {
            return response()->json([
                'status'  => false,
                'message' => 'Data not found.'
            ], 400);
        }
        
        // Update the jobseeker basic info
        $jobseeker->update([
            'name'         => $request->name,
            'gender'       => $request->gender,
            'date_of_birth'=> $request->date_of_birth,
            'city'         => $request->location,
            'address'      => $request->address,
        ]);

        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {
            $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)->where('user_type', 'jobseeker')->where('doc_type', 'profile_picture')->first();

            $profileName = $request->file('profile_picture')->getClientOriginalName();
            $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

            if ($existingProfile) {
                // Update existing record
                $existingProfile->update([
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } else {
                // Create new record
                AdditionalInfo::create([
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } 
        }
        return $this->successResponse(null, 'Personal information  details updated successfully.');
    }

    public function updateEducationInfoDetails(Request $request)
    {
        // Validate registration fields
        $request->validate([
            // Education
            'education' => 'required|array|min:1',
            'education.*.high_education' => 'required|string|max:255',
            'education.*.field_of_study' => 'required|string|max:255',
            'education.*.institution' => 'required|string|max:255',
            'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,
            'jobseeker_id' => 'required'
        ]);
        $jobseekerId = $request->jobseeker_id ;
        $EducationDetails = EducationDetails::where('user_id', $jobseekerId)->get();
        if ($EducationDetails) {
            EducationDetails::where('user_id', $jobseeker->id)->delete();
        }
        // Save education
        foreach ($request->education as $edu) {
            EducationDetails::create([
                'user_id'         => $jobseeker->id,
                'user_type'       => 'jobseeker',
                'high_education'  => $edu['high_education'],
                'field_of_study'  => $edu['field_of_study'],
                'institution'     => $edu['institution'],
                'graduate_year'   => $edu['graduate_year'],
            ]);
        }
        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {
            $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)->where('user_type', 'jobseeker')->where('doc_type', 'profile_picture')->first();

            $profileName = $request->file('profile_picture')->getClientOriginalName();
            $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

            if ($existingProfile) {
                // Update existing record
                $existingProfile->update([
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } else {
                // Create new record
                AdditionalInfo::create([
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } 
        }
        return $this->successResponse(null, 'Education  details updated successfully.');
    }

    public function updateWorkExperienceInfoDetails(Request $request)
    {
        // Validate registration fields
        $request->validate([
            // Experience
            'experience' => 'nullable|array',
            'experience.*.job_role' => 'required|string|max:255',
            'experience.*.organization' => 'required|string|max:255',
            'experience.*.start_date' => 'required|date|before_or_equal:today',
            'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
            'jobseeker_id' => 'required'
        ]);

        $jobseekerId = $request->jobseeker_id ;
        $WorkExperience = WorkExperience::where('user_id', $jobseekerId)->get();
        if ($WorkExperience) {
            WorkExperience::where('user_id', $jobseeker->id)->delete();
        }
        // Save experience
        foreach ($request->experience as $exp) {
            WorkExperience::create([
                'user_id'      => $jobseeker->id,
                'user_type'    => 'jobseeker',
                'job_role'     => $exp['job_role'],
                'organization' => $exp['organization'],
                'starts_from'  => $exp['start_date'],
                'end_to'       => $exp['end_date']
            ]);
        }
        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {
            $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)->where('user_type', 'jobseeker')->where('doc_type', 'profile_picture')->first();

            $profileName = $request->file('profile_picture')->getClientOriginalName();
            $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

            if ($existingProfile) {
                // Update existing record
                $existingProfile->update([
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } else {
                // Create new record
                AdditionalInfo::create([
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } 
        }
        return $this->successResponse(null, 'Eork experience  details updated successfully.');
    }

    public function updateSkillsInfoDetails(Request $request)
    {
        // Validate registration fields
        $request->validate([
            // Skills and links
            'skills' => 'nullable|string',
            'interest' => 'nullable|string',
            'job_category' => 'nullable|string',
            'website_link' => 'nullable|url',
            'portfolio_link' => 'nullable|url',
            'jobseeker_id' => 'required'
        ]);

        $jobseekerId = $request->jobseeker_id ;
        $skills = Skills::where('jobseeker_id', $jobseekerId)->first();

        if (!$skills) {
            Skills::create([
                'jobseeker_id'   => $jobseekerId,
                'skills'         => $request->skills,
                'interest'       => $request->interest,
                'job_category'   => $request->job_category,
                'website_link'   => $request->website_link,
                'portfolio_link' => $request->portfolio_link
            ]);
        }else{ 
            // Update the jobseeker basic info
            $skills->update([
                'skills'         => $request->skills,
                'interest'       => $request->interest,
                'job_category'   => $request->job_category,
                'website_link'   => $request->website_link,
                'portfolio_link' => $request->portfolio_link
            ]);
        }

        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {
            $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)->where('user_type', 'jobseeker')->where('doc_type', 'profile_picture')->first();

            $profileName = $request->file('profile_picture')->getClientOriginalName();
            $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

            if ($existingProfile) {
                // Update existing record
                $existingProfile->update([
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } else {
                // Create new record
                AdditionalInfo::create([
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } 
        }
        return $this->successResponse(null, 'Skills details updated successfully.');
    }

    public function updateAdditionalInfoDetails(Request $request)
    {
        $// Validate registration fields
            $request->validate([
                // Files
                'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'jobseeker_id' => 'required'
            ]);

        
        // Upload Profile Picture
        if ($request->hasFile('resume')) {
            $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)->where('user_type', 'jobseeker')->where('doc_type', 'resume')->first();

            $profileName = $request->file('resume')->getClientOriginalName();
            $fileNameToStoreProfile = 'resume_' . time() . '.' . $request->file('resume')->getClientOriginalExtension();
            $request->file('resume')->move('uploads/', $fileNameToStoreProfile);

            if ($existingProfile) {
                // Update existing record
                $existingProfile->update([
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } else {
                // Create new record
                AdditionalInfo::create([
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'resume',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } 
        }

        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {
            $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)->where('user_type', 'jobseeker')->where('doc_type', 'profile_picture')->first();

            $profileName = $request->file('profile_picture')->getClientOriginalName();
            $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

            if ($existingProfile) {
                // Update existing record
                $existingProfile->update([
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } else {
                // Create new record
                AdditionalInfo::create([
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            } 
        }
        return $this->successResponse(null, 'Additional info  details updated successfully.');
    }
}
