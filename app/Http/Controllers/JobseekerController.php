<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Log;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\Trainers;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentOption;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\Skills;
use App\Models\Coupon;
use App\Models\JobseekerAssessmentStatus;
use App\Models\JobseekerAssessmentData;
use App\Models\Mentors;
use App\Models\TrainerAssessment;
use App\Models\Assessors;
use App\Models\Coach;
use App\Models\Admin;
use App\Models\SubscriptionPlan;
use App\Models\PurchasedSubscription;
use App\Models\BookingSession;
use App\Models\BookingSlot;
use App\Models\JobseekerTrainingMaterialPurchase;
use App\Models\TrainingMaterial;
use App\Models\AdditionalInfo;
use App\Models\JobseekerCartItem;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Carbon\CarbonInterval;
use App\Services\ZoomService;
use App\Services\PaymentHelper;

use App\Events\NotificationSent;



use App\Models\CertificateTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;

use App\Models\JobseekerTrainingAssessmentTime;



class JobseekerController extends Controller
{
    public function showRegistrationForm()
    {
        return view('site.jobseeker.registration');
    }


    public function postRegistration(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:jobseekers,email',
            'phone_number' => 'required|unique:jobseekers,phone_number',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);

        $jobseeker = Jobseekers::create([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'pass' => $request->password, // Only for development
        ]);

        // Send welcome email
            Mail::html('
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Welcome to Talentrek</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f6f8fa;
                            margin: 0;
                            padding: 20px;
                            color: #333;
                        }
                        .container {
                            background-color: #ffffff;
                            padding: 30px;
                            border-radius: 8px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                            max-width: 600px;
                            margin: auto;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .footer {
                            font-size: 12px;
                            text-align: center;
                            color: #999;
                            margin-top: 30px;
                        }
                        .btn {
                            display: inline-block;
                            margin-top: 20px;
                            padding: 10px 20px;
                            background-color: #007bff;
                            color: #fff !important;
                            text-decoration: none;
                            border-radius: 4px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Welcome to <span style="color:#007bff;">Talentrek</span>!</h2>
                        </div>
                        <p>Hi <strong>' . e($jobseeker->name ?? $jobseeker->email) . '</strong>,</p>

                        <p>Thank you for completing your registration on <strong>Talentrek</strong>. We\'re thrilled to have you with us!</p>

                        <p>You can now start exploring job opportunities, connect with recruiters, and grow your career.</p>

                        <p>If you have any questions, feel free to contact our support team at <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>

                        <p>
                            <a href="' . url('/') . '" class="btn">Visit Talentrek</a>
                        </p>

                        <p>Best wishes,<br><strong>The Talentrek Team</strong></p>
                    </div>

                    <div class="footer">
                        © ' . date('Y') . ' Talentrek. All rights reserved.
                    </div>
                </body>
                </html>
            ', function ($message) use ($jobseeker) {
                $message->to($jobseeker->email)
                    ->subject('Welcome to Talentrek – Registration Successful');
            });
         

        // Set session
        session([
            'jobseeker_id' => $jobseeker->id,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('jobseeker.registration');
    }


    public function showDetailsForm()
    {
        $email = session('email');
        // $phone = session('phone_number');
        $jobseekerId = session('jobseeker_id');
        $jobseeker = Jobseekers::find($jobseekerId);

        return view('site.jobseeker.registration', compact('jobseeker', 'email', 'phone'));
    }


    public function storeJobseekerInformation(Request $request)
    {
        $jobseekerId = session('jobseeker_id');

        if (!$jobseekerId) {
            return redirect()->route('signup.form')->with('error', 'Session expired. Please sign up again.');
        }

        $jobseeker = Jobseekers::find($jobseekerId);

        if (!$jobseeker) {
            return redirect()->route('signup.form')->with('error', 'Jobseeker not found.');
        }

        // 🔥 Step 1: Before validation, store resume file name in session if exists
        if ($request->hasFile('resume')) {
            $resumeFile = $request->file('resume');
            session()->flash('old_resume_name', $resumeFile->getClientOriginalName());
        }


        $validated = $request->validate([
            // Basic Info
            'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
            'email' => 'required|email|unique:jobseekers,email,' . $jobseeker->id,
            'phone_number' => 'nullable|unique:jobseekers,phone_number,' . $jobseeker->id,
            'phone_code' => 'required|string',
            'dob' => 'required|date',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:500',
            'pin_code' => 'required|digits:5',
            'gender' => 'required|string|in:Male,Female,Other',

            // National ID
            'national_id' => [
                'required',
                'min:10',
                function ($attribute, $value, $fail) use ($jobseeker) {
                    $existsInRecruiters = Recruiters::where('national_id', $value)->exists();
                    $existsInTrainers = Trainers::where('national_id', $value)->exists();
                    $existsInJobseekers = Jobseekers::where('national_id', $value)
                        ->where('id', '!=', $jobseeker->id)
                        ->exists();

                    if ($existsInRecruiters || $existsInTrainers || $existsInJobseekers) {
                        $fail('The national ID has already been taken.');
                    }
                },
            ],

            // Education
            'high_education.*' => 'required|string',
            'field_of_study.*' => 'required|string',
            'institution.*' => 'required|string',
            'graduate_year.*' => 'required|string',

            // Work Experience
            'job_role.*' => 'required|string',
            'organization.*' => 'required|string',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'required|date|after_or_equal:starts_from.*',

            // Skills & Interests
            'skills' => 'required|string',
            'interest' => 'required|string',
            'job_category' => 'required|string|max:255',
            'website_link' => 'nullable|url',
            'portfolio_link' => 'nullable|url',

            // Files
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',

        ], [

            // Basic Info
            'name.required' => 'Please enter your name.',
            'name.regex' => 'The full name should contain only letters and single spaces.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone_number.unique' => 'This phone number is already registered.',
            'dob.required' => 'Please select your date of birth.',
            'dob.date' => 'Date of birth must be a valid date.',
            'city.required' => 'Please enter your city.',
            'state.required' => 'Please enter your state.',
            'country.required' => 'Please enter your country.',
            'pin_code.required' => 'Please enter your pin code.',
            'address.required' => 'Please enter your address.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Gender must be Male, Female, or Other.',

            // National ID
            'national_id.required' => 'Please enter your national ID.',
            'national_id.min' => 'National ID must be at least 10 digits.',

            // Education
            'high_education.*.required' => 'Please enter your highest education.',
            'field_of_study.*.required' => 'Please enter your field of study.',
            'institution.*.required' => 'Please enter your institution name.',
            'graduate_year.*.required' => 'Please enter your graduation year.',

            // Work Experience
            'job_role.*.required' => 'Please enter your job role.',
            'organization.*.required' => 'Please enter your organization name.',
            'starts_from.*.required' => 'Please enter the job start date.',
            'starts_from.*.date' => 'Start date must be a valid date.',
            'end_to.*.required' => 'Please enter the job end date.',
            'end_to.*.date' => 'End date must be a valid date.',
            'end_to.*.after_or_equal' => 'End date must be the same or after the start date.',

            // Skills & Interests
            'skills.required' => 'Please list your skills.',
            'interest.required' => 'Please enter your area of interest.',
            'job_category.required' => 'Please select your job category.',
            'job_category.max' => 'Job category may not be greater than 255 characters.',
            'website_link.url' => 'Website link must be a valid URL.',
            'portfolio_link.url' => 'Portfolio link must be a valid URL.',

            // Files
            'resume.required' => 'Please upload your resume.',
            'resume.file' => 'Resume must be a valid file.',
            'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'resume.max' => 'Resume must not be larger than 2MB.',
            'profile_picture.required' => 'Please upload your profile picture.',
            'profile_picture.image' => 'Profile picture must be an image.',
            'profile_picture.mimes' => 'Profile picture must be in JPG, JPEG, or PNG format.',
            'profile_picture.max' => 'Profile picture must not be larger than 2MB.',
        ]);


        // Update jobseeker details
        $jobseeker->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'phone_code' => $validated['phone_code'],
            'date_of_birth' => $validated['dob'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'address' => $validated['address'],
            'country' => $validated['country'],
            'pin_code' => $validated['pin_code'],
            'gender' => $validated['gender'],
            'national_id' => $validated['national_id'],
            'is_registered' => 1
        ]);

        // Save education details
        foreach ($request->high_education as $index => $education) {
            EducationDetails::create([
                'user_id' => $jobseeker->id,
                'user_type' => 'jobseeker',
                'high_education' => $education,
                'field_of_study' => $request->field_of_study[$index] ?? null,
                'institution' => $request->institution[$index],
                'graduate_year' => $request->graduate_year[$index],
            ]);
        }

        // Save work experiences
        if ($request->has('job_role')) {
            foreach ($request->job_role as $index => $role) {
                $isCurrentlyWorking = $request->input("currently_working.$index") === 'on';

                $startDate = $request->starts_from[$index] ?? null;
                $endDate = $isCurrentlyWorking
                    ? 'work here'
                    : ($request->end_to[$index] ?? null);

                WorkExperience::create([
                    'user_id' => $jobseeker->id,
                    'user_type' => 'jobseeker',
                    'job_role' => $role,
                    'organization' => $request->organization[$index] ?? null,
                    'starts_from' => $startDate,
                    'end_to' => $endDate,
                ]);
            }
        }


        // Save skills
        Skills::create([
            'jobseeker_id' => $jobseeker->id,
            'skills' => $request->skills,
            'interest' => $request->interest,
            'job_category' => $request->job_category,
            'website_link' => $request->website_link,
            'portfolio_link' => $request->portfolio_link,
        ]);


        // Upload Resume
        if ($request->hasFile('resume')) {
            $existingResume = AdditionalInfo::where('user_id', $jobseeker->id)
                ->where('user_type', 'jobseeker')
                ->where('doc_type', 'resume')
                ->first();

            if (!$existingResume) {
                $resumeName = $request->file('resume')->getClientOriginalName();
                $fileNameToStoreResume = 'resume_' . time() . '.' . $request->file('resume')->getClientOriginalExtension();
                $request->file('resume')->move('uploads/', $fileNameToStoreResume);

                AdditionalInfo::create([
                    'user_id' => $jobseeker->id,
                    'user_type' => 'jobseeker',
                    'doc_type' => 'resume',
                    'document_name' => $resumeName,
                    'document_path' => asset('uploads/' . $fileNameToStoreResume),
                ]);
            }
        }

        // Upload Profile Picture
        if ($request->hasFile('profile_picture')) {
            $existingProfile = AdditionalInfo::where('user_id', $jobseeker->id)
                ->where('user_type', 'jobseeker')
                ->where('doc_type', 'profile_picture')
                ->first();

            if (!$existingProfile) {
                $profileName = $request->file('profile_picture')->getClientOriginalName();
                $fileNameToStoreProfile = 'profile_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $request->file('profile_picture')->move('uploads/', $fileNameToStoreProfile);

                AdditionalInfo::create([
                    'user_id' => $jobseeker->id,
                    'user_type' => 'jobseeker',
                    'doc_type' => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
                ]);
            }
        }



        // If no admin is assigned, assign an available admin
        if (!$jobseeker->assigned_admin) {
            // Example logic: pick any available admin (you can change the logic as needed)
            $availableAdmin = Admin::where('status', 'active')->inRandomOrder()->first();

            if ($availableAdmin) {
                $jobseeker->assigned_admin = $availableAdmin->id;
                $jobseeker->save();

                // Log the assignment
                Log::info('Jobseeker automatically assigned to admin during update', [
                    'jobseeker' => [
                        'id' => $jobseeker->id,
                        'name' => $jobseeker->name,
                        'email' => $jobseeker->email,
                    ],
                    'assigned_to_admin' => [
                        'id' => $availableAdmin->id,
                        'name' => $availableAdmin->name,
                        'email' => $availableAdmin->email,
                        'role' => $availableAdmin->role,
                    ],
                    'assigned_by' => [
                        'id' => auth()->id() ?? null,
                        'name' => auth()->user()?->name ?? 'System',
                        'email' => auth()->user()?->email ?? 'system',
                        'role' => auth()->user()?->role ?? 'unknown',
                    ],
                    'time' => now()
                ]);
            }
        }


        Mail::html(' 
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Welcome to Talentrek</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f6f8fa;
                        margin: 0;
                        padding: 20px;
                        color: #333;
                    }
                    .container {
                        background-color: #ffffff;
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        max-width: 600px;
                        margin: auto;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .footer {
                        font-size: 12px;
                        text-align: center;
                        color: #999;
                        margin-top: 30px;
                    }
                    .btn {
                        display: inline-block;
                        margin-top: 20px;
                        padding: 10px 20px;
                        background-color: #007bff;
                        color: #fff !important;
                        text-decoration: none;
                        border-radius: 4px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Welcome to <span style="color:#007bff;">Talentrek</span>!</h2>
                    </div>
                    <p>Hi <strong>' . e($jobseeker->name ?? $jobseeker->email) . '</strong>,</p>

                    <p>Thank you for completing your registration on <strong>Talentrek</strong>. We\'re thrilled to have you with us!</p>

                    <p>You can now start exploring job opportunities, connect with recruiters, and grow your career.</p>

                    <p>If you have any questions, feel free to contact our support team at <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>

                    <p>
                        <a href="' . url('/') . '" class="btn">Visit Talentrek</a>
                    </p>

                    <p>Best wishes,<br><strong>The Talentrek Team</strong></p>
                </div>

                <div class="footer">
                    © ' . date('Y') . ' Talentrek. All rights reserved.
                </div>
            </body>
            </html>
        ', function ($message) use ($jobseeker) {
            $message->to($jobseeker->email)
                ->subject('Welcome to Talentrek – Registration Successful');
        });

        $data = [
            'sender_id' => $jobseeker->id,
            'sender_type' => 'Registration by Jobseeker.',
            'receiver_id' => '1',
            'message' => 'Welcome to Talentrek – Registration Successful by '.$jobseeker->name,
            'is_read' => 0,
            'is_read_admin' => 0,
            'user_type' => 'jobseeker'
        ];

        Notification::insert($data);
        session()->forget('jobseeker_id');
        return redirect()->route('signin.form')->with('success_popup', true);
    }


