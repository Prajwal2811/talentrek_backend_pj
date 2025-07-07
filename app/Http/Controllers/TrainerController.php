<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainers;
use App\Models\TrainingExperience;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\Additionalinfo;
use App\Models\TrainingMaterial;
use App\Models\TrainingMaterialsDocument;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;


class TrainerController extends Controller
{
    public function showSignInForm(){
        return view('site.trainer.sign-in'); 
    }
    public function showSignUpForm()
    {
    return view('site.trainer.sign-up');
    }
    public function showRegistrationForm()
    {
    return view('site.trainer.registration');
    }
    public function showForgotPasswordForm()
    {
        return view('site.trainer.forget-password');
    }
    public function showOtpForm()
    {
        return view('site.trainer.verify-otp'); 
    }
    public function showResetPasswordForm()
    {
        return view('site.trainer.reset-password'); 
    }

    public function postRegistration(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:trainers,email',
            'phone_number' => 'required|unique:trainers,phone_number',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
        

        $trainers = Trainers::create([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'pass' => $request->password,
        ]);
        
      
        session([
            'trainer_id' => $trainers->id,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('trainer.registration');
    }

    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'contact' => ['required', function ($attribute, $value, $fail) {
                $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                $column = $isEmail ? 'email' : 'phone_number';

                $exists = DB::table('trainers')->where($column, $value)->exists();

                if (!$exists) {
                    $fail("This " . ($isEmail ? 'email' : 'mobile number') . " is not registered.");
                }
            }],
        ]);

        $otp = rand(100000, 999999);
        $contact = $request->contact;
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
        $contactMethod = $isEmail ? 'email' : 'phone_number';

        // Save OTP in database
        DB::table('trainers')->where($contactMethod, $contact)->update([
            'otp' => $otp,
            'updated_at' => now()
        ]);

        // === OTP sending is disabled for now ===
        if ($isEmail) {
            // Mail::html(view('emails.otp', compact('otp'))->render(), function ($message) use ($contact) {
            //     $message->to($contact)->subject('Your Password Reset OTP – Talentrek');
            // });
        } else {
            // SmsService::send($contact, "Your OTP is: $otp");
        }

        // Store contact info in session
        session([
            'otp_method' => $contactMethod,
            'otp_value' => $contact
        ]);

