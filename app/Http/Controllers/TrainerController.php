<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\Trainers;
use App\Models\TrainingExperience;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\AdditionalInfo;
use App\Models\TrainerAssessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentOption;
use App\Models\TrainingMaterial;
use App\Models\TrainingBatch;
use App\Models\TrainingMaterialsDocument;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use App\Models\Mentors;
use App\Models\Assessors;

use App\Services\ZoomService;

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

    public function resendOtp(Request $request)
    {
        $contact = session('otp_value');
        $contactMethod = session('otp_method');

        if (!$contact || !$contactMethod) {
            return response()->json(['message' => 'Session expired. Please try again.'], 400);
        }

        $otp = rand(100000, 999999);

        // Save new OTP in database
        DB::table('trainers')->where($contactMethod, $contact)->update([
            'otp' => $otp,
            'updated_at' => now()
        ]);

        // === OTP sending is disabled for now ===
        if ($contactMethod === 'email') {
            // Mail::html(view('emails.otp', compact('otp'))->render(), function ($message) use ($contact) {
            //     $message->to($contact)->subject('Your OTP has been resent – Talentrek');
            // });
        } else {
            // SmsService::send($contact, "Your OTP is: $otp");
        }

        return response()->json(['message' => 'OTP resent successfully.']);
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
            return redirect()->route('trainer.forget.password')->withErrors(['session' => 'Session expired. Please try again.']);
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

    
  

    public function storeTrainerInformation(Request $request)
    {
        $trainerId = session('trainer_id');

        if (!$trainerId) {
            return redirect()->route('trainer.signup')->with('error', 'Session expired. Please sign up again.');
        }

        $trainer = Trainers::findOrFail($trainerId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $trainer->id,
            'phone_number' => 'required|unique:trainers,phone_number,' . $trainer->id,
            'dob' => 'required|date',
            'city' => 'required|string|max:255',
            'national_id' => [
                'required',
                'min:10',
                function ($attribute, $value, $fail) use ($trainer) {
                    $existsInRecruiters = Recruiters::where('national_id', $value)->exists();
                    $existsInTrainers = Trainers::where('national_id', $value)->where('id', '!=', $trainer->id)->exists();
                    $existsInJobseekers = Jobseekers::where('national_id', $value)->exists();
                    $existsInMentors = Mentors::where('national_id', $value)->exists();
                    $existsInAssessors = Assessors::where('national_id', $value)->exists();

                    if ($existsInRecruiters || $existsInTrainers || $existsInJobseekers || $existsInMentors || $existsInAssessors) {
                        $fail('The national ID has already been taken.');
                    }
                },
            ],
            'high_education.*' => 'required|string',
            'field_of_study.*' => 'nullable|string',
            'institution.*' => 'required|string',
            'graduate_year.*' => 'required|string',
            'job_role.*' => 'required|string',
            'organization.*' => 'required|string',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'required|date',
            'training_experience' => 'required|string',
            'training_skills' => 'required|string',
            'website_link' => 'required|url',
            'portfolio_link' => 'required|url',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'training_certificate' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ],
            [
                // Custom error messages
                'name.required' => 'Please enter your full name.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email is already registered.',
                'phone_number.required' => 'Phone number is required.',
                'phone_number.unique' => 'This phone number is already in use.',
                'dob.required' => 'Date of birth is required.',
                'dob.date' => 'Invalid date format for date of birth.',
                'city.required' => 'City is required.',
                'national_id.required' => 'National ID is required.',
                'national_id.min' => 'National ID must be at least 10 digits.',

                'high_education.*.required' => 'Please enter your highest education.',
                'institution.*.required' => 'Institution name is required.',
                'graduate_year.*.required' => 'Graduation year is required.',

                'job_role.*.required' => 'Job role is required.',
                'organization.*.required' => 'Organization name is required.',
                'starts_from.*.required' => 'Start date is required.',
                'starts_from.*.date' => 'Invalid start date format.',
                'end_to.*.required' => 'End date is required.',
                'end_to.*.date' => 'Invalid end date format.',

                'training_experience.required' => 'Training experience is required.',
                'training_skills.required' => 'Please specify your training skills.',
                'website_link.required' => 'Website link is required.',
                'website_link.url' => 'Please provide a valid website URL.',
                'portfolio_link.required' => 'Portfolio link is required.',
                'portfolio_link.url' => 'Please provide a valid portfolio URL.',

                'resume.required' => 'Please upload your resume.',
                'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
                'resume.max' => 'Resume file must not exceed 2MB.',
                'profile_picture.required' => 'Profile picture is required.',
                'profile_picture.image' => 'Profile picture must be an image.',
                'profile_picture.mimes' => 'Allowed image types are JPG, JPEG, and PNG.',
                'profile_picture.max' => 'Profile picture must not exceed 2MB.',
                'training_certificate.required' => 'Training certificate is required.',
                'training_certificate.mimes' => 'Certificate must be a PDF, DOC, or DOCX file.',
                'training_certificate.max' => 'Certificate file must not exceed 2MB.',
            ]);

        DB::transaction(function () use ($request, $trainer, $validated) {
            // Update trainer
            $trainer->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'date_of_birth' => $validated['dob'],
                'city' => $validated['city'],
                'national_id' => $validated['national_id'],
            ]);

            // Save education
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

            // Save work experience
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

            // Save training experience
            TrainingExperience::create([
                'user_id' => $trainer->id,
                'user_type' => 'trainer',
                'training_experience' => $request->training_experience,
                'training_skills' => $request->training_skills,
                'website_link' => $request->website_link,
                'portfolio_link' => $request->portfolio_link,
            ]);

            // File uploads
            $uploadTypes = [
                'resume' => 'resume',
                'profile_picture' => 'profile_picture',
                'training_certificate' => 'training_certificate',
            ];

            foreach ($uploadTypes as $field => $docType) {
                if ($request->hasFile($field)) {
                    $existing = AdditionalInfo::where([
                        ['user_id', $trainer->id],
                        ['user_type', 'trainer'],
                        ['doc_type', $docType],
                    ])->first();

                    if (!$existing) {
                        $originalName = $request->file($field)->getClientOriginalName();
                        $extension = $request->file($field)->getClientOriginalExtension();
                        $filename = $docType . '_' . time() . '.' . $extension;
                        $request->file($field)->move('uploads/', $filename);

                        AdditionalInfo::create([
                            'user_id' => $trainer->id,
                            'user_type' => 'trainer',
                            'doc_type' => $docType,
                            'document_name' => $originalName,
                            'document_path' => asset('uploads/' . $filename),
                        ]);
                    }
                }
            }
        });

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


    public function assessmentStore(Request $request)
    {
        $request->merge([
            'questions' => json_decode($request->input('questions'), true)
        ]);

        $validator = Validator::make($request->all(), [
            'trainer_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|string|max:100',
            'total_questions' => 'required|integer|min:1',
            'passing_questions' => 'required|integer|min:1',
            'passing_percentage' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $assessment = TrainerAssessment::create([
                'assessment_title' => $validated['title'],
                'assessment_description' => $validated['description'],
                'assessment_level' => $validated['level'],
                'total_questions' => $validated['total_questions'],
                'passing_questions' => $validated['passing_questions'],
                'passing_percentage' => $validated['passing_percentage'],
                'trainer_id' => auth()->id(),
                'material_id' => null,
            ]);

            foreach ($validated['questions'] as $questionData) {
                $question = AssessmentQuestion::create([
                    'trainer_id' => auth()->id(),
                    'assessment_id' => $assessment->id,
                    'questions_title' => $questionData['text'],
                ]);

                foreach ($questionData['options'] as $option) {
                    AssessmentOption::create([
                        'trainer_id' => auth()->id(),
                        'assessment_id' => $assessment->id,
                        'question_id' => $question->id,
                        'options' => $option['text'],
                        'correct_option' => $option['correct'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('assessment.list')->with('success', 'Assessment created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. ' . $e->getMessage());
        }
    }

    public function addTraining() {
        return view('site.trainer.add-training');
    }

    public function assessmentList() {
        $assessments = TrainerAssessment::where('trainer_id', auth()->id())->get();
        $courses = TrainingMaterial::where('trainer_id', auth()->id())->get();
        return view('site.trainer.assessment-list', compact('assessments','courses'));
    }

    public function assignCourse(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:trainer_assessments,id',
            'course_id' => 'required|exists:training_materials,id',
        ]);

        $assessment = TrainerAssessment::find($request->assessment_id);
        $assessment->material_id = $request->course_id; // Assuming you have a `course_id` column
        $assessment->save();

        return response()->json(['success' => true, 'message' => 'Course assigned successfully']);
    }
    public function addAssessment() {
        return view('site.trainer.add-assessment');
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
            'training_category' => 'required|string',
            'training_level' => 'required|string',
            'training_price' => 'required|numeric',
            'training_offer_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'content_sections' => 'array',
            'content_sections.*.title' => 'required|string|max:255',
            'content_sections.*.description' => 'required|string',
            'content_sections.*.file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mov,avi,mkv|max:51200',
            'content_sections.*.file_duration' => 'required|string|max:255',

        ], [
            // Optional custom messages (if needed)
            'training_title.required' => 'Please enter the training title.',
            'training_sub_title.required' => 'Please enter the training subtitle.',
            'training_category.required' => 'Please select a training category.',
            'training_level.required' => 'Please select the training level.',
            'training_price.required' => 'Please enter the training price.',
            'training_offer_price.required' => 'Please enter the offer price.',
            'content_sections.*.title.required' => 'Please enter the section title.',
            'content_sections.*.description.required' => 'Please enter the section description.',
            'content_sections.*.file_duration.required' => 'Please enter the section file_duration.',
        ], [
            //    Custom attribute names
            'training_title' => 'Training Title',
            'training_sub_title' => 'Training Subtitle',
            'training_descriptions' => 'Training Description',
            'training_category' => 'Training Category',
            'training_level' => 'Training Level',
            'training_price' => 'Training Price',
            'training_offer_price' => 'Training Offer Price',
            'thumbnail' => 'Thumbnail Image',

            'content_sections' => 'Content Sections',
            'content_sections.*.title' => 'Section Title',
            'content_sections.*.description' => 'Section Description',
            'content_sections.*.file' => 'Section File',
            'content_sections.*.file_duration' => 'Section file_duration',
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
        $trainingId = DB::table('training_materials')->insertGetId([
            'trainer_id'             => $trainer->id,
            'training_type'          => 'recorded',
            'training_title'         => $request->training_title,
            'training_sub_title'     => $request->training_sub_title,
            'training_descriptions'  => $request->training_descriptions,
            'training_category'      => $request->training_category,
            'training_level'         => $request->training_level,
            'training_price'         => $request->training_price,
            'training_offer_price'   => $request->training_offer_price,
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

                DB::table('training_materials_documents')->insert([
                    'trainer_id' => $trainer->id,
                    'training_material_id' => $trainingId,
                    'training_title' => $section['title'],
                    'description' => $section['description'],
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'file_duration' => $section['file_duration'],
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
            'training_sub_title'     => 'required|string|max:255',
            'training_objective'     => 'required|string',
            'training_descriptions'  => 'nullable|string',
            'training_category'      => 'required|string',
            'training_level'         => 'required|string',
            'training_price'         => 'required|numeric',
            'training_offer_price'   => 'required|numeric',
            'thumbnail'              => 'nullable|image|max:2048',

            'content_sections.*.batch_no'   => 'required|string|max:255',
            'content_sections.*.batch_date' => 'required|date',
            'content_sections.*.start_time' => 'required|string',
            'content_sections.*.end_time'   => 'required|string',
            'content_sections.*.duration'   => 'required|string',
        ], [
            // Main training fields
            'training_title.required'         => 'Please enter the training title.',
            'training_title.string'           => 'The training title must be a valid string.',
            'training_title.max'              => 'The training title may not be greater than 255 characters.',

            'training_sub_title.required'     => 'Please enter the training subtitle.',
            'training_sub_title.string'       => 'The training subtitle must be a valid string.',
            'training_sub_title.max'          => 'The training subtitle may not be greater than 255 characters.',

            'training_objective.required'     => 'Please enter the training objective.',
            'training_objective.string'       => 'The training objective must be a valid string.',

            'training_descriptions.string'    => 'The training description must be a valid string.',

            'training_category.required'      => 'Please select a training category.',
            'training_category.string'        => 'The training category must be a valid string.',

            'training_level.required'         => 'Please select the training level.',
            'training_level.string'           => 'The training level must be a valid string.',

            'training_price.required'         => 'Please enter the training price.',
            'training_price.numeric'          => 'The training price must be a number.',

            'training_offer_price.required'   => 'Please enter the offer price.',
            'training_offer_price.numeric'    => 'The offer price must be a number.',

            'thumbnail.image'                 => 'The thumbnail must be an image file.',
            'thumbnail.max'                   => 'The thumbnail may not be larger than 2MB.',

            // Content section fields
            'content_sections.*.batch_no.required'   => 'Please enter the batch number.',
            'content_sections.*.batch_no.string'     => 'Batch number must be a valid string.',
            'content_sections.*.batch_no.max'        => 'Batch number may not exceed 255 characters.',

            'content_sections.*.batch_date.required' => 'Please enter the batch date.',
            'content_sections.*.batch_date.date'     => 'The batch date must be a valid date.',

            'content_sections.*.start_time.required' => 'Please enter the start time.',
            'content_sections.*.start_time.string'   => 'Start time must be a valid string.',

            'content_sections.*.end_time.required'   => 'Please enter the end time.',
            'content_sections.*.end_time.string'     => 'End time must be a valid string.',

            'content_sections.*.duration.required'   => 'Please enter the duration.',
            'content_sections.*.duration.string'     => 'Duration must be a valid string.',
        ]);


        $thumbnailFilePath = null;
        $thumbnailFileName = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $thumbnailFileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $thumbnailFileName);
            $thumbnailFilePath = asset('uploads/' . $thumbnailFileName);
        }

        $trainingId = DB::table('training_materials')->insertGetId([
            'trainer_id'             => $trainer->id, // if you're saving trainer ID
            'training_title'         => $request->training_title,
            'training_sub_title'     => $request->training_sub_title,
            'training_objective'     => $request->training_objective,
            'training_descriptions'  => $request->training_descriptions,
            'training_level'         => $request->training_level,
            'training_price'         => $request->training_price,
            'training_offer_price'   => $request->training_offer_price,
            'training_type'          => 'online',
            'session_type'           => $request->training_category,
            'thumbnail_file_name'    => $thumbnailFileName,
            'thumbnail_file_path'    => $thumbnailFilePath,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        foreach ($request->input('content_sections', []) as $section) {
            // Initialize ZoomService
            $zoom = new ZoomService();

            // Combine date and time for Zoom meeting start
            $startTime = $section['batch_date'] . ' ' . $section['start_time'];

            // Create Zoom meeting
            $zoomMeeting = $zoom->createMeeting("Training Batch #{$section['batch_no']}", $startTime);

            if (!$zoomMeeting) {
                return redirect()->back()->with('error', 'Zoom meeting creation failed for batch ' . $section['batch_no']);
            }

            // Save the training batch along with Zoom URLs
            DB::table('training_batches')->insert([
                'trainer_id'           => $trainer->id,
                'training_material_id' => $trainingId,
                'batch_no'             => $section['batch_no'],
                'start_date'           => $section['batch_date'],
                'start_timing'         => date("H:i", strtotime($section['start_time'])),
                'end_timing'           => date("H:i", strtotime($section['end_time'])),
                'duration'             => $section['duration'],
                'zoom_start_url'       => $zoomMeeting['start_url'],
                'zoom_join_url'        => $zoomMeeting['join_url'],
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
                'id as document_id',
                'training_title as title',
                'description',
                'file_name',
                'file_path',
                'file_duration',
            ])
            ->get()
            ->toArray();
        //dd( $contentSections );exit;        
        return view('site.trainer.edit-recorded-course', compact('training', 'contentSections'));
    }


    public function updateRecordedTraining(Request $request, $id)
    {
        $data = $request->validate([
            'training_title' => 'required|string|max:255',
            'training_sub_title' => 'required|string|max:255',
            'training_descriptions' => 'nullable|string',
            'training_category' => 'required|string',
            'training_level' => 'required|string',
            'training_price' => 'required|numeric',
            'training_offer_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'content_sections' => 'array',
            'content_sections.*.title' => 'required|string|max:255',
            'content_sections.*.description' => 'required|string',
            'content_sections.*.file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mov,avi,mkv|max:51200',
            'content_sections.*.file_duration' => 'required|string|max:255',

        ], [
            // Optional custom messages (if needed)
            'training_title.required' => 'Please enter the training title.',
            'training_sub_title.required' => 'Please enter the training subtitle.',
            'training_category.required' => 'Please select a training category.',
            'training_level.required' => 'Please select the training level.',
            'training_price.required' => 'Please enter the training price.',
            'training_offer_price.required' => 'Please enter the offer price.',
            'content_sections.*.title.required' => 'Please enter the section title.',
            'content_sections.*.description.required' => 'Please enter the section description.',
            'content_sections.*.file_duration.required' => 'Please enter the section file_duration.',
        ], [
            //    Custom attribute names
            'training_title' => 'Training Title',
            'training_sub_title' => 'Training Subtitle',
            'training_descriptions' => 'Training Description',
            'training_category' => 'Training Category',
            'training_level' => 'Training Level',
            'training_price' => 'Training Price',
            'training_offer_price' => 'Training Offer Price',
            'thumbnail' => 'Thumbnail Image',

            'content_sections' => 'Content Sections',
            'content_sections.*.title' => 'Section Title',
            'content_sections.*.description' => 'Section Description',
            'content_sections.*.file' => 'Section File',
            'content_sections.*.file_duration' => 'Section file_duration',
        ]);

        $training = TrainingMaterial::findOrFail($id);
        $training->fill($data);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $name = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $name);
            $training->thumbnail_file_name = $name;
            $training->thumbnail_file_path = asset('uploads/' . $name);
        }

        $training->save();

        if (!empty($data['content_sections']) && is_array($data['content_sections'])) {
            $existingIds = TrainingMaterialsDocument::where('training_material_id', $id)->pluck('id')->toArray();
            $requestIds = [];

            foreach ($data['content_sections'] as $section) {
                if (!empty($section['document_id'])) {
                    $doc = TrainingMaterialsDocument::where('id', $section['document_id'])
                        ->where('training_material_id', $training->id)
                        ->first();

                    if ($doc) {
                        $doc->training_title = $section['title'];
                        $doc->description = $section['description'];

                        if (!empty($section['file']) && $section['file'] instanceof \Illuminate\Http\UploadedFile) {
                            $file = $section['file'];
                            $name = 'section_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs('uploads', $name, 'public');
                            $doc->file_name = $name;
                            $doc->file_path = asset('storage/' . $path);
                        } else {
                            //  Preserve existing file if no new upload
                            $doc->file_name = $section['existing_file_name'] ?? $doc->file_name;
                            $doc->file_path = $section['existing_file_path'] ?? $doc->file_path;
                        }

                        $doc->save();
                        $requestIds[] = $doc->id;
                    }
                }
                else {
                    $doc = new TrainingMaterialsDocument();
                    $doc->training_material_id = $training->id;
                    $doc->trainer_id = auth()->id();
                    $doc->training_title = $section['title'];
                    $doc->description = $section['description'];

                    if (!empty($section['file']) && $section['file'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $section['file'];
                        $name = 'section_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('uploads', $name, 'public');
                        $doc->file_name = $name;
                        $doc->file_path = asset('storage/' . $path);
                    } else {
                        //  Preserve old file (if passed by hidden input — useful when editing a just-added section)
                        $doc->file_name = $section['existing_file_name'] ?? null;
                        $doc->file_path = $section['existing_file_path'] ?? null;
                    }

                    $doc->save();
                    $requestIds[] = $doc->id;
                }

            }

            $toDelete = array_diff($existingIds, $requestIds);
            if (!empty($toDelete)) {
                TrainingMaterialsDocument::whereIn('id', $toDelete)->delete();
            }
        }

        return redirect()->route('training.list')->with('success', 'Recorded training course updated successfully!');
    }


    public function editOnlineTraining($id)
    {
        // Get the training material by ID
        $training = TrainingMaterial::findOrFail($id);
        
        // Get all batches linked to this training material by ID
        $batches = TrainingBatch::where('training_material_id', $id)
            ->select([
                'id',
                'batch_no',
                'start_date',
                'start_timing',
                'end_timing',
                'duration'
            ])
            ->get();
       
        return view('site.trainer.edit-online-training', compact('training', 'batches'));
    }

    public function updateOnlineTraining(Request $request, $id)
    {
        $request->validate([
            'training_title'         => 'required|string|max:255',
            'training_sub_title'     => 'required|string|max:255',
            'training_objective'     => 'required|string',
            'training_descriptions'  => 'nullable|string',
            'training_category'      => 'required|string',
            'training_level'         => 'required|string',
            'training_price'         => 'required|numeric',
            'training_offer_price'   => 'required|numeric',
            'thumbnail'              => 'nullable|image|max:2048',

            'content_sections.*.batch_no'   => 'required|string|max:255',
            'content_sections.*.batch_date' => 'required|date',
            'content_sections.*.start_time' => 'required|string',
            'content_sections.*.end_time'   => 'required|string',
            'content_sections.*.duration'   => 'required|string',
        ], [
            // Main training fields
            'training_title.required'         => 'Please enter the training title.',
            'training_title.string'           => 'The training title must be a valid string.',
            'training_title.max'              => 'The training title may not be greater than 255 characters.',

            'training_sub_title.required'     => 'Please enter the training subtitle.',
            'training_sub_title.string'       => 'The training subtitle must be a valid string.',
            'training_sub_title.max'          => 'The training subtitle may not be greater than 255 characters.',

            'training_objective.required'     => 'Please enter the training objective.',
            'training_objective.string'       => 'The training objective must be a valid string.',

            'training_descriptions.string'    => 'The training description must be a valid string.',

            'training_category.required'      => 'Please select a training category.',
            'training_category.string'        => 'The training category must be a valid string.',

            'training_level.required'         => 'Please select the training level.',
            'training_level.string'           => 'The training level must be a valid string.',

            'training_price.required'         => 'Please enter the training price.',
            'training_price.numeric'          => 'The training price must be a number.',

            'training_offer_price.required'   => 'Please enter the offer price.',
            'training_offer_price.numeric'    => 'The offer price must be a number.',

            'thumbnail.image'                 => 'The thumbnail must be an image file.',
            'thumbnail.max'                   => 'The thumbnail may not be larger than 2MB.',

            // Content section fields
            'content_sections.*.batch_no.required'   => 'Please enter the batch number.',
            'content_sections.*.batch_no.string'     => 'Batch number must be a valid string.',
            'content_sections.*.batch_no.max'        => 'Batch number may not exceed 255 characters.',

            'content_sections.*.batch_date.required' => 'Please enter the batch date.',
            'content_sections.*.batch_date.date'     => 'The batch date must be a valid date.',

            'content_sections.*.start_time.required' => 'Please enter the start time.',
            'content_sections.*.start_time.string'   => 'Start time must be a valid string.',

            'content_sections.*.end_time.required'   => 'Please enter the end time.',
            'content_sections.*.end_time.string'     => 'End time must be a valid string.',

            'content_sections.*.duration.required'   => 'Please enter the duration.',
            'content_sections.*.duration.string'     => 'Duration must be a valid string.',
        ]);

        $training = TrainingMaterial::findOrFail($id);

        // Handle thumbnail if uploaded
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $training->thumbnail_file_path = url('uploads/' . $fileName);
            $training->thumbnail_file_name = $fileName;
        }


        // Update training fields
        $training->training_title = $request->training_title;
        $training->training_sub_title = $request->training_sub_title;
        $training->training_objective = $request->training_objective;
        $training->training_descriptions = $request->training_descriptions;
        $training->training_level = $request->training_level;
        $training->session_type = $request->training_category;
        $training->training_price = $request->training_price;
        $training->training_offer_price = $request->training_offer_price;
        $training->save();

        // Delete existing batches and insert new ones
        TrainingBatch::where('training_material_id', $id)->delete();

        if ($request->has('content_sections')) {
            foreach ($request->content_sections as $batch) {
                TrainingBatch::create([
                    'trainer_id' => auth()->id(),
                    'training_material_id' => $training->id,
                    'batch_no' => $batch['batch_no'],
                    'start_date' => $batch['batch_date'],
                    'start_timing' => $batch['start_time'],
                    'end_timing' => $batch['end_time'],
                    'duration' => $batch['duration'],
                ]);
            }
        }

        return redirect()->route('training.list')->with('success', 'Online Training course updated successfully!');
    }

    public function batch() 
    {
        $trainerId = auth()->id(); 
        
        $batches = TrainingBatch::where('trainer_id', $trainerId)
            ->with('trainingMaterial:id,id,session_type,training_title') 
            ->get();
       return view('site.trainer.batch', [
                'batches' => $batches,
            ]);

    }

    

    public function trainerReviews()
    {
        $reviews = Review::select(
                'reviews.id',
                'reviews.reviews',
                'reviews.ratings',
                'reviews.created_at',
                'jobseekers.name as jobseeker_name'
            )
            ->join('jobseekers', 'jobseekers.id', '=', 'reviews.jobseeker_id')
            ->where('reviews.user_type', 'trainer')
            ->get();

        return view('site.trainer.reviews', compact('reviews'));
    }

    public function deleteTrainerReview($id)
    {
        DB::table('reviews')
            ->where('id', $id)
            ->where('user_type', 'trainer')
            ->delete();

        return redirect()->route('trainer.reviews')->with('success', 'Trainer review deleted successfully.');
    }


    public function deleteAccount()
     {
          $trainerId = auth()->id();
          Trainers::where('id', $trainerId)->delete();
          //Recruiters::where('company_id', $trainerId)->delete();
          auth()->logout();

          return redirect()->route('trainer.login')->with('success', 'Your account has been deleted successfully.');
     }

     public function getTrainerAllDetails()
    {
        $trainer = Auth::guard('trainer')->user(); 
       
        $trainerId = $trainer->id;

        // Trainer basic details and skill details
        $trainerSkills = DB::table('trainers')
            ->leftJoin('training_experience', 'training_experience.user_id', '=', 'trainers.id')
            ->where('trainers.id', $trainerId)
            ->select('trainers.*', 'training_experience.*')
            ->first();
        
        // Education details (multiple)
        $educationDetails = DB::table('education_details')
            ->where('user_id', $trainerId)
            ->where('user_type', 'trainer')
            ->get();

        // Work experience (multiple)
        $workExperiences = DB::table('work_experience')
            ->where('user_id', $trainerId)
             ->where('user_type', 'trainer')
            ->get();

        $additonalDetails = DB::table('additional_info')
        ->where('user_id', $trainerId)
        ->where('user_type', 'trainer')
        ->get();

        return view('site.trainer.trainer-settings', compact(
            'trainerSkills',
            'educationDetails',
            'workExperiences',
            'additonalDetails'
        ));
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:jobseekers,email,' . $user->id,
            'phone' => 'required|digits:10',
            'dob' => 'required|date',
            'location' => 'required|string|max:255',
            'national_id' => [
                'required',
                'min:10',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value != $user->national_id) {
                        $existsInRecruiters = Recruiters::where('national_id', $value)->exists();
                        $existsInTrainers = Trainers::where('national_id', $value)->exists();
                        $existsInJobseekers = Jobseekers::where('national_id', $value)
                            ->where('id', '!=', $user->id)
                            ->exists();
                        $existsInMentors = Mentors::where('national_id', $value)->exists();
                        $existsInAssessors = Assessors::where('national_id', $value)->exists();

                        if ($existsInRecruiters || $existsInTrainers || $existsInJobseekers || $existsInMentors || $existsInAssessors) {
                            $fail('The national ID has already been taken.');
                        }    
                    }
                },
            ],

        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'],
            'date_of_birth' => $validated['dob'],
            'city' => $validated['location'],
            'national_id' => $validated['national_id'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Personal information updated successfully!',
        ]);
    }

    public function updateEducationInfo(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $validated = $request->validate([
            'high_education.*' => 'required|string|max:255',
            'field_of_study.*' => 'required|string|max:255',
            'institution.*' => 'required|string|max:255',
            'graduate_year.*' => 'required|string|max:255', 
        ]);

        $incomingIds = $request->input('education_id', []);

        $existingIds = EducationDetails::where('user_id', $userId)
                        ->where('user_type', 'trainer')
                        ->pluck('id')
                        ->toArray();

        $toDelete = array_diff($existingIds, $incomingIds);
        EducationDetails::whereIn('id', $toDelete)->delete();

        foreach ($request->input('high_education', []) as $i => $education) {
            $data = [
                'user_id' => $userId,
                'user_type' => 'trainer',
                'high_education' => $request->high_education[$i],
                'field_of_study' => $request->field_of_study[$i] ?? null,
                'institution' => $request->institution[$i] ?? null,
                'graduate_year' => $request->graduate_year[$i] ?? null,
            ];

            if (!empty($request->education_id[$i])) {
                EducationDetails::where('id', $request->education_id[$i])
                    ->update($data);
            } else {
                EducationDetails::create($data);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Education information saved successfully!']);

    }

    public function updateWorkExprienceInfo(Request $request)
    {
        $user_id = auth()->id();

        // Flattened validation
        $validated = $request->validate([
            'job_role.*' => 'required|string|max:255',
            'organization.*' => 'required|string|max:255',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'nullable|date',
            'currently_working' => 'nullable|array',
        ]);

        $workIds = $request->input('work_id', []);
        $existingIds = WorkExperience::where('user_id', $user_id)
                        ->where('user_type', 'trainer')
                        ->pluck('id')
                        ->toArray();

        // Delete entries not present in the submitted data
        $toDelete = array_diff($existingIds, $workIds);
        if (!empty($toDelete)) {
            WorkExperience::whereIn('id', $toDelete)->delete();
        }

        $currentlyWorkingIndices = $request->input('currently_working', []);

        foreach ($request->input('job_role', []) as $i => $role) {
            $currentlyWorking = in_array($i, $currentlyWorkingIndices);
            $startDate = $request->starts_from[$i] ?? null;
            $endDate = $currentlyWorking ? 'Work here' : ($request->end_to[$i] ?? null);

            // Manual validation: ensure end date is not before start date if not currently working
            if (!$currentlyWorking && $startDate && $endDate && $endDate < $startDate) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ["end_to.$i" => ["The end date must be after or equal to the start date."]]
                ], 422);
            }

            $data = [
                'user_id' => $user_id,
                'user_type' => 'trainer',
                'job_role' => $role,
                'organization' => $request->organization[$i] ?? null,
                'starts_from' => $startDate,
                'end_to' => $endDate,
            ];

            if (!empty($request->work_id[$i])) {
                WorkExperience::where('id', $request->work_id[$i])->update($data);
            } else {
                WorkExperience::create($data);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Work Experience information saved successfully!'
        ]);
    }


    public function updateTrainerSkillsInfo(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;

        $validated = $request->validate([
            'training_experience' => 'required|string',
            'training_skills' => 'required|string',
            'website_link' => 'required|url',
            'portfolio_link' => 'required|url',
        ]);

        $skills = TrainingExperience::where('user_id', $user_id)
            ->where('user_type', 'trainer')
            ->first();

        if ($skills) {
            $skills->update([
                'training_experience' => $validated['training_experience'] ?? null,
                'training_skills' => $validated['training_skills'] ?? null,
                'website_link' => $validated['website_link'] ?? null,
                'portfolio_link' => $validated['portfolio_link'] ?? null,
            ]);
        } else {
            TrainingExperience::create([
                'user_id' => $user_id,
                'user_type' => 'trainer',
                'training_experience' => $validated['training_experience'] ?? null,
                'training_skills' => $validated['training_skills'] ?? null,
                'website_link' => $validated['website_link'] ?? null,
                'portfolio_link' => $validated['portfolio_link'] ?? null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Trainer skills updated successfully!',
        ]);
    }

    public function updateAdditionalInfo(Request $request)
    {
        $userId = auth()->id();
        
        // Validate all 3 possible uploads
        $validated = $request->validate([
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'training_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Loop over each type
        foreach (['resume', 'profile', 'training_certificate'] as $type) {
            if ($request->hasFile($type)) {
                $file = $request->file($type);
                $fileName = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $path = asset('uploads/' . $fileName);

                AdditionalInfo::updateOrCreate(
                    ['user_id' => $userId, 'user_type' => 'trainer', 'doc_type' => $type],
                    ['document_path' => $path, 'document_name' => $fileName]
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Trainer documents updated successfully!'
        ]);
    }


    public function deleteAdditionalFile($type)
    {
        $userId = auth()->id();

        $file = AdditionalInfo::where('user_id', $userId)->where('doc_type', $type)->first();

        if ($file) {
            $publicPath = str_replace(asset('') . '/', '', $file->document_path);
            $filePath = public_path($publicPath);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file->delete();

            return response()->json([
                'status' => 'success',
                'message' => ucfirst(str_replace('_', ' ', $type)) . ' deleted successfully.',
                'type' => $type
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => ucfirst(str_replace('_', ' ', $type)) . ' not found.'
        ], 404);
    }
   

}