    public function showSignInForm()
    {
        return view('site.jobseeker.sign-in');
    }

    public function showSignUpForm()
    {
        return view('site.jobseeker.sign-up');
    }

    public function showProfilePage()
    {
        //$jobseeker = Auth::guard('jobseeker')->user();
        return view('site.jobseeker.profile');
    }

    public function showSubscriptionPlanPage()
    {
        //$jobseeker = Auth::guard('jobseeker')->user();
        return view('site.jobseeker.subscription-plan');
    }


    // public function loginJobseeker(Request $request)
    // {
    //     $this->validate($request, [
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $jobseeker = Jobseekers::where('email', $request->email)->first();

    //     if (!$jobseeker) {
    //         // Email does not exist
    //         session()->flash('error', 'Invalid email or password.');
    //         return back()->withInput($request->only('email'));
    //     }

    //     if ($jobseeker->status !== 'active') {
    //         // Status is inactive or blocked
    //         session()->flash('error', 'Your account is inactive. Please contact admimnistrator.');
    //         return back()->withInput($request->only('email'));
    //     }

    //     // Now attempt login only if status is active
    //     if (Auth::guard('jobseeker')->attempt(['email' => $request->email, 'password' => $request->password])) {
    //         return redirect()->route('jobseeker.profile');
    //     } else {
    //         session()->flash('error', 'Invalid email or password.');
    //         return back()->withInput($request->only('email'));
    //     }
    // }

    public function loginJobseeker(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $jobseeker = Jobseekers::where('email', $request->email)->first();

        if (!$jobseeker) {
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }

        if ($jobseeker->status !== 'active') {
            session()->flash('error', 'Your account is inactive. Please contact administrator.');
            return back()->withInput($request->only('email'));
        }

        // ✅ Check admin_status
        if ($jobseeker->admin_status === 'superadmin_reject' || $jobseeker->admin_status === 'rejected') {
            session()->flash('error', 'Your account has been rejected by administrator.');
            return back()->withInput($request->only('email'));
        }

        if ($jobseeker->admin_status !== 'superadmin_approved') {
            session()->flash('error', 'Your account is not yet approved by administrator.');
            return back()->withInput($request->only('email'));
        }

        // ✅ Check registration completion
        if ($jobseeker->is_registered == 0) {
            session([
                'jobseeker_id'  => $jobseeker->id,
                'email'         => $jobseeker->email,
                'phone_number'  => $jobseeker->phone_number,
            ]);

            return redirect()->route('jobseeker.registration')
                ->with([
                    'info'  => 'Please complete your registration.',
                    'email' => session('email'),
                    'phone' => session('phone_number'),
                ]);
        }

        // ✅ Attempt login only if all checks pass
        if (Auth::guard('jobseeker')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('jobseeker.profile');
        } else {
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }
    }



  











    public function getJobseekerAllDetails()
    {
        $jobseeker = Auth::guard('jobseeker')->user();
        $jobseekerId = $jobseeker->id;

        // Jobseeker basic details and skill details
        $jobseekerSkills = DB::table('jobseekers')
            ->leftJoin('skills', 'skills.jobseeker_id', '=', 'jobseekers.id')
            ->where('jobseekers.id', $jobseekerId)
            ->select('jobseekers.*', 'skills.*')
            ->first();


        // Education details (multiple)
        $educationDetails = DB::table('education_details')
            ->where('user_id', $jobseekerId)
            ->get();

        // Work experience (multiple)
        $workExperiences = DB::table('work_experience')
            ->where('user_id', $jobseekerId)
            ->get();

        // echo "<pre>";
        // print_r($workExperiences);
        // exit;

        return view('site.jobseeker.profile', compact(
            'jobseekerSkills',
            'educationDetails',
            'workExperiences',

        ));

    }



    public function logoutJobseeker(Request $request)
    {
        Auth::guard('jobseeker')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin.form')->with('success', 'Logged out successfully');
    }