        // Then redirect to OTP verification page
        return redirect()->route('trainer.verify-otp')->with('success', 'OTP sent!');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'contact' => 'required',
            'otp' => ['required', 'digits:6'],
        ]);

        $contact = $request->contact;
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
        $column = $isEmail ? 'email' : 'phone_number';

        $trainers = DB::table('trainers')
            ->where($column, $contact)
            ->where('otp', $request->otp)
            ->first();

        if (!$trainers) {
            return back()
                ->withErrors(['otp' => 'Invalid OTP or contact. Please enter the correct 6-digit OTP.'])
                ->withInput();
        }

        // Save verified user ID in session
        session(['verified_recruiter' => $trainers->id]);

        return redirect()->route('trainer.reset-password');
    }

    public function resetPassword(Request $request){
       $request->validate([
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
        $trainerId = session('verified_recruiter');
       
        if (!$trainerId) {
            return redirect()->route('recruiter.forget.password')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $updated = DB::table('trainers')->where('id', $trainerId)->update([
            'password' => Hash::make($request->new_password),
            'pass' => $request->new_password,
            'otp' => null, 
            'updated_at' => now(),
        ]);
         
        session()->forget('verified_recruiter');
        session()->forget('otp_value');
        session()->forget('otp_method');

        // if ($updated && $jobseeker && $jobseeker->email) {
        //     $toEmail = $jobseeker->email;
        //     $subject = "Password Changed Successfully";
        //     $body = "Dear " . ($jobseeker->name ?? 'User') . ",\n\nYour password has been successfully changed.\n\nIf this wasn't you, please contact our support immediately.\n\nThanks,\nTeam";

        //     Mail::raw($body, function ($message) use ($toEmail, $subject) {
        //         $message->to($toEmail)->subject($subject);
        //     });
        // }

        return redirect()->route('trainer.login')->with('success', 'Password change successfully.');
    } 

    // public function storeTrainerInformation(Request $request)
    // {
    //     $trainerId = session('trainer_id');

    //     if (!$trainerId) {
    //         return redirect()->route('trainer.signup')->with('error', 'Session expired. Please sign up again.');
    //     }

    //     $trainer = Trainers::find($trainerId);

    //     if (!$trainer) {
    //         return redirect()->route('trainer.signup')->with('error', 'trainer not found.');
    //     }

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:trainers,email,' . $trainer->id,
    //         'phone_number' => 'required|unique:trainers,phone_number,' . $trainer->id,
    //         'dob' => 'required|date',
    //         'city' => 'required|string|max:255',

    //         // // Education array validations
    //         'high_education' => 'required|string',
    //         'field_of_study' => 'nullable|string',
    //         'institution' => 'required|string',
    //         'graduate_year' => 'required|string',

    //         // Work experience array validations
    //         'job_role' => 'required|string',
    //         'organization' => 'required|string',
    //         'starts_from' => 'required|date',
    //         'end_to' => 'required|date|after_or_equal:starts_from',

    //         // skills validations
    //         'training_experience' => 'required|string',
    //         'training_skills' => 'required|string',
    //         'website_link' => 'required|url',
    //         'portfolio_link' => 'required|url',

    //          // Files
    //         // 'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
    //         // 'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     // Update jobseeker details
    //     $trainer->update([
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'phone_number' => $validated['phone_number'],
    //         'date_of_birth' => $validated['dob'],
    //         'city' => $validated['city'],
    //     ]);

    //     // Save education details
    //     foreach ($request->high_education as $index => $education) {
    //         EducationDetails::create([
    //             'user_id' => $trainer->id,
    //             'user_type' => 'trainer',
    //             'high_education' => $education,
    //             'field_of_study' => $request->field_of_study[$index] ?? null,
    //             'institution' => $request->institution[$index],
    //             'graduate_year' => $request->graduate_year[$index],
    //         ]);
    //     }

    //     // Save work experiences
    //     if ($request->has('job_role')) {
    //         foreach ($request->job_role as $index => $role) {
    //             WorkExperience::create([
    //                 'user_id' => $trainer->id,
    //                 'user_type' => 'trainer',
    //                 'job_role' => $role,
    //                 'organization' => $request->organization[$index] ?? null,
    //                 'starts_from' => $request->starts_from[$index] ?? null,
    //                 'end_to' => $request->end_to[$index] ?? null,
    //             ]);
    //         }
    //     }

    //     // Save skills
    //     TrainingExperience::create([
    //         'user_id ' => $trainer->id,
    //         'user_type ' => 'trainer',
    //         'training_experience' => $request->training_experience,
    //         'training_skills' => $request->training_skills,
    //         'website_link' => $request->website_link,
    //         'portfolio_link' => $request->portfolio_link,
    //     ]);

    //     // Upload Resume
    //     // if ($request->hasFile('resume')) {
    //     //     $existingResume = AdditionalInfo::where('user_id', $jobseeker->id)
    //     //         ->where('user_type', 'jobseeker')
    //     //         ->where('doc_type', 'resume')
    //     //         ->first();

    //     //     if (!$existingResume) {
    //     //         $resumeName = $request->file('resume')->getClientOriginalName();
    //     //         $fileNameToStoreResume = 'resume_' . time() . '.' . $request->file('resume')->getClientOriginalExtension();
    //     //         $request->file('resume')->move('uploads/', $fileNameToStoreResume);

    //     //         AdditionalInfo::create([
    //     //             'user_id'       => $jobseeker->id,
    //     //             'user_type'     => 'jobseeker',
    //     //             'doc_type'      => 'resume',
    //     //             'document_name' => $resumeName,
    //     //             'document_path' => asset('uploads/' . $fileNameToStoreResume),
    //     //         ]);
    //     //     }
    //     // }

    //     // Upload Profile Picture
    //     // if ($request->hasFile('profile_picture')) {
    //     //     $existingProfile = AdditionalInfo::where('user_id', $jobseeker->id)
    //     //         ->where('user_type', 'jobseeker')
    //     //         ->where('doc_type', 'profile_picture')
    //     //         ->first();

    //     //     if (!$existingProfile) {
    //     //         $profileName = $request->file('profile_picture')->getClientOriginalName();
    //     //         $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
    //     //         $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

    //     //         AdditionalInfo::create([
    //     //             'user_id'       => $jobseeker->id,
    //     //             'user_type'     => 'jobseeker',
    //     //             'doc_type'      => 'profile_picture',
    //     //             'document_name' => $profileName,
    //     //             'document_path' => asset('uploads/' . $fileNameToStoreProfile),
    //     //         ]);
    //     //     }
    //     // }

    //     session()->forget('trainer_id');
    //     return redirect()->route('trainer.login')->with('success_popup', true);
    // }

    public function storeTrainerInformation(Request $request)
    {
        $trainerId = session('trainer_id');

        if (!$trainerId) {
            return redirect()->route('trainer.signup')->with('error', 'Session expired. Please sign up again.');
        }

        $trainer = Trainers::find($trainerId);

        if (!$trainer) {
            return redirect()->route('trainer.signup')->with('error', 'Trainer not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'phone_number' => 'required|unique:trainers,phone_number,' . $trainer->id,
            'dob' => 'required|date',
            'city' => 'required|string|max:255',

            // Education fields
            'high_education.*' => 'required|string',
            'field_of_study.*' => 'nullable|string',
            'institution.*' => 'required|string',
            'graduate_year.*' => 'required|string',

            // Work experience fields
            'job_role.*' => 'required|string',
            'organization.*' => 'required|string',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'required|date|after_or_equal:starts_from.*',

            // Skills fields
            'training_experience' => 'required|string',
            'training_skills' => 'required|string',
            'website_link' => 'required|url',
            'portfolio_link' => 'required|url',

            // Files
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'training_certificate' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Update trainer profile
        $trainer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'date_of_birth' => $validated['dob'],
            'city' => $validated['city'],
        ]);

        // Save multiple education entries
        foreach ($request->high_education as $index => $education) {
            EducationDetails::create([
                'user_id' => $trainer->id,
                'user_type' => 'trainer',
                'high_education' => $education,
                'field_of_study' => $request->field_of_study[$index] ?? null,
                'institution' => $request->institution[$index],
                'graduate_year' => $request->graduate_year[$index],
            ]);
        }

        // Save multiple work experiences
        foreach ($request->job_role as $index => $role) {
            WorkExperience::create([
                'user_id' => $trainer->id,
                'user_type' => 'trainer',
                'job_role' => $role,
                'organization' => $request->organization[$index],
                'starts_from' => $request->starts_from[$index],
                'end_to' => $request->end_to[$index],
            ]);
        }

        // Save training experience and skills
        TrainingExperience::create([
            'user_id' => $trainer->id,
            'user_type' => 'trainer',
            'training_experience' => $request->training_experience,
            'training_skills' => $request->training_skills,
            'website_link' => $request->website_link,
            'portfolio_link' => $request->portfolio_link,
        ]);

        // Upload Resume
        if ($request->hasFile('resume')) {
            $existingResume = AdditionalInfo::where('user_id', $trainer->id)
                ->where('user_type', 'trainer')
                ->where('doc_type', 'resume')
                ->first();

            if (!$existingResume) {
                $resumeName = $request->file('resume')->getClientOriginalName();
                $fileNameToStoreResume = 'resume_' . time() . '.' . $request->file('resume')->getClientOriginalExtension();
                $request->file('resume')->move('uploads/', $fileNameToStoreResume);

                AdditionalInfo::create([
                    'user_id'       => $trainer->id,
                    'user_type'     => 'trainer',
                    'doc_type'      => 'resume',
                    'document_name' => $resumeName,
                    'document_path' => asset('uploads/' . $fileNameToStoreResume),
                ]);
            }
        }

        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {
            $existingProfile = AdditionalInfo::where('user_id', $trainer->id)
                ->where('user_type', 'trainer')
                ->where('doc_type', 'profile_picture')
                ->first();

            if (!$existingProfile) {
                $profileName = $request->file('profile_picture')->getClientOriginalName();
                $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

                AdditionalInfo::create([
                    'user_id'       => $trainer->id,
                    'user_type'     => 'trainer',
                    'doc_type'      => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            }
        }

        // Upload training certificate
        if ($request->hasFile('training_certificate')) {
            $existingCertificate = AdditionalInfo::where('user_id', $trainer->id)
                ->where('user_type', 'trainer')
                ->where('doc_type', 'training_certificate')
                ->first();

            if (!$existingCertificate) {
                $certificateName = $request->file('training_certificate')->getClientOriginalName();
                $fileNameToStoreCertificate = 'training_certificate' . time() . '.' . $request->file('training_certificate')->getClientOriginalExtension();
                $request->file('training_certificate')->move('uploads/', $fileNameToStoreCertificate);

                AdditionalInfo::create([
                    'user_id'       => $trainer->id,
                    'user_type'     => 'trainer',
                    'doc_type'      => 'training_certificate',
                    'document_name' => $certificateName,
                    'document_path' => asset('uploads/' . $fileNameToStoreCertificate),
                ]);
            }
        }
        // Clear session and redirect
        session()->forget('trainer_id');

        return redirect()->route('trainer.login')->with('success_popup', true);
    }

    public function loginTrainer(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $trainer = Trainers::where('email', $request->email)->first();

        if (!$trainer) {
            // Email does not exist
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }

        if ($trainer->status !== 'active') {
            // Status is inactive or blocked
            session()->flash('error', 'Your account is inactive. Please contact administrator.');
            return back()->withInput($request->only('email'));
        }

        // Now attempt login only if status is active
        if (Auth::guard('trainer')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // return view('site.trainer.trainer-dashboard');
            return redirect()->route('trainer.dashboard');
        } else {
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }
    }

    public function showTrainerDashboard()
    {
        return view('site.trainer.trainer-dashboard');    
    }

    public function logoutTrainer(Request $request)
    {
        Auth::guard('trainer')->logout();
        
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect()->route('trainer.login')->with('success', 'Logged out successfully');
    }


    public function addTraining() {
        return view('site.trainer.add-training');
    }

    public function assessmentList() {
        return view('site.trainer.assessment-list');
    }

    public function addAssessment() {
        return view('site.trainer.add-assessment');
    }

    public function batch() {
        return view('site.trainer.batch');
    }

    public function traineesJobseekers() {
        return view('site.trainer.trainees-jobseekers');
    }

    public function chatWithJobseeker() {
        return view('site.trainer.chat-with-jobseeker');
    }

    public function reviews() {
        return view('site.trainer.reviews');
    }

    public function trainerSettings() {
        return view('site.trainer.trainer-settings');
    }

    public function addOnlineTraining() {
        return view('site.trainer.add-online-training');
    }

    public function addRecordedTraining() {
        return view('site.trainer.add-recorded-course');
    }
      
    public function saveTrainingRecorededData(Request $request)
    {
        $trainer = auth()->user();

        $request->validate([
            'training_title' => 'required|string|max:255',
            'training_sub_title' => 'required|string|max:255',
            'training_descriptions' => 'nullable|string',
            'training_category' => 'required|string', // radio button returns single value
            'training_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'content_sections' => 'array',
            'content_sections.*.title' => 'required|string|max:255',
            'content_sections.*.description' => 'required|string',
            'content_sections.*.file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mov,avi,mkv|max:51200',

        ]);

        // Handle course thumbnail
        $thumbnailFilePath = null;
        $thumbnailFileName = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $thumbnailFileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $thumbnailFileName);
            $thumbnailFilePath = asset('uploads/' . $thumbnailFileName);
        }

        // Save course
        $trainingId = \DB::table('training_materials')->insertGetId([
            'trainer_id'             => $trainer->id,
            'training_type'          => 'recorded',
            'training_title'         => $request->training_title,
            'training_sub_title'     => $request->training_sub_title,
            'training_descriptions'  => $request->training_descriptions,
            'training_category'      => $request->training_category,
            'training_price'         => $request->training_price,
            'thumbnail_file_path'    => $thumbnailFilePath,
            'thumbnail_file_name'    => $thumbnailFileName,
            'training_objective'     => null,
            'session_type'           => null,
            'admin_status'           => 'pending',
            'rejection_reason'       => null,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        // Handle content sections
        if ($request->has('content_sections')) {
            foreach ($request->content_sections as $index => $section) {
                $filePath = null;
                $fileName = null;

                if (isset($section['file']) && $section['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $uploadedFile = $section['file'];
                    $fileName = 'section_' . time() . '_' . $index . '.' . $uploadedFile->getClientOriginalExtension();
                    $uploadedFile->move(public_path('uploads'), $fileName);
                    $filePath = asset('uploads/' . $fileName);
                }

                \DB::table('training_materials_documents')->insert([
                    'trainer_id' => $trainer->id,
                    'training_material_id' => $trainingId,
                    'training_title' => $section['title'],
                    'description' => $section['description'],
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

       return redirect()->route('training.list')->with('success', 'Recorded Training course saved successfully.');
    }



    public function saveTrainingOnlineData(Request $request)
    {
        $trainer = auth()->user();
        $request->validate([
            'training_title'         => 'required|string|max:255',
            'training_sub_title'     => 'nullable|string|max:255',
            'training_objective'     => 'nullable|string',
            'training_descriptions'  => 'nullable|string',
            'training_category'      => 'required|string',
            'training_price'         => 'required|numeric',
            'thumbnail'              => 'nullable|image|max:2048',

            'content_sections.*.batch_no'   => 'required|string|max:255',
            'content_sections.*.batch_date' => 'required|date',
            'content_sections.*.start_time' => 'required|string',
            'content_sections.*.end_time'   => 'required|string',
            'content_sections.*.duration'   => 'required|string',
        ]);

        $thumbnailFilePath = null;
        $thumbnailFileName = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $thumbnailFileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $thumbnailFileName);
            $thumbnailFilePath = asset('uploads/' . $thumbnailFileName);
        }

        $trainingId = \DB::table('training_materials')->insertGetId([
            'trainer_id'             => $trainer->id, // if you're saving trainer ID
            'training_title'         => $request->training_title,
            'training_sub_title'     => $request->training_sub_title,
            'training_objective'     => $request->training_objective,
            'training_descriptions'  => $request->training_descriptions,
            'training_price'         => $request->training_price,
            'training_type'          => 'online',
            'session_type'           => $request->training_category,
            'thumbnail_file_name'    => $thumbnailFileName,
            'thumbnail_file_path'    => $thumbnailFilePath,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        foreach ($request->input('content_sections', []) as $section) {
            \DB::table('training_batches')->insert([
                'trainer_id'           => $trainer->id, 
                'training_material_id' => $trainingId, 
                'batch_no'             => $section['batch_no'],
                'start_date'           => $section['batch_date'],
                'start_timing'         => date("H:i", strtotime($section['start_time'])), 
                'end_timing'           => date("H:i", strtotime($section['end_time'])), 
                'duration'             => $section['duration'],
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);

        }

        return redirect()->route('training.list')->with('success', 'Training and batch data saved successfully.');
    }

    public function trainingList(Request $request) {
        $trainer_id = auth()->id();

        $recordedTrainings = TrainingMaterial::where('trainer_id', $trainer_id)
            ->where('training_type', 'recorded')
            ->get();
        
        $onlineTrainings = TrainingMaterial::where('trainer_id', $trainer_id)
            ->where('session_type', 'online')
            ->get();
        
        $offlineTrainings = TrainingMaterial::where('trainer_id', $trainer_id)
            ->where('session_type', 'classroom') // or 'Offline'
            ->get();
        
        $activeTab = $request->get('tab', 'recorded'); 

        return view('site.trainer.training-list', compact(
            'recordedTrainings', 'onlineTrainings', 'offlineTrainings', 'activeTab'
        ));

    }


    public function editRecordedTraining($id)
    {
        $training = TrainingMaterial::findOrFail($id);

        $contentSections = TrainingMaterialsDocument::where('training_material_id', $id)
            ->select([
                'training_title as title',
                'description',
                'file_name',
                'file_path'
            ])
            ->get()
            ->toArray();
            
        return view('site.trainer.edit-recorded-course', compact('training', 'contentSections'));
    }

    public function updateRecordedTraining(Request $request, $id)
    {
        
        $data = $request->validate([
            'training_title' => 'required',
            'training_sub_title' => 'required',
            'training_descriptions' => 'nullable',
            'training_category' => 'required',
            'training_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'content_sections.*.document_id' => 'required|exists:training_materials_documents,id',
            'content_sections.*.title' => 'required',
            'content_sections.*.description' => 'required',
            'content_sections.*.file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Update training material
        $training = TrainingMaterial::findOrFail($id);
        $training->training_title = $data['training_title'];
        $training->training_sub_title = $data['training_sub_title'];
        $training->training_descriptions = $data['training_descriptions'];
        $training->training_category = $data['training_category'];
        $training->training_price = $data['training_price'];

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $name = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $name, 'public');
            $training->thumbnail_file_name = $name;
            $training->thumbnail_file_path = asset('storage/' . $path);
        }
       
        $training->save();

        // Update existing documents only if they belong to this training
        foreach ($data['content_sections'] as $section) {
            $doc = TrainingMaterialsDocument::where('id', $section['document_id'])
                ->where('training_material_id', $training->id)
                ->first();

            if (!$doc) continue; // Skip if document does not belong to this training

            $doc->training_title = $section['title'];
            $doc->description = $section['description'];

            if (!empty($section['file'])) {
                $file = $section['file'];
                $name = 'section_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads', $name, 'public');
                $doc->file_name = $name;
                $doc->file_path = asset('storage/' . $path);
            }

            $doc->save(); // Only update
        }

        return redirect()->route('training.list')->with('success', 'Recorded Training course updated successfully!');
    }



}
