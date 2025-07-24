<?php

namespace App\Http\Controllers\API\Training;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

use App\Models\Api\TrainingExperience;
use App\Models\Api\Trainers;
use App\Models\Api\EducationDetails;
use App\Models\Api\WorkExperience;
use App\Models\Api\TrainingMaterialsDocument;
use App\Models\Api\AdditionalInfo;
use DB;
use Carbon\Carbon;
class TrainerProfileController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function trainersProfileById($id)
    {
        try {
            // Fetch Trainers personal information
            $TrainersPersonal = Trainers::select('*')->where('id', $id)->first();
           
            if (!$TrainersPersonal) {
                return $this->errorResponse('Trainer not found.', 404);
            }

            // Fetch related data
            $TrainersEducation = EducationDetails::select(
                'id', 'user_id', 'user_type', 'high_education', 'field_of_study',
                'institution', 'graduate_year'
            )
            ->where('user_id', $id)
            ->where('user_type', 'trainer')
            ->get();

            $TrainersWorkExp = WorkExperience::select(
                'id', 'user_id', 'user_type', 'job_role', 'organization',
                'starts_from', 'end_to'
            )
            ->where('user_id', $id)
            ->where('user_type', 'trainer')
            ->get();

            $Trainerskill = TrainingExperience::select('*')
            ->where('user_id', $id)
            ->where('user_type', 'trainer')
            ->get();

            $TrainersAdditionalInfo = AdditionalInfo::select(
                'id', 'user_id', 'user_type', 'doc_type',
                'document_name', 'document_path'
            )
            ->where('user_id', $id)
            ->where('user_type', 'trainer')
            ->get();

            // Return combined response
            return $this->successResponse([
                'TrainersPersonal'       => $TrainersPersonal,
                'TrainersEducation'      => $TrainersEducation,
                'TrainersWorkExp'        => $TrainersWorkExp,
                'Trainerskill'          => $Trainerskill,
                'TrainersAdditionalInfo' => $TrainersAdditionalInfo,
            ], 'Trainer profile fetched successfully.');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updatePersonalInfoDetails(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'gender'       => 'required|in:Male,Female,Other',
            'date_of_birth'=> 'required|date|before:today',
            'location'     => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'trainers_id' => 'required'            
        ]);

        try {
            $TrainersId = $request->trainers_id;
            $Trainers = Trainers::where('id', $TrainersId)->first();

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
                'date_of_birth'=> $request->date_of_birth,
                'city'         => $request->location,
                'address'      => $request->address,
            ]);

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'trainer')
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
                        'user_id'       => $Trainers->id,
                        'user_type'     => 'trainer',
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
        // Validate registration fields
        $request->validate([
            // Education
            'education' => 'required|array|min:1',
            'education.*.high_education' => 'required|string|max:255',
            'education.*.field_of_study' => 'required|string|max:255',
            'education.*.institution' => 'required|string|max:255',
            'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,
            'trainers_id' => 'required'
        ]);

        try {
            $TrainersId = $request->trainers_id;
            $EducationDetails = EducationDetails::where('user_id', $TrainersId)->get();

            if ($EducationDetails) {
                EducationDetails::where('user_id', $TrainersId)->delete();
            }

            // Save education
            foreach ($request->education as $edu) {
                EducationDetails::create([
                    'user_id'         => $TrainersId,
                    'user_type'       => 'trainer',
                    'high_education'  => $edu['high_education'],
                    'field_of_study'  => $edu['field_of_study'],
                    'institution'     => $edu['institution'],
                    'graduate_year'   => $edu['graduate_year'],
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'trainer')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'trainer',
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
            $request->validate([
                // Experience
                'experience' => 'nullable|array',
                'experience.*.job_role' => 'required|string|max:255',
                'experience.*.organization' => 'required|string|max:255',
                'experience.*.start_date' => 'required|date|before_or_equal:today',
                'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
                'Trainers_id' => 'required'
            ]);

            $TrainersId = $request->trainers_id;
            $WorkExperience = WorkExperience::where('user_id', $TrainersId)->get();

            if ($WorkExperience) {
                WorkExperience::where('user_id', $TrainersId)->delete();
            }

            // Save experience
            foreach ($request->experience as $exp) {
                WorkExperience::create([
                    'user_id'      => $TrainersId,
                    'user_type'    => 'Trainers',
                    'job_role'     => $exp['job_role'],
                    'organization' => $exp['organization'],
                    'starts_from'  => $exp['start_date'],
                    'end_to'       => $exp['end_date']
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'trainer')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'trainer',
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
            $request->validate([
                // TrainingMaterialsDocument and links
                'training_material_id' => 'required|string',
                'training_title' => 'nullable|string',
                'job_category' => 'nullable|string',
                'website_link' => 'nullable|url',
                'portfolio_link' => 'nullable|url',
                'trainers_id' => 'required'
            ]);

            $TrainersId = $request->trainers_id;
            $TrainingMaterialsDocument = TrainingExperience::where('user_id', $TrainersId)->first();

            if (!$TrainingMaterialsDocument) {
                TrainingExperience::create([
                    'user_id'   => $trainer->id,
                    'user_type'   => 'trainer',
                    'skills'         => $request->skills,
                    'interest'       => $request->interest,
                    'job_category'   => $request->job_category,
                    'website_link'   => $request->website_link,
                    'portfolio_link' => $request->portfolio_link
                ]);
            } else {
                // Update the Trainers basic info
                $TrainingMaterialsDocument->update([
                    'user_id'   => $trainer->id,
                    'user_type'   => 'trainer',
                    'skills'         => $request->skills,
                    'interest'       => $request->interest,
                    'job_category'   => $request->job_category,
                    'website_link'   => $request->website_link,
                    'portfolio_link' => $request->portfolio_link
                ]);
            }

            // Upload Profile Picture
            if ($request->hasFile('profile_picture')) {
                $existingProfile = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'trainer')
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'trainer',
                        'doc_type'      => 'profile_picture',
                        'document_name' => $profileName,
                        'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                    ]);
                }
            }

            return $this->successResponse(null, 'TrainingMaterialsDocument details updated successfully.');

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
            $request->validate([
                // Files
                'resume'          => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'Trainers_id'    => 'required'
            ]);

            $TrainersId = $request->Trainers_id;

            // Upload Resume
            if ($request->hasFile('resume')) {
                $existingResume = AdditionalInfo::where('user_id', $TrainersId)
                    ->where('user_type', 'trainer')
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
                        'user_type'     => 'trainer',
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
                        'user_type'     => 'trainer',
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
                        'user_id'       => $TrainersId,
                        'user_type'     => 'trainer',
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
