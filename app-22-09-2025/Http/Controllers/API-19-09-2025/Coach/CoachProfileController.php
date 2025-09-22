<?php

namespace App\Http\Controllers\API\Coach;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\TrainingExperience;
use App\Models\Api\Coach;
use App\Models\Api\EducationDetails;
use App\Models\Api\WorkExperience;
use App\Models\Api\TrainingMaterialsDocument;
use App\Models\Api\AdditionalInfo;
use DB;
use Carbon\Carbon;
class CoachProfileController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function coachProfileById($id)
    {
        try {
            // Fetch Trainers personal information
            $TrainersPersonal = Coach::select('id','name','email','national_id','phone_code','phone_number','date_of_birth','city','state','address','pin_code','country','shortlist','avatar','about_coach', 'about_coach as about','per_slot_price')->where('id', $id)->first();
           
            $coachPersonal = $TrainersPersonal->toArray();
            if ($TrainersPersonal && $TrainersPersonal->date_of_birth) {
                $coachPersonal['date_of_birth'] = date('d/m/Y', strtotime($TrainersPersonal->date_of_birth));
            }

            if (!$TrainersPersonal) {
                return $this->errorResponse('Coach not found.', 404);
            }

            // Fetch related data
            $TrainersEducation = EducationDetails::select(
                'id', 'user_id', 'user_type', 'high_education', 'field_of_study',
                'institution', 'graduate_year'
            )
            ->where('user_id', $id)
            ->where('user_type', 'coach')
            ->get();

            $TrainersWorkExp = WorkExperience::select(
                'id', 'user_id', 'user_type', 'job_role', 'organization',
                'starts_from', 'end_to'
            )
            ->where('user_id', $id)
            ->where('user_type', 'coach')
            ->get()
            ->map(function ($item) {
                $item->starts_from = Carbon::parse($item->starts_from)->format('d/m/Y');
                
                if (strtolower($item->end_to) !== 'work here') {
                    $item->end_to = Carbon::parse($item->end_to)->format('d/m/Y');
                }
                // else keep 'work here' as it is

                return $item;
            });

            $Trainerskill = TrainingExperience::select('id','user_id','training_skills','area_of_interest','job_category','website_link','portfolio_link')
            ->where('user_id', $id)
            ->get();

            $TrainersAdditionalInfo = AdditionalInfo::select(
                'id', 'user_id', 'user_type', 'doc_type',
                'document_name', 'document_path'
            )
            ->where('user_id', $id)
            ->where('user_type', 'coach')
            ->get();
            $image = '' ;
            foreach($TrainersAdditionalInfo  as $TrainersAdditionalInfos){
                if($TrainersAdditionalInfos->doc_type == 'coach_profile_picture'){
                    $image = $TrainersAdditionalInfos->document_path ;
                }                
            }
            
            // Return combined response
            return $this->successWithCmsResponse([
                'CoachPersonal'       => $coachPersonal,
                'CoachEducation'      => $TrainersEducation,
                'CoachWorkExp'        => $TrainersWorkExp,
                'Coachskill'          => $Trainerskill,
                'CoachAdditionalInfo' => $TrainersAdditionalInfo,
            ], ['image' => $image], 'Coach profile fetched successfully.');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updatePersonalInfoDetails(Request $request)
    {
        $TrainersId = $request->coach_id;
        $Trainers = Coach::where('id', $TrainersId)->first();
        // $request->validate([
        //     'name'         => 'required|string|max:255',
        //     'gender'       => 'required|in:Male,Female,Other',
        //     'date_of_birth'=> 'required|date|before:today',
        //     'location'     => 'required|string|max:255',
        //     'address'      => 'required|string|max:500',
        //     'coach_id' => 'required'            
        // ]);

        $data = $request->all();
        $rules = [
            'name' => 'required|string',
            //'gender' => 'required|in:Male,Female,Other',
            'location' => 'required|string',
            'address' => 'required|string',
            'pincode' => 'required',                
            //'about_coach' => 'required',                
            'city' => 'required|string',
            'phone_code' => 'required',
            'phone_number' => 'required|unique:coaches,phone_number,' . $Trainers->id,
            'state' => 'required|string',                
            'country' => 'required|string',
            'per_slot_price' => 'required',
            'national_id' => [
                'required',
                'min:10',
                function ($attribute, $value, $fail) use ($Trainers) {
                    $existsInCoach = Coach::where('national_id', $value)
                        ->where('id', '!=', $Trainers->id)
                        ->exists();

                    if ($existsInCoach) {
                        $fail('The national ID has already been taken.');
                    }
                },
            ],
            'coach_id' => 'required',
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
            

            if (!$Trainers) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data not found.'
                ], 400);
            }

            // Update the Trainers basic info
            $Trainers->update([
                'name'         => $request->name,
                'gender'       => $request->gender,
                'date_of_birth'=> Carbon::createFromFormat('d/m/Y', $request->date_of_birth),
                'address'      => $request->location,
                'city'         => $request->city,
                'state'      => $request->state,
                'phone_code'      => $request->phone_code,
                'phone_number'      => $request->phone_number,
                'country'      => $request->country,
                'pin_code'      => $request->pincode,
                'about_coach'      => $request->about,
                'national_id'      => $request->national_id,
                'per_slot_price'      => $request->per_slot_price,
            ]);

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'coach')
                    ->where('doc_type', 'coach_profile_picture')
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
                        'user_id'       => $Trainers->id,
                        'user_type'     => 'coach',
                        'doc_type'      => 'coach_profile_picture',
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
        // Validate registration fields
        // $request->validate([
        //     // Education
        //     'education' => 'required|array|min:1',
        //     'education.*.high_education' => 'required|string|max:255',
        //     'education.*.field_of_study' => 'required|string|max:255',
        //     'education.*.institution' => 'required|string|max:255',
        //     'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,
        //     'coach_id' => 'required'
        // ]);

        $data = $request->all();
        $rules = [
            'education' => 'required|array|min:1',
            'education.*.high_education' => 'required|string|max:255',
            'education.*.field_of_study' => 'required|string|max:255',
            'education.*.institution' => 'required|string|max:255',
            'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,
            'coach_id' => 'required'
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
            $TrainersId = $request->coach_id;
            $EducationDetails = EducationDetails::where('user_id', $TrainersId)->get();

            if ($EducationDetails) {
                EducationDetails::where('user_id', $TrainersId)->delete();
            }

            // Save education
            foreach ($request->education as $edu) {
                EducationDetails::create([
                    'user_id'         => $TrainersId,
                    'user_type'       => 'coach',
                    'high_education'  => $edu['high_education'],
                    'field_of_study'  => $edu['field_of_study'],
                    'institution'     => $edu['institution'],
                    'graduate_year'   => $edu['graduate_year'],
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'coach')
                    ->where('doc_type', 'coach_profile_picture')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'coach',
                        'doc_type'      => 'coach_profile_picture',
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
            //     'coach_id' => 'required'
            // ]);

            $data = $request->all();
            $rules = [
                'coach_id' => 'required'
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

            $TrainersId = $request->coach_id;
            $WorkExperience = WorkExperience::where('user_id', $TrainersId)->get();

            if ($WorkExperience) {
                WorkExperience::where('user_id', $TrainersId)->delete();
            }

            // Save experience
            foreach ($request->experience as $exp) {
                WorkExperience::create([
                    'user_id'      => $TrainersId,
                    'user_type'    => 'coach',
                    'job_role'     => $exp['job_role'],
                    'organization' => $exp['organization'],
                    'starts_from'  => Carbon::createFromFormat('d/m/Y', $exp['start_date']),
                    'end_to'       => strtolower(trim($exp['end_date'])) === 'work here' ? 'work here' : Carbon::createFromFormat('d/m/Y', $exp['end_date'])
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'coach')
                    ->where('doc_type', 'coach_profile_picture')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'coach',
                        'doc_type'      => 'coach_profile_picture',
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
            // $request->validate([
            //     // TrainingMaterialsDocument and links
            //     'interest' => 'required|string',
            //     'skills' => 'nullable|string',
            //     'job_category' => 'nullable|string',
            //     'website_link' => 'nullable|url',
            //     'portfolio_link' => 'nullable|url',
            //     'coach_id' => 'required'
            // ]);

            $data = $request->all();
            $rules = [
                'skills' => 'required|string',
                'interest' => 'required|string',
                'job_category' => 'required|string',
                'website_link' => 'nullable|url',
                'portfolio_link' => 'nullable|url',
                'coach_id' => 'required'
            ];  
            $validator = Validator::make($data, $rules);

            // Return only the first error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $TrainersId = $request->coach_id;
            $TrainingMaterialsDocument = TrainingExperience::where('user_id', $TrainersId)->first();

            if (!$TrainingMaterialsDocument) {
                TrainingExperience::create([
                    'user_id'   => $request->coach_id,
                    'user_type'   => 'coach',
                    'training_skills'         => $request->skills,
                    'area_of_interest'       => $request->interest,
                    'job_category'   => $request->job_category,
                    'website_link'   => $request->website_link,
                    'portfolio_link' => $request->portfolio_link
                ]);
            } else {
                // Update the Trainers basic info
                $TrainingMaterialsDocument->update([
                    'user_id'   => $request->coach_id,
                    'user_type'   => 'coach',
                    'training_skills'         => $request->skills,
                    'area_of_interest'       => $request->interest,
                    'job_category'   => $request->job_category,
                    'website_link'   => $request->website_link,
                    'portfolio_link' => $request->portfolio_link
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'coach')
                    ->where('doc_type', 'coach_profile_picture')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'coach',
                        'doc_type'      => 'coach_profile_picture',
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
            //     'coach_id'    => 'required'
            // ]);
            $data = $request->all();
            $rules = [
                'resume'          => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'coach_id'    => 'required'
            ];  
            $validator = Validator::make($data, $rules);

            // Return only the first error
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 200);
            }

            $TrainersId = $request->coach_id;

            // Upload Resume
            if ($request->hasFile('resume')) {
                $existingResume = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'coach')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'coach',
                        'doc_type'      => 'resume',
                        'document_name' => $resumeName,
                        'document_path' => asset('uploads/' . $resumeFileName),
                    ]);
                }
            }

            // Upload Resume
            if ($request->hasFile('training_certificate')) {
                $existingResume = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'trainer')
                    ->where('doc_type', 'training_certificate')
                    ->first();

                $resumeName = $request->file('training_certificate')->getClientOriginalName();
                $resumeFileName = 'training_certificate_' . time() . '.' . $request->file('training_certificate')->getClientOriginalExtension();
                $request->file('training_certificate')->move('uploads/', $resumeFileName);

                if ($existingResume) {
                    $existingResume->update([
                        'document_name' => $resumeName,
                        'document_path' => asset('uploads/' . $resumeFileName),
                    ]);
                } else {
                    AdditionalInfo::create([
                        'user_id'       => $TrainersId,
                        'user_type'     => 'coach',
                        'doc_type'      => 'training_certificate',
                        'document_name' => $resumeName,
                        'document_path' => asset('uploads/' . $resumeFileName),
                    ]);
                }
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'trainer')
                    ->where('doc_type', 'coach_profile_picture')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'coach',
                        'doc_type'      => 'coach_profile_picture',
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
