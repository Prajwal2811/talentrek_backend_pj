<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\Jobseekers;
use App\Models\Api\EducationDetails;
use App\Models\Api\WorkExperience;
use App\Models\Api\Skills;
use App\Models\Api\AdditionalInfo;
use Carbon\Carbon;

class SeekerProfileController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function jobSeekerProfileById($id)
    {
        try {
            // Fetch jobseeker personal information
            $jobseekerPersonal = Jobseekers::select(
                'id', 'name', 'email', 'gender', 'phone_code', 'phone_number',
                'date_of_birth', 'city', 'address','state', 'address','pin_code','country' )->where('id', $id)->first();
            
            $JobSeekPersonal = $jobseekerPersonal->toArray();
            if ($jobseekerPersonal && $jobseekerPersonal->date_of_birth) {
                $JobSeekPersonal['date_of_birth'] = date('d/m/Y', strtotime($jobseekerPersonal->date_of_birth));
            }


            if (!$jobseekerPersonal) {
                return $this->errorResponse('Job seeker not found.', 404);
            }

            // Fetch related data
            $jobseekerEducation = EducationDetails::select(
                'id', 'user_id', 'user_type', 'high_education', 'field_of_study',
                'institution', 'graduate_year'
            )
            ->where('user_id', $id)
            ->where('user_type', 'jobseeker')
            ->get();

            $jobseekerWorkExp = WorkExperience::select(
                'id', 'user_id', 'user_type', 'job_role', 'organization',
                'starts_from', 'end_to'
            )
            ->where('user_id', $id)
            ->where('user_type', 'jobseeker')
            ->get()
            ->map(function ($item) {
                $item->starts_from = Carbon::parse($item->starts_from)->format('d/m/Y');
                
                if (strtolower($item->end_to) !== 'work here') {
                    $item->end_to = Carbon::parse($item->end_to)->format('d/m/Y');
                }
                // else keep 'work here' as it is

                return $item;
            });

            $jobseekerSkill = Skills::select(
                'id', 'jobseeker_id', 'skills', 'interest', 'job_category',
                'website_link', 'portfolio_link'
            )
            ->where('jobseeker_id', $id)
            ->get();

            $jobseekerAdditionalInfo = AdditionalInfo::select(
                'id', 'user_id', 'user_type', 'doc_type',
                'document_name', 'document_path as image'
            )
            ->where('user_id', $id)
            ->where('user_type', 'jobseeker')
            ->get();
           // Get only the profile_image document
           $image = '' ;
            foreach($jobseekerAdditionalInfo  as $jobseekerAdditionalInfos){
                if($jobseekerAdditionalInfos->doc_type == 'profile_picture'){
                    $image = $jobseekerAdditionalInfos->image ;
                }                
            }
            // Return combined response
            return $this->successWithCmsResponse([
               'jobseekerPersonal'       => $JobSeekPersonal,
                'jobseekerEducation'      => $jobseekerEducation,
                'jobseekerWorkExp'        => $jobseekerWorkExp,
                'jobseekerSkill'          => $jobseekerSkill,
                'jobseekerAdditionalInfo' => $jobseekerAdditionalInfo,
            ], ['image' => $image],'Job Seeker profile fetched successfully.');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch job seeker profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updatePersonalInfoDetails(Request $request)
    {
        $jobseekerId = $request->jobseeker_id;
        $jobseeker = Jobseekers::where('id', $jobseekerId)->first();
        // $request->validate([
        //     'name'         => 'required|string|max:255',
        //     'gender'       => 'required|in:Male,Female,Other',
        //     'date_of_birth'=> 'required|date|before:today',
        //     'location'     => 'required|string|max:255',
        //     'address'      => 'required|string|max:500',
        //     'jobseeker_id' => 'required'            
        // ]);
        $data = $request->all();
        $rules = [
            'name' => 'required|string',
            'gender' => 'required|in:Male,Female,Other',
            'location' => 'required|string',
            'address' => 'required|string',
            'pincode' => 'required',                
            'city' => 'required|string',                
            'state' => 'required|string',                
            'country' => 'required|string',
            'national_id' => [
                'required',
                'min:10',
                function ($attribute, $value, $fail) use ($jobseeker) {
                    $existsInJobseekers = Jobseekers::where('national_id', $value)
                        ->where('id', '!=', $jobseeker->id)
                        ->exists();

                    if ($existsInJobseekers) {
                        $fail('The national ID has already been taken.');
                    }
                },
            ],
            'jobseeker_id' => 'required',
        ];        
        $rules["date_of_birth"] = [
            'required',
            'date_format:d/m/Y',
            function ($attribute, $value, $fail) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', $value);
                    
                    if ($date->isToday() || $date->isFuture()) {
                        $fail("The date of birth must be a date before today.");
                    }
                } catch (\Exception $e) {
                    $fail("The date of birth must be a valid date in d/m/Y format.");
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
                'date_of_birth'=> Carbon::createFromFormat('d/m/Y', $request->date_of_birth),
                'address'      => $request->location,
                'city'         => $request->city,
                'state'      => $request->state,
                'country'      => $request->country,
                'pin_code'      => $request->pincode,
                'national_id'      => $request->national_id,
            ]);
            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)
                    ->where('user_type', 'jobseeker')
                    ->where('doc_type', 'profile_picture')
                    ->first();

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

            return $this->successResponse(null, 'Personal information details updated successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function updateEducationInfoDetails(Request $request)
    {
        $data = $request->all();
        $rules = [
            'education' => 'required|array|min:1',
            'education.*.high_education' => 'required|string|max:255',
            'education.*.field_of_study' => 'required|string|max:255',
            'education.*.institution' => 'required|string|max:255',
            'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,
            'jobseeker_id' => 'required'
        ];   // Validate registration fields

        // $request->validate([
        //     // Education
        //     'education' => 'required|array|min:1',
        //     'education.*.high_education' => 'required|string|max:255',
        //     'education.*.field_of_study' => 'required|string|max:255',
        //     'education.*.institution' => 'required|string|max:255',
        //     'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,
        //     'jobseeker_id' => 'required'
        // ]);

        $validator = Validator::make($data, $rules);

        // Return only the first error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        try {
            $jobseekerId = $request->jobseeker_id;
            $EducationDetails = EducationDetails::where('user_id', $jobseekerId)->get();

            if ($EducationDetails) {
                EducationDetails::where('user_id', $jobseekerId)->delete();
            }

            // Save education
            foreach ($request->education as $edu) {
                EducationDetails::create([
                    'user_id'         => $jobseekerId,
                    'user_type'       => 'jobseeker',
                    'high_education'  => $edu['high_education'],
                    'field_of_study'  => $edu['field_of_study'],
                    'institution'     => $edu['institution'],
                    'graduate_year'   => $edu['graduate_year'],
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)
                    ->where('user_type', 'jobseeker')
                    ->where('doc_type', 'profile_picture')
                    ->first();

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
                        'user_id'       => $jobseekerId,
                        'user_type'     => 'jobseeker',
                        'doc_type'      => 'profile_picture',
                        'document_name' => $profileName,
                        'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                    ]);
                }
            }

            return $this->successResponse(null, 'Education details updated successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function updateWorkExperienceInfoDetails(Request $request)
    {
        try {
            // Validate registration fields
            // $request->validate([
            //     // Experience
            //     'experience' => 'nullable|array',
            //     'experience.*.job_role' => 'required|string|max:255',
            //     'experience.*.organization' => 'required|string|max:255',
            //     'experience.*.start_date' => 'required|date|before_or_equal:today',
            //     'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
            //     'jobseeker_id' => 'required'
            // ]);
            $data = $request->all();
            $rules = [
                'jobseeker_id' => 'required'
            ]; 
           
            if (!empty($data['experience'])) {
                foreach ($data['experience'] as $index => $exp) {
                    $rules["experience.$index.job_role"] = 'required|string';
                    $rules["experience.$index.organization"] = 'required|string';
                    $rules["experience.$index.start_date"] = [
                        'required',
                        'date_format:d/m/Y',
                        function ($attribute, $value, $fail) {
                            $date = Carbon::createFromFormat('d/m/Y', $value);
                            if ($date->isFuture()) {
                                $fail("$attribute should not be a future date.");
                            }
                        },
                    ];
                    if($data['experience'][$index]['end_date'] != 'work here'){
                        $rules["experience.$index.end_date"] = [
                            'required',
                            'date_format:d/m/Y',
                            function ($attribute, $value, $fail) use ($exp,$index) {
                                $end = Carbon::createFromFormat('d/m/Y', $value);
                                $start = isset($exp['start_date']) ? Carbon::createFromFormat('d/m/Y', $exp['start_date']) : null;

                                if ($end->isFuture()) {
                                    $fail("Experience " . ($index + 1) . " end date should not be a future date.");
                                }

                                if ($start && $end->lessThan($start)) {
                                    $fail("Experience " . ($index + 1) . " end date should not be earlier than start date.");
                                }
                            },
                        ];
                    }
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'The experience details must be required.'
                ], 200);
            }

            $validator = Validator::make($data, $rules);

            // Return only the first error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $jobseekerId = $request->jobseeker_id;
            $WorkExperience = WorkExperience::where('user_id', $jobseekerId)->get();

            if ($WorkExperience) {
                WorkExperience::where('user_id', $jobseekerId)->delete();
            }

            // Save experience
            foreach ($request->experience as $exp) {
                WorkExperience::create([
                    'user_id'      => $jobseekerId,
                    'user_type'    => 'jobseeker',
                    'job_role'     => $exp['job_role'],
                    'organization' => $exp['organization'],
                    'starts_from'  => Carbon::createFromFormat('d/m/Y', $exp['start_date']),
                    'end_to'       => strtolower(trim($exp['end_date'])) === 'work here' ? 'work here' : Carbon::createFromFormat('d/m/Y', $exp['end_date'])
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)
                    ->where('user_type', 'jobseeker')
                    ->where('doc_type', 'profile_picture')
                    ->first();

                $profileName = $request->file('profile_picture')->getClientOriginalName();
                $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

                if ($existingProfile) {
                    $existingProfile->update([
                        'document_name' => $profileName,
                        'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                    ]);
                } else {
                    AdditionalInfo::create([
                        'user_id'       => $jobseekerId,
                        'user_type'     => 'jobseeker',
                        'doc_type'      => 'profile_picture',
                        'document_name' => $profileName,
                        'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                    ]);
                }
            }

            return $this->successResponse(null, 'Work experience details updated successfully.');
            
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function updateSkillsInfoDetails(Request $request)
    {
        try {
            // Validate registration fields
            $data = $request->all();
            $rules = [
                'skills' => 'required|string',
                'interest' => 'required|string',
                'job_category' => 'required|string',
                'website_link' => 'nullable|url',
                'portfolio_link' => 'nullable|url',
                'jobseeker_id' => 'required'
            ];  
            $validator = Validator::make($data, $rules);

            // Return only the first error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }
            
            // $request->validate([
            //     // Skills and links
            //     'skills' => 'required|string',
            //     'interest' => 'required|string',
            //     'job_category' => 'required|string',
            //     'website_link' => 'required|url',
            //     'portfolio_link' => 'required|url',
            //     'jobseeker_id' => 'required'
            // ]);

            $jobseekerId = $request->jobseeker_id;
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
            } else {
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
                $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)
                    ->where('user_type', 'jobseeker')
                    ->where('doc_type', 'profile_picture')
                    ->first();

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
                        'user_id'       => $jobseekerId,
                        'user_type'     => 'jobseeker',
                        'doc_type'      => 'profile_picture',
                        'document_name' => $profileName,
                        'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                    ]);
                }
            }

            return $this->successResponse(null, 'Skills details updated successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateAdditionalInfoDetails(Request $request)
    {
        try {
            // Validate registration fields
            // $request->validate([
            //     // Files
            //     'resume'          => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            //     'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            //     'jobseeker_id'    => 'required'
            // ]);

            $data = $request->all();
            $rules = [
                'resume'          => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'jobseeker_id'    => 'required'
            ];  
            $validator = Validator::make($data, $rules);

            // Return only the first error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $jobseekerId = $request->jobseeker_id;

            // Upload Resume
            if ($request->hasFile('resume')) {
                $existingResume = AdditionalInfo::where('user_id', $jobseekerId)
                    ->where('user_type', 'jobseeker')
                    ->where('doc_type', 'resume')
                    ->first();

                $resumeName = $request->file('resume')->getClientOriginalName();
                $resumeFileName = 'resume_' . time() . '.' . $request->file('resume')->getClientOriginalExtension();
                $request->file('resume')->move('uploads/', $resumeFileName);

                if ($existingResume) {
                    $existingResume->update([
                        'document_name' => $resumeName,
                        'document_path' => asset('uploads/' . $resumeFileName),
                    ]);
                } else {
                    AdditionalInfo::create([
                        'user_id'       => $jobseekerId,
                        'user_type'     => 'jobseeker',
                        'doc_type'      => 'resume',
                        'document_name' => $resumeName,
                        'document_path' => asset('uploads/' . $resumeFileName),
                    ]);
                }
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $jobseekerId)
                    ->where('user_type', 'jobseeker')
                    ->where('doc_type', 'profile_picture')
                    ->first();

                $profileName = $request->file('profile_picture')->getClientOriginalName();
                $profileFileName = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $request->file('profile_picture')->move('uploads/', $profileFileName);

                if ($existingProfile) {
                    $existingProfile->update([
                        'document_name' => $profileName,
                        'document_path' => asset('uploads/' . $profileFileName),
                    ]);
                } else {
                    AdditionalInfo::create([
                        'user_id'       => $jobseekerId,
                        'user_type'     => 'jobseeker',
                        'doc_type'      => 'profile_picture',
                        'document_name' => $profileName,
                        'document_path' => asset('uploads/' . $profileFileName),
                    ]);
                }
            }

            return $this->successResponse(null, 'Additional info details updated successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}