    public function updatePersonalInfo(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
            'email' => 'required|email|unique:jobseekers,email,' . $user->id,
            'gender' => 'required|string|in:Male,Female,Other',
            'phone_number' => 'required|digits:9',
            'dob' => 'required|date',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:500',
            'pin_code' => 'required|digits:5',
            'national_id' => [
                'required',
                'min:10',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value != $user->national_id) {
                        $existsInJobseekers = Jobseekers::where('national_id', $value)
                            ->where('id', '!=', $user->id)
                            ->exists();

                        if ($existsInJobseekers) {
                            $fail('The national ID has already been taken.');
                        }
                    }
                },
            ],
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name must not exceed 255 characters.',

            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',

            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Gender must be Male, Female, or Other.',

            'phone_number.required' => 'The phone number is required.',
            'phone_number.digits' => 'The phone number must be exactly 9 digits.',

            'dob.required' => 'The date of birth is required.',
            'dob.date' => 'Please enter a valid date of birth.',

            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a valid string.',
            'city.max' => 'The city must not exceed 255 characters.',

            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a valid string.',
            'address.max' => 'The address must not exceed 500 characters.',

            'national_id.required' => 'The national ID is required.',
            'national_id.min' => 'The national ID must be at least 10 characters.',
        ]);


        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'date_of_birth' => $validated['dob'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'address' => $validated['address'],
            'country' => $validated['country'],
            'pin_code' => $validated['pin_code'],
            'gender' => $validated['gender'],
            'national_id' => $validated['national_id'],
        ]);

        return response()->json(['status' => 'success', 'message' => 'Personal information updated successfully!']);
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
        ], [
            'high_education.*.required' => 'The highest education field is required.',
            'high_education.*.string' => 'The highest education must be a valid string.',
            'high_education.*.max' => 'The highest education must not exceed 255 characters.',

            'field_of_study.*.required' => 'The field of study is required.',
            'field_of_study.*.string' => 'The field of study must be a valid string.',
            'field_of_study.*.max' => 'The field of study must not exceed 255 characters.',

            'institution.*.required' => 'The institution name is required.',
            'institution.*.string' => 'The institution must be a valid string.',
            'institution.*.max' => 'The institution must not exceed 255 characters.',

            'graduate_year.*.required' => 'The graduation year is required.',
            'graduate_year.*.string' => 'The graduation year must be a valid string.',
            'graduate_year.*.max' => 'The graduation year must not exceed 255 characters.',
        ]);


        $incomingIds = $request->input('education_id', []);

        $existingIds = EducationDetails::where('user_id', $userId)
            ->where('user_type', 'jobseeker')
            ->pluck('id')
            ->toArray();

        $toDelete = array_diff($existingIds, $incomingIds);
        EducationDetails::whereIn('id', $toDelete)->delete();

        foreach ($request->input('high_education', []) as $i => $education) {
            $data = [
                'user_id' => $userId,
                'user_type' => 'jobseeker',
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

        // Validation for multiple entries
        $validated = $request->validate([
            'job_role.*' => 'required|string|max:255',
            'organization.*' => 'required|string|max:255',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'required|date|after_or_equal:starts_from.*',
            'currently_working' => 'array',
        ], [
            'job_role.*.required' => 'Please enter your job role.',
            'job_role.*.string' => 'Job role should be a valid text.',
            'job_role.*.max' => 'Job role can’t be more than 255 characters.',

            'organization.*.required' => 'Please provide the organization name.',
            'organization.*.string' => 'Organization name must be a valid string.',
            'organization.*.max' => 'Organization name can’t exceed 255 characters.',

            // Start Date
            'starts_from.*.required' => 'Please select the start date for each experience.',
            'starts_from.*.date' => 'The start date must be in a valid format (e.g., YYYY-MM-DD).',

            // End Date
            'end_to.*.required' => 'Please select the end date for each experience.',
            'end_to.*.date' => 'The end date must be in a valid format (e.g., YYYY-MM-DD).',
            'end_to.*.after_or_equal' => 'The end date must be the same as or after the start date.',

            'currently_working.array' => 'Currently working selection must be in a valid format.',
        ]);


        // Manual check for end date >= start date
        foreach ($request->end_to as $index => $end) {
            if (isset($request->starts_from[$index]) && $end < $request->starts_from[$index]) {
                return back()->withErrors([
                    "end_to.$index" => "End date should not be earlier than the start date."
                ])->withInput();
            }
        }


        $workIds = $request->input('work_id', []);
        $existingIds = WorkExperience::where('user_id', $user_id)
            ->where('user_type', 'jobseeker')
            ->pluck('id')
            ->toArray();

        $toDelete = array_diff($existingIds, $workIds);
        WorkExperience::whereIn('id', $toDelete)->delete();

        foreach ($request->input('job_role', []) as $i => $role) {
            $currentlyWorking = in_array($i, $request->input('currently_working', []));
            $endToValue = $currentlyWorking ? 'Work here' : ($request->end_to[$i] ?? null);

            $data = [
                'user_id' => $user_id,
                'user_type' => 'jobseeker',
                'job_role' => $role,
                'organization' => $request->organization[$i] ?? null,
                'starts_from' => $request->starts_from[$i] ?? null,
                'end_to' => $endToValue,
            ];

            if (!empty($request->work_id[$i])) {
                WorkExperience::where('id', $request->work_id[$i])->update($data);
            } else {
                WorkExperience::create($data);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Work Experience information saved successfully!']);

    }


    public function updateSkillsInfo(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;

        $validated = $request->validate([
            'skills' => 'required|string',
            'interest' => 'required|string',
            'job_category' => 'required|string|max:255',
            'website_link' => 'nullable|url',
            'portfolio_link' => 'nullable|url',
        ], [
            // Skills
            'skills.required' => 'Please enter your skills.',
            'skills.string' => 'Skills must be in text format.',

            // Interest
            'interest.required' => 'Please mention your area of interest.',
            'interest.string' => 'Interest must be a valid string.',

            // Job Category
            'job_category.required' => 'Please select a job category.',
            'job_category.string' => 'Job category should be text.',
            'job_category.max' => 'Job category must not exceed 255 characters.',

            // Website Link
            'website_link.url' => 'Please enter a valid website URL (e.g., https://example.com).',

            // Portfolio Link
            'portfolio_link.url' => 'Please enter a valid portfolio URL (e.g., https://portfolio.com).',
        ]);


        $skills = Skills::where('jobseeker_id', $user_id)->first();

        if ($skills) {
            $skills->update([
                'skills' => $validated['skills'] ?? null,
                'interest' => $validated['interest'] ?? null,
                'job_category' => $validated['job_category'] ?? null,
                'website_link' => $validated['website_link'] ?? null,
                'portfolio_link' => $validated['portfolio_link'] ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Skills updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Skills record not found for update.'
            ], 404);
        }
    }


    // public function updateAdditionalInfo(Request $request)
    // {
    //     $userId = auth()->id();

    //     $validated = $request->validate([
    //         'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
    //         'profile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //     ], [
    //         // Resume Messages
    //         'resume.file' => 'The resume must be a valid file.',
    //         'resume.mimes' => 'The resume must be a file of type: PDF, DOC, or DOCX.',
    //         'resume.max' => 'The resume must not be larger than 2MB.',

    //         // Profile Messages
    //         'profile.file' => 'The profile must be a valid file.',
    //         'profile.mimes' => 'The profile must be a file of type: JPG, JPEG, PNG, or PDF.',
    //         'profile.max' => 'The profile file must not exceed 2MB.',
    //     ]);

    //     foreach (['resume', 'profile'] as $type) {
    //         if ($request->hasFile($type)) {
    //             $file = $request->file($type);
    //             $fileName = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('uploads'), $fileName);
    //             $path = asset('uploads/' . $fileName);

    //             // Change doc_type name if 'profile' to 'profile_picture'
    //             $docType = $type === 'profile' ? 'profile_picture' : $type;

    //             AdditionalInfo::updateOrCreate(
    //                 ['user_id' => $userId, 'doc_type' => $docType],
    //                 ['document_path' => $path, 'document_name' => $fileName]
    //             );
    //         }
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Additional information updated successfully!'
    //     ]);
    // }
    public function updateAdditionalInfo(Request $request)
    {
        $userId = auth()->id();

        // Check existing docs for this user
        $existingResume = AdditionalInfo::where('user_id', $userId)->where('doc_type', 'resume')->exists();
        $existingProfile = AdditionalInfo::where('user_id', $userId)->where('doc_type', 'profile_picture')->exists();

        // Dynamic rules
        $rules = [];
        if (!$existingResume) {
            $rules['resume'] = 'required|file|mimes:pdf,doc,docx|max:2048';
        } elseif ($request->hasFile('resume')) {
            $rules['resume'] = 'file|mimes:pdf,doc,docx|max:2048';
        }

        if (!$existingProfile) {
            $rules['profile'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } elseif ($request->hasFile('profile')) {
            $rules['profile'] = 'file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        $validated = $request->validate($rules, [
            // Resume Messages
            'resume.required' => 'The resume is required.',
            'resume.file'     => 'The resume must be a valid file.',
            'resume.mimes'    => 'The resume must be a file of type: PDF, DOC, or DOCX.',
            'resume.max'      => 'The resume must not be larger than 2MB.',

            // Profile Messages
            'profile.required' => 'The profile is required.',
            'profile.file'     => 'The profile must be a valid file.',
            'profile.mimes'    => 'The profile must be a file of type: JPG, JPEG, PNG, or PDF.',
            'profile.max'      => 'The profile file must not exceed 2MB.',
        ]);

        // Save files
        foreach (['resume', 'profile'] as $type) {
            if ($request->hasFile($type)) {
                $file = $request->file($type);
                $fileName = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $path = asset('uploads/' . $fileName);

                $docType = $type === 'profile' ? 'profile_picture' : $type;

                AdditionalInfo::updateOrCreate(
                    ['user_id' => $userId, 'doc_type' => $docType],
                    ['document_path' => $path, 'document_name' => $fileName]
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Additional information updated successfully!'
        ]);
    }




    public function deleteAdditionalFile($type)
    {
        $userId = auth()->id();

        $file = AdditionalInfo::where('user_id', $userId)
            ->where('doc_type', $type)
            ->first();

        if ($file) {
            $filePath = public_path($file->document_path); // e.g. public/uploads/resume_1753084475.pdf

            if ($file->document_path && file_exists($filePath)) {
                unlink($filePath);
            }

            $file->delete();

            return response()->json([
                'status' => 'success',
                'message' => ucfirst(str_replace('_', ' ', $type)) . ' deleted successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => ucfirst(str_replace('_', ' ', $type)) . ' not found.'
        ], 404);
    }




    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'contact' => [
                'required',
                function ($attribute, $value, $fail) {
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $column = $isEmail ? 'email' : 'phone_number';

                    $exists = DB::table('jobseekers')->where($column, $value)->exists();

                    if (!$exists) {
                        $fail("This " . ($isEmail ? 'email' : 'mobile number') . " is not registered.");
                    }
                }
            ],
        ]);

        $otp = rand(100000, 999999);
        $contact = $request->contact;
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
        $contactMethod = $isEmail ? 'email' : 'phone_number';

        // Save OTP in database
        DB::table('jobseekers')->where($contactMethod, $contact)->update([
            'otp' => $otp,
            'updated_at' => now()
        ]);

        // === OTP sending ===
        if ($isEmail) {
            Mail::html('
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Password Reset OTP</title>
                    <style>
                        body {
                            background-color: #f6f8fa;
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            margin: 0;
                            color: #333;
                        }
                        .container {
                            background-color: #ffffff;
                            padding: 30px;
                            max-width: 500px;
                            margin: 20px auto;
                            border-radius: 8px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        .otp-box {
                            font-size: 24px;
                            font-weight: bold;
                            background-color: #f0f4ff;
                            padding: 15px;
                            text-align: center;
                            border: 1px dashed #007bff;
                            border-radius: 6px;
                            margin: 20px 0;
                            color: #007bff;
                        }
                        .footer {
                            font-size: 12px;
                            text-align: center;
                            margin-top: 30px;
                            color: #888;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Password Reset Request</h2>
                        <p>Hello,</p>
                        <p>We received a request to reset your password. Use the OTP below to proceed:</p>

                        <div class="otp-box">' . $otp . '</div>

                        <p>This OTP is valid for the next 10 minutes. If you did not request this, please ignore this email.</p>

                        <p>Thanks,<br><strong>The Talentrek Team</strong></p>
                    </div>

                    <div class="footer">
                        &copy; ' . date('Y') . ' Talentrek. All rights reserved.
                    </div>
                </body>
                </html>
            ', function ($message) use ($contact) {
                $message->to($contact)->subject('Your Password Reset OTP – Talentrek');
            });
        } else {
            // Simulate SMS sending (replace with Msg91 / Twilio integration)
            // SmsService::send($contact, "Your OTP is: $otp");
        }

        // Store contact info in session
        session([
            'otp_method' => $contactMethod,
            'otp_value' => $contact
        ]);

        return redirect()->route('jobseeker.verify-otp')->with('success', 'OTP sent!');
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
        DB::table('jobseekers')->where($contactMethod, $contact)->update([
            'otp' => $otp,
            'updated_at' => now()
        ]);

        // === OTP sending ===
        if ($isEmail) {
            Mail::html('
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Password Reset OTP</title>
                    <style>
                        body {
                            background-color: #f6f8fa;
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            margin: 0;
                            color: #333;
                        }
                        .container {
                            background-color: #ffffff;
                            padding: 30px;
                            max-width: 500px;
                            margin: 20px auto;
                            border-radius: 8px;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        .otp-box {
                            font-size: 24px;
                            font-weight: bold;
                            background-color: #f0f4ff;
                            padding: 15px;
                            text-align: center;
                            border: 1px dashed #007bff;
                            border-radius: 6px;
                            margin: 20px 0;
                            color: #007bff;
                        }
                        .footer {
                            font-size: 12px;
                            text-align: center;
                            margin-top: 30px;
                            color: #888;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Password Reset Request</h2>
                        <p>Hello,</p>
                        <p>We received a request to reset your password. Use the OTP below to proceed:</p>

                        <div class="otp-box">' . $otp . '</div>

                        <p>This OTP is valid for the next 10 minutes. If you did not request this, please ignore this email.</p>

                        <p>Thanks,<br><strong>The Talentrek Team</strong></p>
                    </div>

                    <div class="footer">
                        &copy; ' . date('Y') . ' Talentrek. All rights reserved.
                    </div>
                </body>
                </html>
            ', function ($message) use ($contact) {
                $message->to($contact)->subject('Your Password Reset OTP – Talentrek');
            });
        } else {
            // Simulate SMS sending (replace with Msg91 / Twilio integration)
            // SmsService::send($contact, "Your OTP is: $otp");
        }

        return response()->json(['message' => 'OTP resent successfully.']);
    }

    public function showOtpForm()
    {
        return view('site.jobseeker.verify-otp');
    }

    public function showResetPasswordForm()
    {
        return view('site.jobseeker.reset-password');
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

        $jobseeker = DB::table('jobseekers')
            ->where($column, $contact)
            ->where('otp', $request->otp)
            ->first();

        if (!$jobseeker) {
            return back()
                ->withErrors(['otp' => 'Invalid OTP or contact. Please enter the correct 6-digit OTP.'])
                ->withInput();
        }

        // Save verified user ID in session
        session(['verified_jobseeker' => $jobseeker->id]);

        return redirect()->route('jobseeker.reset-password');
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);

        $jobseekerId = session('verified_jobseeker');

        if (!$jobseekerId) {
            return redirect()->route('jobseeker.forget-password')->withErrors([
                'session' => 'Session expired. Please try again.'
            ]);
        }

        $jobseeker = DB::table('jobseekers')->where('id', $jobseekerId)->first();

        if (!$jobseeker) {
            return redirect()->route('jobseeker.forget-password')->withErrors([
                'not_found' => 'User not found.'
            ]);
        }

        DB::table('jobseekers')->where('id', $jobseekerId)->update([
            'password' => Hash::make($request->new_password),
            'pass' => $request->new_password,
            'otp' => null,
            'updated_at' => now(),
        ]);

        // ✅ Send Password Reset Confirmation Email (if email available)
        if (!empty($jobseeker->email)) {
            Mail::html('
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Password Reset Confirmation</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f6f9;
                            margin: 0;
                            padding: 20px;
                            color: #333;
                        }
                        .container {
                            background: #fff;
                            padding: 30px;
                            border-radius: 8px;
                            max-width: 600px;
                            margin: auto;
                            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                        }
                        .footer {
                            text-align: center;
                            font-size: 12px;
                            color: #888;
                            margin-top: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Password Reset Successfully</h2>
                        <p>Hello <strong>' . e($jobseeker->email) . '</strong>,</p>
                        <p>Your password has been successfully updated for your Talentrek account.</p>
                        <p>If you didn\'t initiate this change, please contact our support team immediately.</p>
                        <p>Stay safe,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">
                        &copy; ' . date('Y') . ' Talentrek. All rights reserved.
                    </div>
                </body>
                </html>
            ', function ($message) use ($jobseeker) {
                $message->to($jobseeker->email)
                    ->subject('Your Talentrek Password Has Been Reset');
            });
        }

        // Clear session
        session()->forget('verified_jobseeker');
        session()->forget('otp_value');
        session()->forget('otp_method');

        return redirect()->route('signin.form')->with('success', 'Password changed successfully.');
    }


    public function mentorshipDetails($id)
    {
        $mentorDetails = Mentors::with([
            'reviews.jobseeker',
            'additionalInfo',
            'profilePicture',
            'experiences',
            'educations' => function ($q) {
                $q->where('user_type', 'mentor')->orderBy('id')->limit(1);
            },
            'bookingSlots'
        ])
            ->where('id', $id)
            ->firstOrFail();

        // Calculate total experience
        $experiences = DB::table('work_experience')
            ->where('user_id', $id)
            ->where('user_type', 'mentor')
            ->get();

        $totalDays = 0;


        foreach ($experiences as $exp) {
            $start = Carbon::parse($exp->starts_from);

            if (!empty($exp->end_to) && strtolower($exp->end_to) !== 'work here') {
                $end = Carbon::parse($exp->end_to);
            } else {
                // Assume current date if still working
                $end = Carbon::now();
            }

            $totalDays += $start->diffInDays($end);
        }

        $interval = CarbonInterval::days($totalDays)->cascade();
        $totalExperience = sprintf('%d years %d months %d days', $interval->y, $interval->m, $interval->d);

        // Pass experience as property (optional)
        $mentorDetails->total_experience = $totalExperience;

        $reviews = DB::table('reviews')
            ->join('jobseekers', 'reviews.jobseeker_id', '=', 'jobseekers.id')
            ->where('reviews.user_type', 'mentor')
            ->select(
                'reviews.*',
                'jobseekers.name as jobseeker_name'
            )
            ->get();

        return view('site.mentorship-details', compact('mentorDetails', 'reviews'));
    }


    public function bookingSession($mentor_id, $slot_id)
    {
        $mentor = Mentors::with([
            'reviews.jobseeker',
            'additionalInfo',
            'profilePicture',
            'experiences',
        ])
            ->where('id', $mentor_id)
            ->firstOrFail();

        // Calculate total experience
        $experiences = DB::table('work_experience')
            ->where('user_id', $mentor_id)
            ->where('user_type', 'mentor')
            ->get();

        $totalDays = 0;


        foreach ($experiences as $exp) {
            $start = Carbon::parse($exp->starts_from);

            if (!empty($exp->end_to) && strtolower($exp->end_to) !== 'work here') {
                $end = Carbon::parse($exp->end_to);
            } else {
                // Assume current date if still working
                $end = Carbon::now();
            }

            $totalDays += $start->diffInDays($end);
        }
        
        $interval = CarbonInterval::days($totalDays)->cascade();
        $totalExperience = sprintf('%d years %d months %d days', $interval->y, $interval->m, $interval->d);

        // Pass experience as property (optional)
        $mentor->total_experience = $totalExperience;

        $mentorDetails = Mentors::select('mentors.*', 'booking_slots.*', 'booking_slots.id as booking_slot_id', 'mentors.id as mentor_id')
            ->where('mentors.id', $mentor_id)
            ->join('booking_slots', 'mentors.id', '=', 'booking_slots.user_id')
            ->where('booking_slots.id', $slot_id)
            ->first();
        return view('site.mentorship-book-session', compact('mentorDetails', 'mentor'));
    }


    public function bookingAssessorSession($assessor_id, $slot_id)
    {

        $assessor = Assessors::with([
            'reviews.jobseeker',
            'additionalInfo',
            'profilePicture',
            'experiences',
        ])
            ->where('id', $assessor_id)
            ->firstOrFail();

        // Calculate total experience
        $experiences = DB::table('work_experience')
            ->where('user_id', $assessor_id)
            ->where('user_type', 'assessor')
            ->get();

        $totalDays = 0;

        foreach ($experiences as $exp) {
            $start = Carbon::parse($exp->starts_from);

            if (!empty($exp->end_to) && strtolower($exp->end_to) !== 'work here') {
                $end = Carbon::parse($exp->end_to);
            } else {
                // Assume current date if still working
                $end = Carbon::now();
            }

            $totalDays += $start->diffInDays($end);
        }


        $interval = CarbonInterval::days($totalDays)->cascade();
        $totalExperience = sprintf('%d years %d months %d days', $interval->y, $interval->m, $interval->d);

        // Pass experience as property (optional)
        $assessor->total_experience = $totalExperience;



        $assessorDetails = Assessors::select('assessors.*', 'booking_slots.*', 'booking_slots.id as booking_slot_id', 'assessors.id as assessor_id')
            ->where('assessors.id', $assessor_id)
            ->join('booking_slots', 'assessors.id', '=', 'booking_slots.user_id')
            ->where('booking_slots.id', $slot_id)
            ->first();

        return view('site.assessment-book-session', compact('assessorDetails', 'assessor'));
    }


    public function bookingCoachSession($coach_id, $slot_id)
    {
        $coach = Coach::with([
            'reviews.jobseeker',
            'additionalInfo',
            'profilePicture',
            'experiences',
        ])
            ->where('id', $coach_id)
            ->firstOrFail();

        // Calculate total experience
        $experiences = DB::table('work_experience')
            ->where('user_id', $coach_id)
            ->where('user_type', 'coach')
            ->get();

        $totalDays = 0;

        foreach ($experiences as $exp) {
            $start = Carbon::parse($exp->starts_from);

            if (!empty($exp->end_to) && strtolower($exp->end_to) !== 'work here') {
                $end = Carbon::parse($exp->end_to);
            } else {
                // Assume current date if still working
                $end = Carbon::now();
            }

            $totalDays += $start->diffInDays($end);
        }

        $interval = CarbonInterval::days($totalDays)->cascade();
        $totalExperience = sprintf('%d years %d months %d days', $interval->y, $interval->m, $interval->d);

        // Pass experience as property (optional)
        $coach->total_experience = $totalExperience;


        $coachDetails = Coach::select('coaches.*', 'booking_slots.*', 'booking_slots.id as booking_slot_id', 'coaches.id as coach_id')
            ->where('coaches.id', $coach_id)
            ->join('booking_slots', 'coaches.id', '=', 'booking_slots.user_id')
            ->where('booking_slots.id', $slot_id)
            ->first();
        return view('site.coach-book-session', compact('coachDetails', 'coach'));
    }


    public function getAvailableSlots(Request $request)
    {
        $mode = $request->query('mode');
        $date = $request->query('date');
        $mentor_id = $request->query('mentor_id');
        $jobseeker_id = auth('jobseeker')->id(); // or use from request if sent

        if (!$mode || !$date || !$mentor_id) {
            return response()->json([
                'status' => false,
                'message' => 'Missing required parameters.'
            ], 400);
        }

        $formattedDate = date('Y-m-d', strtotime($date));

        $slots = BookingSlot::where('slot_mode', $mode)
            ->where('user_type', 'mentor')
            ->where('user_id', $mentor_id)
            ->get();

        if ($slots->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No slots available.'
            ], 404);
        }

        // Step: Get already booked slots by jobseeker on selected date
        $bookedSlots = DB::table('jobseeker_saved_booking_session')
            // ->where('jobseeker_id', $jobseeker_id)
            ->where('user_type', 'mentor')
            ->where('user_id', $mentor_id)
            ->whereDate('slot_date', $formattedDate)
            ->pluck('booking_slot_id')
            ->toArray();

        // Get slot IDs that are unavailable for this date from separate table
        $unavailableSlotIds = DB::table('booking_slots_unavailable_dates')
            ->where('unavailable_date', $formattedDate)
            ->pluck('booking_slot_id')
            ->toArray();

        // Transform each slot with is_unavailable flag
        $slots->transform(function ($slot) use ($formattedDate, $unavailableSlotIds) {
            $unavailableDates = [];

            if (!empty($slot->unavailable_dates)) {
                if (is_string($slot->unavailable_dates)) {
                    $decoded = json_decode($slot->unavailable_dates, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $unavailableDates = $decoded;
                    }
                } elseif (is_array($slot->unavailable_dates)) {
                    $unavailableDates = $slot->unavailable_dates;
                }
            }

            $slot->is_unavailable = in_array($formattedDate, $unavailableDates) || in_array($slot->id, $unavailableSlotIds);
            $slot->start_time = \Carbon\Carbon::parse($slot->start_time)->format('h:i A');
            $slot->end_time = \Carbon\Carbon::parse($slot->end_time)->format('h:i A');

            return $slot;
        });

        return response()->json([
            'status' => true,
            'date' => $formattedDate,
            'slots' => $slots,
            'booked_slot_ids' => $bookedSlots
        ]);
    }


    public function getAssesorAvailableSlots(Request $request)
    {
        $mode = $request->query('mode');
        $date = $request->query('date');
        $assessor_id = $request->query('assessor_id');
        $jobseeker_id = auth('jobseeker')->id();

        if (!$mode || !$date || !$assessor_id) {
            return response()->json([
                'status' => false,
                'message' => 'Missing required parameters.'
            ], 400);
        }

        $formattedDate = date('Y-m-d', strtotime($date));

        // Get all slots for the assessor
        $slots = BookingSlot::where('slot_mode', $mode)
            ->where('user_type', 'assessor')
            ->where('user_id', $assessor_id)
            ->get();

        if ($slots->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No slots available.'
            ], 404);
        }

        // Get already booked slots by jobseeker on selected date
        $bookedSlots = DB::table('jobseeker_saved_booking_session')
            ->where('jobseeker_id', $jobseeker_id)
            ->where('user_type', 'assessor')
            ->where('user_id', $assessor_id)
            ->whereDate('slot_date', $formattedDate)
            ->pluck('booking_slot_id')
            ->toArray();

        // Get slot IDs that are unavailable for this date from separate table
        $unavailableSlotIds = DB::table('booking_slots_unavailable_dates')
            ->where('unavailable_date', $formattedDate)
            ->pluck('booking_slot_id')
            ->toArray();

        // Transform each slot with is_unavailable flag
        $slots->transform(function ($slot) use ($formattedDate, $unavailableSlotIds) {
            $unavailableDates = [];

            // Decode unavailable_dates JSON field
            if (!empty($slot->unavailable_dates)) {
                if (is_string($slot->unavailable_dates)) {
                    $decoded = json_decode($slot->unavailable_dates, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $unavailableDates = $decoded;
                    }
                } elseif (is_array($slot->unavailable_dates)) {
                    $unavailableDates = $slot->unavailable_dates;
                }
            }

            $slot->is_unavailable = in_array($formattedDate, $unavailableDates) || in_array($slot->id, $unavailableSlotIds);
            $slot->start_time = \Carbon\Carbon::parse($slot->start_time)->format('h:i A');
            $slot->end_time = \Carbon\Carbon::parse($slot->end_time)->format('h:i A');

            return $slot;
        });

        return response()->json([
            'status' => true,
            'date' => $formattedDate,
            'slots' => $slots,
            'booked_slot_ids' => $bookedSlots
        ]);
    }


    public function getCoachAvailableSlots(Request $request)
    {
        $mode = $request->query('mode');
        $date = $request->query('date');
        $coach_id = $request->query('coach_id');
        $jobseeker_id = auth('jobseeker')->id(); // or use from request if sent

        if (!$mode || !$date || !$coach_id) {
            return response()->json([
                'status' => false,
                'message' => 'Missing required parameters.'
            ], 400);
        }

        $formattedDate = date('Y-m-d', strtotime($date));

        $slots = BookingSlot::where('slot_mode', $mode)
            ->where('user_type', 'coach')
            ->where('user_id', $coach_id)
            ->get();

        if ($slots->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No slots available.'
            ], 404);
        }

        // Step: Get already booked slots by jobseeker on selected date
        $bookedSlots = DB::table('jobseeker_saved_booking_session')
            ->where('jobseeker_id', $jobseeker_id)
            ->where('user_type', 'coach')
            ->where('user_id', $coach_id)
            ->whereDate('slot_date', $formattedDate)
            ->pluck('booking_slot_id')
            ->toArray();

        // Get slot IDs that are unavailable for this date from separate table
        $unavailableSlotIds = DB::table('booking_slots_unavailable_dates')
            ->where('unavailable_date', $formattedDate)
            ->pluck('booking_slot_id')
            ->toArray();

        // Transform each slot with is_unavailable flag
        $slots->transform(function ($slot) use ($formattedDate, $unavailableSlotIds) {
            $unavailableDates = [];

            if (!empty($slot->unavailable_dates)) {
                if (is_string($slot->unavailable_dates)) {
                    $decoded = json_decode($slot->unavailable_dates, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $unavailableDates = $decoded;
                    }
                } elseif (is_array($slot->unavailable_dates)) {
                    $unavailableDates = $slot->unavailable_dates;
                }
            }

            $slot->is_unavailable = in_array($formattedDate, $unavailableDates) || in_array($slot->id, $unavailableSlotIds);
            $slot->start_time = \Carbon\Carbon::parse($slot->start_time)->format('h:i A');
            $slot->end_time = \Carbon\Carbon::parse($slot->end_time)->format('h:i A');

            return $slot;
        });

        return response()->json([
            'status' => true,
            'date' => $formattedDate,
            'slots' => $slots,
            'booked_slot_ids' => $bookedSlots
        ]);
    }


    public function submitMentorshipBooking(Request $request)
    {

        $request->validate([
            'mentor_id' => 'required|exists:mentors,id',
            'mode' => 'required|in:online,offline',
            'date' => 'required|date',
            'slot_id' => 'required|exists:booking_slots,id',
            'slot_time' => 'required',
        ]);

        $jobseeker = auth('jobseeker')->user();

        // Check if there's already a booking on the same date and time
        $existingBooking = BookingSession::where('jobseeker_id', $jobseeker->id)
            ->where('user_type', 'mentor')
            ->where('user_id', $request->mentor_id)
            ->whereDate('slot_date', $request->date)
            ->where('slot_time', $request->slot_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'You have already booked a session for this date and time.');
        }

        // Create the booking record
        $booking = BookingSession::create([
            'jobseeker_id' => $jobseeker->id,
            'user_type' => 'mentor',
            'user_id' => $request->mentor_id,
            'booking_slot_id' => $request->slot_id,
            'slot_date' => $request->date,
            'slot_mode' => $request->mode,
            'slot_time' => $request->slot_time,
            'status' => 'pending',
        ]);

        $mentorAddress = null;

        // If online, create Zoom meeting
        if ($request->mode === 'online') {
            $zoom = new ZoomService();
            $startTime = $request->date . ' ' . explode(' - ', $request->slot_time)[0];
            $zoomMeeting = $zoom->createMeeting("Mentorship with #{$jobseeker->id}", $startTime);

            if ($zoomMeeting) {
                $booking->update([
                    'zoom_start_url' => $zoomMeeting['start_url'],
                    'zoom_join_url' => $zoomMeeting['join_url'],
                ]);
            } else {
                \Log::error('Zoom creation failed for mentorship booking', [
                    'jobseeker_id' => $jobseeker->id,
                    'mentor_id' => $request->mentor_id,
                    'slot_time' => $request->slot_time,
                ]);
                return redirect()->back()->with('error', 'Zoom meeting creation failed. Please try again later.');
            }
        } elseif ($request->mode === 'offline') {
            // Get mentor address
            $mentor = Mentors::find($request->mentor_id);
            $mentorAddress = $mentor?->address ?? 'Address not available';
        }

        return redirect()->back()->with([
            'success' => 'Session booked successfully.',
            'booking_id' => $booking->id,
            'slot_date' => $request->date,
            'slot_time' => $request->slot_time,
            'zoom_link' => $request->mode === 'online' ? ($booking->zoom_join_url ?? null) : null,
            'mentor_address' => $request->mode === 'offline' ? $mentorAddress : null,
        ]);
    }




   public function submitAssessorBooking(Request $request)
    {
        $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'mode' => 'required|in:online,offline',
            'date' => 'required|date',
            'slot_id' => 'required|exists:booking_slots,id',
            'slot_time' => 'required',
        ]);

        $jobseeker = auth('jobseeker')->user();

        // Check if there's already a booking on the same date and time
        $existingBooking = BookingSession::where('jobseeker_id', $jobseeker->id)
            ->where('user_type', 'assessor')
            ->where('user_id', $request->assessor_id)
            ->whereDate('slot_date', $request->date)
            ->where('slot_time', $request->slot_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'You have already booked a session for this date and time.');
        }

        // Create the booking
        $booking = BookingSession::create([
            'jobseeker_id' => $jobseeker->id,
            'user_type' => 'assessor',
            'user_id' => $request->assessor_id,
            'booking_slot_id' => $request->slot_id,
            'slot_date' => $request->date,
            'slot_mode' => $request->mode,
            'slot_time' => $request->slot_time,
            'status' => 'pending',
        ]);

        $assessorAddress = null;

        // If mode is online, create Zoom meeting
        if ($request->mode === 'online') {
            $zoom = new ZoomService();
            $startTime = $request->date . ' ' . explode(' - ', $request->slot_time)[0];
            $zoomMeeting = $zoom->createMeeting("Assessment with #{$jobseeker->id}", $startTime);

            if ($zoomMeeting) {
                $booking->update([
                    'zoom_start_url' => $zoomMeeting['start_url'],
                    'zoom_join_url' => $zoomMeeting['join_url'],
                ]);
            } else {
                \Log::error('Zoom creation failed for assessor booking', [
                    'jobseeker_id' => $jobseeker->id,
                    'assessor_id' => $request->assessor_id,
                    'slot_time' => $request->slot_time,
                ]);
                return redirect()->back()->with('error', 'Zoom meeting creation failed. Please try again later.');
            }
        } elseif ($request->mode === 'offline') {
            // Get assessor address
            $assessor = Assessors::find($request->assessor_id);
            $assessorAddress = $assessor?->address ?? 'Address not available';
        }

        return redirect()->back()->with([
            'success' => 'Session booked successfully.',
            'booking_id' => $booking->id,
            'slot_date' => $request->date,
            'slot_time' => $request->slot_time,
            'zoom_link' => $request->mode === 'online' ? ($booking->zoom_join_url ?? null) : null,
            'assessor_address' => $request->mode === 'offline' ? $assessorAddress : null,
        ]);
    }




    public function submitCoachBooking(Request $request)
    {
        $request->validate([
            'coach_id' => 'required|exists:coaches,id',
            'mode' => 'required|in:online,offline',
            'date' => 'required|date',
            'slot_id' => 'required|exists:booking_slots,id',
            'slot_time' => 'required',
        ]);

        $jobseeker = auth('jobseeker')->user();

        // Check for duplicate booking
        $existingBooking = BookingSession::where('jobseeker_id', $jobseeker->id)
            ->where('user_type', 'coach')
            ->where('user_id', $request->coach_id)
            ->whereDate('slot_date', $request->date)
            ->where('slot_time', $request->slot_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'You have already booked a session for this date and time.');
        }

        // Create booking
        $booking = BookingSession::create([
            'jobseeker_id' => $jobseeker->id,
            'user_type' => 'coach',
            'user_id' => $request->coach_id,
            'booking_slot_id' => $request->slot_id,
            'slot_date' => $request->date,
            'slot_mode' => $request->mode,
            'slot_time' => $request->slot_time,
            'status' => 'pending',
        ]);

        $coachAddress = null;

        // Handle Zoom if online
        if ($request->mode === 'online') {
            $zoom = new ZoomService();
            $startTime = $request->date . ' ' . explode(' - ', $request->slot_time)[0];
            $zoomMeeting = $zoom->createMeeting("Coaching with #{$jobseeker->id}", $startTime);

            if ($zoomMeeting) {
                $booking->update([
                    'zoom_start_url' => $zoomMeeting['start_url'],
                    'zoom_join_url' => $zoomMeeting['join_url'],
                ]);
            } else {
                \Log::error('Zoom creation failed for coach booking', [
                    'jobseeker_id' => $jobseeker->id,
                    'coach_id' => $request->coach_id,
                    'slot_time' => $request->slot_time,
                ]);
                return redirect()->back()->with('error', 'Zoom meeting creation failed. Please try again later.');
            }
        } elseif ($request->mode === 'offline') {
            // Get coach address
            $coach = Coach::find($request->coach_id);
            $coachAddress = $coach?->address ?? 'Address not available';
        }

        // Redirect with session values
        return redirect()->back()->with([
            'success' => 'Session booked successfully.',
            'booking_id' => $booking->id,
            'slot_date' => $request->date,
            'slot_time' => $request->slot_time,
            'zoom_link' => $request->mode === 'online' ? ($booking->zoom_join_url ?? null) : null,
            'coach_address' => $request->mode === 'offline' ? $coachAddress : null,
        ]);
    }




   public function courseDetails($id)
    {
        $jobseeker = auth()->guard('jobseeker')->user();
        $jobseekerId = auth()->guard('jobseeker')->id();

        $material = DB::table('training_materials')->where('id', $id)->first();
        if (!$material) {
            abort(404, 'Course not found');
        }

        $material->documents = DB::table('training_materials_documents')
            ->where('training_material_id', $material->id)
            ->get();

        $material->batches = DB::table('training_batches')
            ->where('training_material_id', $material->id)
            ->get();

        $cartItems = JobseekerCartItem::where('jobseeker_id', auth('jobseeker')->id())
            ->pluck('material_id')
            ->toArray();

        $userType = null;
        $userId = null;
        $user = null;
        $profile = null;

        // Detect user type and assign proper id + table
        if (!empty($material->trainer_id)) {
            $userType = 'trainer';
            $userId   = $material->trainer_id;
            $user     = DB::table('trainers')->where('id', $userId)->first();
            $profile  = DB::table('additional_info')
                ->where('user_id', $userId)
                ->where('user_type', 'trainer')
                ->where('doc_type', 'trainer_profile_picture')
                ->orderByDesc('id')
                ->first();

        } elseif (!empty($material->mentor_id)) {
            $userType = 'mentor';
            $userId   = $material->mentor_id;
            $user     = DB::table('mentors')->where('id', $userId)->first();
            $profile  = DB::table('additional_info')
                ->where('user_id', $userId)
                ->where('user_type', 'mentor')
                ->where('doc_type', 'mentor_profile_picture')
                ->orderByDesc('id')
                ->first();

        } elseif (!empty($material->coach_id)) {
            $userType = 'coach';
            $userId   = $material->coach_id;
            $user     = DB::table('coaches')->where('id', $userId)->first();
            $profile  = DB::table('additional_info')
                ->where('user_id', $userId)
                ->where('user_type', 'coach')
                ->where('doc_type', 'coach_profile_picture')
                ->orderByDesc('id')
                ->first();

        } elseif (!empty($material->assessor_id)) {
            $userType = 'assessor';
            $userId   = $material->assessor_id;
            $user     = DB::table('assessors')->where('id', $userId)->first();
            $profile  = DB::table('additional_info')
                ->where('user_id', $userId)
                ->where('user_type', 'assessor')
                ->where('doc_type', 'assessor_profile_picture')
                ->orderByDesc('id')
                ->first();
        }

        if (!$userType || !$userId || !$user) {
            abort(404, 'User info not found');
        }

        $material->user_name    = $user->name ?? '';
        $material->user_profile = $profile->document_path ?? asset('asset/images/avatar.png');

        // Ratings and reviews
        $total = DB::table('reviews')
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->count();

        $average = $total > 0
            ? round(DB::table('reviews')
                ->where('user_type', $userType)
                ->where('user_id', $userId)
                ->when($userType === 'trainer', function ($q) use ($material) {
                    $q->where('trainer_material', $material->id);
                })
                ->avg('ratings'), 1)
            : 0;

        $ratings = DB::table('reviews')
            ->select('ratings', DB::raw('COUNT(*) as count'))
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->groupBy('ratings')
            ->pluck('count', 'ratings');

        $ratingsPercent = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratings[$i] ?? 0;
            $ratingsPercent[$i] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
        }

        $reviews = DB::table('reviews as r')
            ->join('jobseekers as j', 'r.jobseeker_id', '=', 'j.id')
            ->select('r.*', 'j.name as jobseeker_name')
            ->where('r.user_type', $userType)
            ->where('r.user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('r.trainer_material', $material->id);
            })
            ->latest('r.created_at')
            ->limit(10)
            ->get();

        return view('site.training-detail', compact(
            'material', 'user', 'userType', 'userId', 'average', 'ratingsPercent', 'reviews', 'cartItems'
        ));
    }





    
    public function addToCart(Request $request, $id)
    {
        if (!Auth::guard('jobseeker')->check()) {
            return response()->json(['message' => 'Please log in to add items to your cart.'], 401);
        }

        $jobseekerId = Auth::guard('jobseeker')->id();
        $material = TrainingMaterial::find($id);

        if (!$material) {
            return response()->json(['message' => 'Invalid material ID.'], 400);
        }

        $exists = JobseekerCartItem::where('jobseeker_id', $jobseekerId)
            ->where('material_id', $id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Item is already in your cart.'], 200);
        }

        JobseekerCartItem::create([
            'jobseeker_id' => $jobseekerId,
            'trainer_id' => $material->trainer_id,
            'material_id' => $id,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Item added to cart successfully.']);
    }





public function submitReview(Request $request)
{
    $jobseeker = auth()->guard('jobseeker')->user();
    if (!$jobseeker) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $allowedTypes = ['trainer', 'mentor', 'coach', 'assessor'];

    $request->validate([
        'user_type'   => 'required|string|in:' . implode(',', $allowedTypes),
        'ratings'     => 'required|integer|min:1|max:5',
        'reviews'     => 'required|string',
        'material_id' => 'nullable|integer',
    ]);

    $userId = $request->user_id;

  
    if ($request->user_type === 'trainer' && $request->filled('material_id')) {
        $material = DB::table('training_materials')->where('id', $request->material_id)->first();
        if ($material) {
            $userId = $material->trainer_id;  
        }
    }

    $data = [
        'jobseeker_id'    => $jobseeker->id,
        'user_type'       => $request->user_type,
        'user_id'         => $userId,  
        'reviews'         => $request->reviews,
        'ratings'         => $request->ratings,
        'trainer_material'=> $request->user_type === 'trainer' ? $request->material_id : null,
        'created_at'      => now(),
        'updated_at'      => now(),
    ];

    DB::table('reviews')->insert($data);

    return response()->json([
        'success' => true,
        'review' => [
            'jobseeker_id'   => $jobseeker->id,
            'jobseeker_name' => $jobseeker->name,
            'user_type'      => $request->user_type,
            'user_id'        => $userId, 
            'material_id'    => $request->material_id,
            'ratings'        => $request->ratings,
            'reviews'        => $request->reviews,
        ]
    ]);
}



    public function buyCourseDetails($id)
    {
        $material = DB::table('training_materials')->where('id', $id)->first();
        if (!$material) {
            abort(404, 'Course not found');
        }

        $material->documents = DB::table('training_materials_documents')
            ->where('training_material_id', $material->id)
            ->get();

        $material->batches = DB::table('training_batches')
            ->where('training_material_id', $material->id)
            ->get();

        $userType = null;
        $userId = null;
        $user = null;

        // Detect user type and get basic info
        if (!empty($material->trainer_id)) {
            $userType = 'trainer';
            $userId = $material->trainer_id;
            $user = DB::table('trainers')->where('id', $userId)->first();
        } elseif (!empty($material->mentor_id)) {
            $userType = 'mentor';
            $userId = $material->mentor_id;
            $user = DB::table('mentors')->where('id', $userId)->first();
        } elseif (!empty($material->coach_id)) {
            $userType = 'coach';
            $userId = $material->coach_id;
            $user = DB::table('coaches')->where('id', $userId)->first();
        } elseif (!empty($material->assessor_id)) {
            $userType = 'assessor';
            $userId = $material->assessor_id;
            $user = DB::table('assessors')->where('id', $userId)->first();
        }

        if (!$userType || !$userId || !$user) {
            abort(404, 'User info not found');
        }

        // Fetch profile picture from talentrek_additional_info
        $profile = DB::table('additional_info')
            ->where('user_id', $userId)
            ->where('user_type', 'trainer')
            ->where('doc_type', 'trainer_profile_picture')
            ->orderByDesc('id')
            ->first();

        $material->user_name = $user->name ?? '';
        $material->user_profile = $profile->document_path ?? asset('asset/images/avatar.png');

        // Ratings and reviews
        $total = DB::table('reviews')
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->count();

        $average = $total > 0
            ? round(DB::table('reviews')
                ->where('user_type', $userType)
                ->where('user_id', $userId)
                ->when($userType === 'trainer', function ($q) use ($material) {
                    $q->where('trainer_material', $material->id);
                })
                ->avg('ratings'), 1)
            : 0;

        $ratings = DB::table('reviews')
            ->select('ratings', DB::raw('COUNT(*) as count'))
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->groupBy('ratings')
            ->pluck('count', 'ratings');

        $ratingsPercent = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratings[$i] ?? 0;
            $ratingsPercent[$i] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
        }

        $reviews = DB::table('reviews as r')
            ->join('jobseekers as j', 'r.jobseeker_id', '=', 'j.id')
            ->select('r.*', 'j.name as jobseeker_name')
            ->where('r.user_type', $userType)
            ->where('r.user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('r.trainer_material', $material->id);
            })
            ->latest('r.created_at')
            ->limit(10)
            ->get();

        return view('site.buy-course', compact(
            'material',
            'user',
            'userType',
            'userId',
            'average',
            'ratingsPercent',
            'reviews'
        ));
    }



    public function buyTeamCourseDetails($id)
    {
        $material = DB::table('training_materials')->where('id', $id)->first();
        if (!$material) {
            abort(404, 'Course not found');
        }

        $material->documents = DB::table('training_materials_documents')
            ->where('training_material_id', $material->id)
            ->get();

        $material->batches = DB::table('training_batches')
            ->where('training_material_id', $material->id)
            ->get();

        $userType = null;
        $userId = null;
        $user = null;

        // Detect user type and get basic info
        if (!empty($material->trainer_id)) {
            $userType = 'trainer';
            $userId = $material->trainer_id;
            $user = DB::table('trainers')->where('id', $userId)->first();
        } elseif (!empty($material->mentor_id)) {
            $userType = 'mentor';
            $userId = $material->mentor_id;
            $user = DB::table('mentors')->where('id', $userId)->first();
        } elseif (!empty($material->coach_id)) {
            $userType = 'coach';
            $userId = $material->coach_id;
            $user = DB::table('coaches')->where('id', $userId)->first();
        } elseif (!empty($material->assessor_id)) {
            $userType = 'assessor';
            $userId = $material->assessor_id;
            $user = DB::table('assessors')->where('id', $userId)->first();
        }

        if (!$userType || !$userId || !$user) {
            abort(404, 'User info not found');
        }

        // Fetch profile picture from talentrek_additional_info
        $profile = DB::table('additional_info')
            ->where('user_id', $userId)
            ->where('user_type', 'trainer')
            ->where('doc_type', 'trainer_profile_picture')
            ->orderByDesc('id')
            ->first();

        $material->user_name = $user->name ?? '';
        $material->user_profile = $profile->document_path ?? asset('asset/images/avatar.png');

        // Ratings and reviews
        $total = DB::table('reviews')
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->count();

        $average = $total > 0
            ? round(DB::table('reviews')
                ->where('user_type', $userType)
                ->where('user_id', $userId)
                ->when($userType === 'trainer', function ($q) use ($material) {
                    $q->where('trainer_material', $material->id);
                })
                ->avg('ratings'), 1)
            : 0;

        $ratings = DB::table('reviews')
            ->select('ratings', DB::raw('COUNT(*) as count'))
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('trainer_material', $material->id);
            })
            ->groupBy('ratings')
            ->pluck('count', 'ratings');

        $ratingsPercent = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratings[$i] ?? 0;
            $ratingsPercent[$i] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
        }

        $reviews = DB::table('reviews as r')
            ->join('jobseekers as j', 'r.jobseeker_id', '=', 'j.id')
            ->select('r.*', 'j.name as jobseeker_name')
            ->where('r.user_type', $userType)
            ->where('r.user_id', $userId)
            ->when($userType === 'trainer', function ($q) use ($material) {
                $q->where('r.trainer_material', $material->id);
            })
            ->latest('r.created_at')
            ->limit(10)
            ->get();

        return view('site.buy-course-for-team', compact(
            'material',
            'user',
            'userType',
            'userId',
            'average',
            'ratingsPercent',
            'reviews'
        ));
    }


    public function purchaseCourse(Request $request)
    {
        if (!auth('jobseeker')->check()) {
            return redirect()->back()->with('error', 'Please log in as a Jobseeker to purchase a course.');
        }

        $request->validate([
            'training_type' => 'required|in:online,classroom,recorded',
            'session_type' => 'required_if:training_type,online|in:online,classroom',
            'batch' => 'required_if:training_type,online|exists:training_batches,id',
            'payment_method' => 'required|in:card,upi'
        ]);

        DB::beginTransaction();

        try {
            $material = TrainingMaterial::with('batches')->findOrFail($request->material_id);

            $actualPrice = $material->training_price;
            $offerPrice = $material->training_offer_price;
            $savedAmount = $actualPrice - $offerPrice;
            $tax = round($offerPrice * 0.10, 2);
            $total = $offerPrice + $tax;

            JobseekerTrainingMaterialPurchase::create([
                'jobseeker_id' => auth('jobseeker')->id(),
                'trainer_id' => $material->trainer_id,
                'material_id' => $material->id,
                'training_type' => $request->training_type,
                'session_type' => $request->session_type,
                'batch_id' => $request->batch,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'tax' => $tax,
                'discount' => $savedAmount,
                'status' => 'paid',
            ]);

            DB::commit();
            return redirect()->route('course.details', $material->id)->with('success', 'Course purchased successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course purchase failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while processing your purchase.');
        }
    }




    public function teamPurchaseCourse(Request $request)
    {
        if (!auth('jobseeker')->check()) {
            return redirect()->back()->with('error', 'Please log in as a Jobseeker to purchase a course.');
        }

        $request->validate([
            'training_type'   => 'required|in:online,classroom,recorded',
            'session_type'    => 'required_if:training_type,online,classroom|in:online,classroom',
            'batch'           => 'required_if:training_type,online,classroom|exists:training_batches,id',
            'payment_method'  => 'required|in:card,upi,bank',
            'member_count'    => 'required|integer|min:2',
            'member_emails'   => 'required|array|min:2',
            'member_emails.*' => 'required|email'
        ]);

        DB::beginTransaction();

        try {
            $material = TrainingMaterial::with('batches')->findOrFail($request->material_id);

            $actualPrice = $material->training_price;
            $offerPrice  = $material->training_offer_price;
            $savedAmount = $actualPrice - $offerPrice;

            // Total price per member
            $taxPerMember   = round($offerPrice * 0.10, 2); // 10% tax
            $totalPerMember = $offerPrice + $taxPerMember;

            // Multiply by team size
            $memberCount = (int)$request->member_count;
            $grandTotal  = $totalPerMember * $memberCount;
            $grandTax    = $taxPerMember * $memberCount;
            $grandSaved  = $savedAmount * $memberCount;

            // Create purchase
            $purchase = JobseekerTrainingMaterialPurchase::create([
                'jobseeker_id'   => auth('jobseeker')->id(),
                'trainer_id'     => $material->trainer_id,
                'material_id'    => $material->id,
                'training_type'  => $request->training_type,
                'session_type'   => $request->session_type ?? null,
                'batch_id'       => $request->batch ?? null,
                'payment_method' => $request->payment_method,
                'amount'         => $grandTotal,
                'tax'            => $grandTax,
                'discount'       => $grandSaved,
                'status'         => 'paid',
                'member_count'   => $memberCount,
            ]);

            // Save member emails in both tables
            foreach ($request->member_emails as $email) {
                // Insert into team_course_members
                DB::table('team_course_members')->insert([
                    'purchase_id' => $purchase->id,
                    'email'       => $email,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                // Create Jobseeker account if not exists
                // Create Jobseeker account if not exists
                $existing = Jobseekers::where('email', $email)->first();
                if (!$existing) {
                    $username = strstr($email, '@', true);

                    // Create new password based on email prefix
                    $password = $username . '@talentrek';

                    Jobseekers::create([
                        'name'     => 'Team Member', // optional, you can customize
                        'email'    => $email,
                        'password' => Hash::make($password), // store securely
                        'pass'     => $password,             // store plain text if column exists
                    ]);

                    // Optional: send credentials via email
                    // Mail::to($email)->send(new TeamMemberCredentialsMail($email, $password));
                }

            }

            DB::commit();

            return redirect()->route('course.details', $material->id)
                ->with('success', 'Course purchased successfully for your team!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Team course purchase failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while processing your purchase.');
        }
    }



    // public function viewAssessment($id)
    // {
    //     $assessment = TrainerAssessment::where('material_id', $id)
    //         ->with(['questions.options'])
    //         ->firstOrFail();

    //     $jobseekerId = Auth::guard('jobseeker')->id();

    //     $answeredData = JobseekerAssessmentData::where([
    //         ['assessment_id', '=', $assessment->id],
    //         ['jobseeker_id', '=', $jobseekerId],
    //     ])->get();

    //     $answeredAnswers = $answeredData->mapWithKeys(function ($answer) {
    //         return [$answer->question_id => $answer->selected_answer];
    //     });

    //     $answeredIds = $answeredData->pluck('question_id')->toArray();

    //     $sessionKey = 'quiz_start_time_' . $assessment->id . '_' . $jobseekerId;

    //     if (!session()->has($sessionKey)) {
    //         session([$sessionKey => now()]);
    //     }

    //     $startTime = session($sessionKey);
    //     $duration = 3600;
    //     $elapsed = now()->diffInSeconds($startTime);
    //     $remainingTime = max($duration - $elapsed, 0);

    //     $quizQuestions = $assessment->questions->map(function ($q) use ($assessment, $answeredAnswers) {
    //         $options = $q->options->pluck('options')->toArray();
    //         $selectedAnswer = $answeredAnswers[$q->id] ?? null;
    //         $selectedIndex = is_null($selectedAnswer) ? null : array_search($selectedAnswer, $options);

    //         return [
    //             'id' => $q->id,
    //             'question' => $q->questions_title,
    //             'options' => $options,
    //             'correct_option' => $q->options->firstWhere('correct_option', 1)?->options,
    //             'trainer_id' => $assessment->trainer_id,
    //             'material_id' => $assessment->material_id,
    //             'assessment_id' => $assessment->id,
    //             'totalQuestions' => $assessment->total_questions,
    //             'passingQuestions' => $assessment->passing_questions,
    //             'selected_index' => $selectedIndex,
    //         ];
    //     });

    //     $lastIndex = $answeredData->count();

    //     $alreadySubmitted = JobseekerAssessmentStatus::where([
    //         ['assessment_id', '=', $assessment->id],
    //         ['jobseeker_id', '=', $jobseekerId],
    //         ['submitted', '=', true],
    //     ])->exists();


    //     $resultStatus = JobseekerAssessmentStatus::where([
    //                         ['assessment_id', '=', $assessment->id],
    //                         ['jobseeker_id', '=', $jobseekerId],
    //                         ['submitted', '=', true],
    //                     ])->first();

    //     return view('site.jobseeker.assessment', compact(
    //         'assessment',
    //         'quizQuestions',
    //         'answeredIds',
    //         'remainingTime',
    //         'lastIndex',
    //         'alreadySubmitted',
    //         'resultStatus'
    //     ));

    // }
    public function viewAssessment($id)
    {
        $assessment = TrainerAssessment::where('material_id', $id)
            ->with(['questions.options'])
            ->firstOrFail();

        $jobseekerId = Auth::guard('jobseeker')->id();

        // Answered data
        $answeredData = JobseekerAssessmentData::where([
            ['assessment_id', '=', $assessment->id],
            ['jobseeker_id', '=', $jobseekerId],
        ])->get();

        $answeredAnswers = $answeredData->mapWithKeys(fn($a) => [$a->question_id => $a->selected_answer]);
        $answeredIds = $answeredData->pluck('question_id')->toArray();

        // Assessment time
        $duration = 3600; // 60 min
        $assessmentTime = JobseekerTrainingAssessmentTime::firstOrCreate(
            [
                'jobseeker_id' => $jobseekerId,
                'trainer_id'   => $assessment->trainer_id,
                'material_id'  => $assessment->material_id,
            ],
            [
                'start_time'     => now(),
                'end_time'       => now()->copy()->addSeconds($duration),
                'total_duration' => $duration,
                'remaining_time' => $duration,
                'status'         => 0,
            ]
        );

        $remainingTime = ($assessmentTime->status == 1) ? 0 : $assessmentTime->remaining_time;
        if ($remainingTime <= 0) {
            $assessmentTime->update(['status' => 2]);
        }

        // Questions
        $quizQuestions = $assessment->questions->map(function ($q) use ($assessment, $answeredAnswers) {
            $options = $q->options->pluck('options')->toArray();
            $selectedAnswer = $answeredAnswers[$q->id] ?? null;
            $selectedIndex = is_null($selectedAnswer) ? null : array_search($selectedAnswer, $options);

            return [
                'id' => $q->id,
                'question' => $q->questions_title,
                'options' => $options,
                'correct_option' => $q->options->firstWhere('correct_option', 1)?->options,
                'trainer_id' => $assessment->trainer_id,
                'material_id' => $assessment->material_id,
                'assessment_id' => $assessment->id,
                'totalQuestions' => $assessment->total_questions,
                'passingQuestions' => $assessment->passing_questions,
                'selected_index' => $selectedIndex,
            ];
        });

        $lastIndex = $answeredData->count();
        $alreadySubmitted = JobseekerAssessmentStatus::where([
            ['assessment_id', '=', $assessment->id],
            ['jobseeker_id', '=', $jobseekerId],
            ['submitted', '=', true],
        ])->exists();

        $resultStatus = JobseekerAssessmentStatus::where([
            ['assessment_id', '=', $assessment->id],
            ['jobseeker_id', '=', $jobseekerId],
            ['submitted', '=', true],
        ])->first();

        return view('site.jobseeker.assessment', compact(
            'assessment',
            'quizQuestions',
            'answeredIds',
            'remainingTime',
            'lastIndex',
            'alreadySubmitted',
            'resultStatus'
        ));
    }

    public function updateRemainingTime(Request $request)
    {
        $jobseekerId = Auth::guard('jobseeker')->id();

        $request->validate([
            'material_id' => 'required|integer',
            'remaining_time' => 'required|integer|min:0',
        ]);

        $record = JobseekerTrainingAssessmentTime::where([
            'jobseeker_id' => $jobseekerId,
            'material_id'  => $request->material_id,
        ])->first();

        if ($record) {
            $record->update([
                'remaining_time' => $request->remaining_time,
                'status' => $request->remaining_time > 0 ? 0 : 2,
            ]);
        }

        return response()->json(['success' => true]);
    }





    public function saveJobseekerAnswer(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|integer',
            'material_id' => 'required|integer',
            'assessment_id' => 'required|integer',
            'question_id' => 'required|integer',
            'selected_answer' => 'required|string',
            'correct_answer' => 'required|string',
        ]);

        $jobseekerId = Auth::guard('jobseeker')->id();

        JobseekerAssessmentData::updateOrCreate(
            [
                'trainer_id' => $request->trainer_id,
                'training_id' => $request->material_id,
                'assessment_id' => $request->assessment_id,
                'question_id' => $request->question_id,
                'jobseeker_id' => $jobseekerId,
            ],
            [
                'selected_answer' => $request->selected_answer,
                'correct_answer' => $request->correct_answer,
            ]
        );

        return response()->json(['message' => 'Answer saved.']);
    }

    public function submitQuiz(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $jobseekerId = Auth::guard('jobseeker')->id();
        $correctCount = 0;
        $assessmentId = null;

        foreach ($request->answers as $answer) {
            if (!isset($answer['trainer_id'], 
                    $answer['material_id'], 
                    $answer['assessment_id'], 
                    $answer['question_id'], 
                    $answer['selected_answer'], 
                    $answer['correct_answer']
                )) {
                continue;
            }

            $assessmentId = $answer['assessment_id'];
            $materialId = $answer['material_id'];

            if ($answer['selected_answer'] === $answer['correct_answer']) {
                $correctCount++;
            }

            JobseekerAssessmentData::updateOrCreate(
                [
                    'trainer_id'   => $answer['trainer_id'],
                    'training_id'  => $answer['material_id'],
                    'assessment_id'=> $answer['assessment_id'],
                    'question_id'  => $answer['question_id'],
                    'jobseeker_id' => $jobseekerId,
                ],
                [
                    'selected_answer' => $answer['selected_answer'],
                    'correct_answer'  => $answer['correct_answer'],
                ]
            );
        }

        if ($assessmentId) {
            $assessmentData = TrainerAssessment::find($assessmentId);
            $totalQuestions = count($request->answers);

            $rawPercentage = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
            $percentage = min(100, max(0, round($rawPercentage)));

            $passingPercentage = $assessmentData ? $assessmentData->passing_percentage : 0;
            $resultStatus = $percentage >= $passingPercentage ? 'pass' : 'fail';

            JobseekerAssessmentStatus::updateOrCreate(
                [
                    'jobseeker_id' => $jobseekerId,
                    'assessment_id' => $assessmentId,
                    'material_id' => $materialId,
                ],
                [
                    'submitted'     => true,
                    'score'         => $correctCount,
                    'total'         => $totalQuestions,
                    'percentage'    => $percentage,
                    'result_status' => $resultStatus,
                ]
            );

            // Clear quiz timer
            $sessionKey = 'quiz_start_time_' . $assessmentId . '_' . $jobseekerId;
            session()->forget($sessionKey);

            // Flash result to session (for showing in UI after redirect)
            session()->flash('quiz_result', [
                'score'         => $correctCount,
                'total'         => $totalQuestions,
                'percentage'    => $percentage,
                'result_status' => $resultStatus,
            ]);
        }

        // ✅ Redirect instead of returning JSON
        return redirect()->route('jobseeker.profile')->with('success', 'Quiz submitted successfully.');
    }



    public function quizSuccess()
    {
        return view('site.jobseeker.quiz_success');
    }

    public function viewScore($id)
    {
        $jobseekerId = Auth::guard('jobseeker')->id();

        $assessment = TrainerAssessment::with(['questions.options'])->findOrFail($id);

        // Check if submitted
        $status = JobseekerAssessmentStatus::where([
            ['assessment_id', '=', $id],
            ['jobseeker_id', '=', $jobseekerId],
            ['submitted', '=', '1'],
        ])->first();

        if (!$status) {
            return redirect()->back()->with('error', 'You have not submitted this assessment yet.');
        }

        // Fetch all submitted answers
        $answers = JobseekerAssessmentData::where([
            ['assessment_id', '=', $id],
            ['jobseeker_id', '=', $jobseekerId],
        ])->get();

        // Map of question_id => selected_answer
        $answeredMap = $answers->keyBy('question_id');
        $answeredAnswers = $answeredMap->map(fn($a) => $a->selected_answer)->toArray();

        // Build result data
        $questionsWithAnswers = $assessment->questions->map(function ($question) use ($answeredMap) {
            $answer = $answeredMap->get($question->id);
            $options = $question->options->pluck('options')->toArray();
            $correctAnswer = $question->options->firstWhere('correct_option', 1)?->options;
            $selectedAnswer = $answer?->selected_answer;

            return [
                'id' => $question->id,
                'question' => $question->questions_title,
                'options' => $options,
                'correct_answer' => $correctAnswer,
                'selected_answer' => $selectedAnswer,
                'is_correct' => $selectedAnswer === $correctAnswer,
            ];
        });

        $totalQuestions = $assessment->total_questions;
        $correctAnswers = $questionsWithAnswers->where('is_correct', true)->count();
        $score = $correctAnswers;
        $passingScore = $assessment->passing_questions;
        $resultStatus = $score >= $passingScore ? 'Passed' : 'Failed';

        // This is the structure used by the quiz/result UI
        $quizQuestions = $assessment->questions->map(function ($question) use ($assessment, $answeredAnswers) {
            $options = $question->options->pluck('options')->toArray();
            $selectedAnswer = $answeredAnswers[$question->id] ?? null;
            $selectedIndex = is_null($selectedAnswer) ? null : array_search($selectedAnswer, $options);
            $correctAnswer = $question->options->firstWhere('correct_option', 1)?->options;
            $correctIndex = array_search($correctAnswer, $options);

            return [
                'id' => $question->id,
                'question' => $question->questions_title,
                'options' => $options,
                'correct_option' => $correctAnswer,
                'correct_index' => $correctIndex,
                'trainer_id' => $assessment->trainer_id,
                'material_id' => $assessment->material_id,
                'assessment_id' => $assessment->id,
                'totalQuestions' => $assessment->total_questions,
                'passingQuestions' => $assessment->passing_questions,
                'selected_index' => $selectedIndex,
            ];
        });

        return view('site.jobseeker.assessment-result', compact(
            'assessment',
            'score',
            'totalQuestions',
            'resultStatus',
            'questionsWithAnswers',
            'quizQuestions'
        ));
    }



    public function assessorDetails($id)
    {
        $assessor = Assessors::with([
            'reviews.jobseeker',
            'additionalInfo',
            'profilePicture',
            'experiences',
            'educations' => function ($q) {
                $q->where('user_type', 'assessor')->orderBy('id')->limit(1);
            },

            'bookingSlots'
        ])
            ->where('id', $id)
            ->firstOrFail();

        // Calculate total experience
        $experiences = DB::table('work_experience')
            ->where('user_id', $id)
            ->where('user_type', 'assessor')
            ->get();

        $totalDays = 0;

        foreach ($experiences as $exp) {
            $start = Carbon::parse($exp->starts_from);

            if (!empty($exp->end_to) && strtolower($exp->end_to) !== 'work here') {
                $end = Carbon::parse($exp->end_to);
            } else {
                // Assume current date if still working
                $end = Carbon::now();
            }

            $totalDays += $start->diffInDays($end);
        }

        $interval = CarbonInterval::days($totalDays)->cascade();
        $totalExperience = sprintf('%d years %d months %d days', $interval->y, $interval->m, $interval->d);

        // Pass experience as property (optional)
        $assessor->total_experience = $totalExperience;



        $reviews = DB::table('reviews')
            ->join('jobseekers', 'reviews.jobseeker_id', '=', 'jobseekers.id')
            ->where('reviews.user_type', 'assessor')
            ->select(
                'reviews.*',
                'jobseekers.name as jobseeker_name'
            )
            ->get();



        // echo "<pre>";
        // print_r($reviews);exit;
        // echo "</pre>";

        return view('site.assessment-details', compact('assessor', 'reviews'));
    }

    public function coachDetails($id)
    {

        $coach = Coach::with([
            'reviews.jobseeker',
            'additionalInfo',
            'profilePicture',
            'experiences',
            'educations' => function ($q) {
                $q->where('user_type', 'coach')->orderBy('id')->limit(1);
            },

            'bookingSlots'
        ])
            ->where('id', $id)
            ->firstOrFail();

        // Calculate total experience
        $experiences = DB::table('work_experience')
            ->where('user_id', $id)
            ->where('user_type', 'coach')
            ->get();
        $totalDays = 0;

        foreach ($experiences as $exp) {
            $start = Carbon::parse($exp->starts_from);

            if (!empty($exp->end_to) && strtolower($exp->end_to) !== 'work here') {
                $end = Carbon::parse($exp->end_to);
            } else {
                // Assume current date if still working
                $end = Carbon::now();
            }

            $totalDays += $start->diffInDays($end);
        }

        $interval = CarbonInterval::days($totalDays)->cascade();
        $totalExperience = sprintf('%d years %d months %d days', $interval->y, $interval->m, $interval->d);

        // Pass experience as property (optional)
        $coach->total_experience = $totalExperience;


        $reviews = DB::table('reviews')
            ->join('jobseekers', 'reviews.jobseeker_id', '=', 'jobseekers.id')
            ->where('reviews.user_type', 'coach')
            ->select(
                'reviews.*',
                'jobseekers.name as jobseeker_name'
            )
            ->get();

        // echo "<pre>";
        // print_r($reviews);exit;
        // echo "</pre>";

        return view('site.coach-details', compact('coach', 'reviews'));
    }

    public function submitAssessorReview(Request $request)
    {
        $jobseeker = auth()->guard('jobseeker')->user();

        if (!$jobseeker) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'assessor_id' => 'required|integer',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $reviewId = DB::table('reviews')->insertGetId([
            'jobseeker_id' => $jobseeker->id,
            'user_type' => 'assessor',
            'user_id' => $request->assessor_id,
            'reviews' => $request->review,
            'ratings' => $request->rating,
            'trainer_material' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => 'Review submitted successfully',
            'review' => [
                'jobseeker_name' => $jobseeker->name ?? 'Anonymous',
                'reviews' => $request->review,
                'ratings' => $request->rating,
            ]
        ]);
    }
    public function submitCoachReview(Request $request)
    {
        $jobseeker = auth()->guard('jobseeker')->user();

        if (!$jobseeker) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'coach_id' => 'required|integer',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $reviewId = DB::table('reviews')->insertGetId([
            'jobseeker_id' => $jobseeker->id,
            'user_type' => 'coach',
            'user_id' => $request->coach_id,
            'reviews' => $request->review,
            'ratings' => $request->rating,
            'trainer_material' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => 'Review submitted successfully',
            'review' => [
                'jobseeker_name' => $jobseeker->name ?? 'Anonymous',
                'reviews' => $request->review,
                'ratings' => $request->rating,
            ]
        ]);
    }

    public function submitMentorReview(Request $request)
    {
        $jobseeker = auth()->guard('jobseeker')->user();

        if (!$jobseeker) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'mentor_id' => 'required|integer',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $reviewId = DB::table('reviews')->insertGetId([
            'jobseeker_id' => $jobseeker->id,
            'user_type' => 'mentor',
            'user_id' => $request->mentor_id,
            'reviews' => $request->review,
            'ratings' => $request->rating,
            'trainer_material' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => 'Review submitted successfully',
            'review' => [
                'jobseeker_name' => $jobseeker->name ?? 'Anonymous',
                'reviews' => $request->review,
                'ratings' => $request->rating,
            ]
        ]);
    }


    public function removeCartItem($id)
    {
        $item = JobseekerCartItem::where('id', $id)
            ->where('jobseeker_id', auth('jobseeker')->id())
            ->first();
   
        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
        }

        $item->delete();

        return response()->json(['status' => 'success', 'message' => 'Item removed']);
    }


  
    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();

    //         $jobseeker = Jobseekers::where('email', $googleUser->getEmail())->first();

    //         if (!$jobseeker) {
    //             // Auto-register new jobseeker
    //             $jobseeker = Jobseekers::create([
    //                 'name' => $googleUser->getName(),
    //                 'email' => $googleUser->getEmail(),
    //                 'status' => 'active', // or 'pending' if you require manual approval
    //                 'password' => bcrypt(Str::random(16)), // Random placeholder password
    //                 'email_verified_at' => now(),
    //                 // other fields if needed
    //             ]);
    //         }

    //         if ($jobseeker->status !== 'active') {
    //             session()->flash('error', 'Your account is inactive. Please contact administrator.');
    //             return redirect()->route('signin.form');
    //         }

    //         Auth::guard('jobseeker')->login($jobseeker);
    //         return redirect()->intended(route('jobseeker.dashboard'));

    //     } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
    //         session()->flash('error', 'Invalid state. Please try again.');
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Google login failed. Please try again.');
    //     }

    //     return redirect()->route('signin.form');
    // }
   
    public function redirectToGoogle()

    {

        return Socialite::driver('google')
            ->redirectUrl(config('services.google.jobseeker_redirect'))
            ->redirect();

    }





   public function handleGoogleCallback()
    {
        try {
            
            $googleUser = Socialite::driver('google')->stateless()->user();

           
            $jobseeker = Jobseekers::where('email', $googleUser->getEmail())->first();

            
            if (!$jobseeker) {
                $plainPassword = Str::random(16);

                $jobseeker = Jobseekers::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'status'            => 'active',
                    'password'          => bcrypt($plainPassword),
                    'pass'              => $plainPassword,
                    'email_verified_at' => now(),
                    'is_registered'     => 0, // not registered yet
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                ]);

                
                session([
                    'jobseeker_id' => $jobseeker->id,
                    'email'        => $jobseeker->email,
                ]);

                // Redirect to registration form
                return redirect()->route('jobseeker.registration');
            }

            // Case 2: Inactive account
            if ($jobseeker->status !== 'active') {
                return redirect()
                    ->route('jobseeker.sign-in')
                    ->with('error', 'Your account is inactive. Please contact administrator.');
            }

            
            if ($jobseeker->is_registered == 1) {
                Auth::guard('jobseeker')->login($jobseeker);

                return redirect()->route('jobseeker.profile');
            }

            
            session([
                'jobseeker_id' => $jobseeker->id,
                'email'        => $jobseeker->email,
            ]);

            return redirect()->route('jobseeker.registration');

        } catch (\Exception $e) {
            return redirect()
                ->route('jobseeker.sign-in')
                ->with('error', 'Google login failed. Please try again.');
        }
    }



    public function applyCoupon(Request $request)
    {
        $today = Carbon::today();
        $code = trim($request->input('code', ''));
        $price = (float) $request->input('price', 0);

        if ($code === '' || $price <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid code or price.']);
        }

        $coupon = Coupon::where('is_active', 1)
            ->where('code', $code)
            ->whereDate('valid_from', '<=', $today)
            ->whereDate('valid_to', '>=', $today)
            ->first();

        if (! $coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon']);
        }

        $discount = 0.0;
        $type = strtolower(trim($coupon->discount_type ?? ''));

        if ($type === 'percentage' || $type === 'percent') {
            $percent = (float) $coupon->discount_value;
            $discount = round($price * ($percent / 100), 2);
        } else { // assume fixed
            $discount = round((float) $coupon->discount_value, 2);
        }

        // Prevent discount > price
        if ($discount > $price) {
            $discount = $price;
        }

        $newPrice = round($price - $discount, 2);
        if ($newPrice < 0) $newPrice = 0.00;

        $tax = round($newPrice * 0.10, 2); // 10% tax on final price
        $total = round($newPrice + $tax, 2);

        return response()->json([
            'success'  => true,
            'discount' => $discount,
            'newPrice' => $newPrice,
            'tax'      => $tax,
            'total'    => $total,
            'message'  => 'Coupon applied successfully'
        ]);
    }



}