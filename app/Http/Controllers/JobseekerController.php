<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\Trainers;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\Skills;
use App\Models\Mentors;
use App\Models\BookingSession;
use App\Models\BookingSlot;
use App\Models\AdditionalInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;   
use DB;
use Laravel\Socialite\Facades\Socialite;

class JobseekerController extends Controller
{
    public function showRegistrationForm()
    {
        return view('site.jobseeker.registration');
    }
    // public function postRegistration(Request $request)
    // {
    //     $validated = $request->validate([
       
    //         'email' => 'required|email|unique:jobseekers,email',
    //         // 'phone_number' => 'required|digits:10|unique:jobseekers,phone_number',
    //         'phone_number' => 'required|unique:jobseekers,phone_number',
    //         'password' => 'required|min:6|same:confirm_password',
    //         'confirm_password' => 'required|min:6',
    //     ]);
     

    //      $jobseekers = Jobseekers::create([
    //         'email' => $request->email,
    //         'phone_number' => $request->phone_number,
    //         'password' => Hash::make($request->password),
    //         'pass' => $request->password,
             
    //     ]);
    //     session([
    //         'jobseeker_id' => $jobseekers->id,
    //         'email' => $request->email,
    //         'phone_number' => $request->phone_number,
    //     ]);

    //     return redirect()->route('jobseeker.registration');
    // }

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
        // Mail::html('
        //     <!DOCTYPE html>
        //     <html lang="en">
        //     <head>
        //         <meta charset="UTF-8">
        //         <title>Welcome to Talentrek</title>
        //         <style>
        //             body {
        //                 background-color: #f4f6f9;
        //                 font-family: Arial, sans-serif;
        //                 padding: 20px;
        //                 margin: 0;
        //             }
        //             .email-container {
        //                 background: #ffffff;
        //                 max-width: 600px;
        //                 margin: auto;
        //                 padding: 30px;
        //                 border-radius: 8px;
        //                 box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        //             }
        //             h2 {
        //                 color: #007bff;
        //                 margin-bottom: 20px;
        //             }
        //             p {
        //                 line-height: 1.6;
        //                 color: #333333;
        //             }
        //             .footer {
        //                 margin-top: 30px;
        //                 font-size: 12px;
        //                 color: #888888;
        //                 text-align: center;
        //             }
        //         </style>
        //     </head>
        //     <body>
        //         <div class="email-container">
        //             <h2>Welcome to Talentrek!</h2>
        //             <p>Hello <strong>' . e($jobseeker->email) . '</strong>,</p>

        //             <p>You have successfully signed up on <strong>Talentrek</strong>. We\'re excited to have you with us!</p>

        //             <p>Start exploring career opportunities, connect with employers, and grow your professional journey.</p>

        //             <p>If you ever need help, feel free to contact our support team.</p>

        //             <p>Warm regards,<br><strong>The Talentrek Team</strong></p>
        //         </div>

        //         <div class="footer">
        //             © ' . date('Y') . ' Talentrek. All rights reserved.
        //         </div>
        //     </body>
        //     </html>
        // ', function ($message) use ($jobseeker) {
        //     $message->to($jobseeker->email)
        //             ->subject('Welcome to Talentrek – Signup Successful');
        // });

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
        $phone = session('phone_number');
        $jobseekerId = session('jobseeker_id');
        $jobseeker = Jobseekers::find($jobseekerId);

        return view('site.jobseeker.registration', compact('jobseeker','email','phone'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:jobseekers,email,' . $jobseeker->id,
            'phone_number' => 'required|unique:jobseekers,phone_number,' . $jobseeker->id,
            'dob' => 'required|date',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
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
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone_number.required' => 'Please enter your phone number.',
            'phone_number.unique' => 'This phone number is already registered.',
            'dob.required' => 'Please select your date of birth.',
            'dob.date' => 'Date of birth must be a valid date.',
            'city.required' => 'Please enter your city.',
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
            'date_of_birth' => $validated['dob'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'national_id' => $validated['national_id'],
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
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'job_role'      => $role,
                    'organization'  => $request->organization[$index] ?? null,
                    'starts_from'   => $startDate,
                    'end_to'        => $endDate,
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
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'resume',
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
                    'user_id'       => $jobseeker->id,
                    'user_type'     => 'jobseeker',
                    'doc_type'      => 'profile_picture',
                    'document_name' => $profileName,
                    'document_path' => asset('uploads/' . $fileNameToStoreProfile),
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


    public function loginJobseeker(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $jobseeker = Jobseekers::where('email', $request->email)->first();

        if (!$jobseeker) {
            // Email does not exist
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }

        if ($jobseeker->status !== 'active') {
            // Status is inactive or blocked
            session()->flash('error', 'Your account is inactive. Please contact admimnistrator.');
            return back()->withInput($request->only('email'));
        }

        // Now attempt login only if status is active
        if (Auth::guard('jobseeker')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('jobseeker.profile');
        } else {
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }
    }

    // public function loginJobseeker(Request $request)
    // {
    //     $this->validate($request, [
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $jobseeker = Jobseekers::where('email', $request->email)->first();

    //     if (!$jobseeker) {
    //         session()->flash('error', 'Invalid email or password.');
    //         return back()->withInput($request->only('email'));
    //     }

    //     if ($jobseeker->status !== 'active') {
    //         session()->flash('error', 'Your account is inactive. Please contact administrator.');
    //         return back()->withInput($request->only('email'));
    //     }

    //     if (Auth::guard('jobseeker')->attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $jobseeker = Auth::guard('jobseeker')->user();

    //         if ($jobseeker->isSubscriptionBuy === 'yes') { // or === 1 if boolean
    //             return redirect()->route('jobseeker.profile');
    //         } else {
    //             return redirect()->route('jobseeker.subscription.plan');
    //         }
    //     } else {
    //         session()->flash('error', 'Invalid email or password.');
    //         return back()->withInput($request->only('email'));
    //     }
    // }

    public function processSubscriptionPayment(Request $request)
    {
        $jobseeker = auth()->user(); 

        $jobseeker->isSubscribtionBuy = 'yes';
        $jobseeker->save();

        return redirect()->route('jobseeker.profile')->with('success', 'Subscription activated successfully.');
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $jobseeker = Jobseekers::where('email', $googleUser->getEmail())->first();

            if (!$jobseeker) {
                session()->flash('error', 'No account associated with this Google email.');
                return redirect()->route('jobseeker.sign-in');
            }

            if ($jobseeker->status !== 'active') {
                session()->flash('error', 'Your account is inactive. Please contact administrator.');
                return redirect()->route('jobseeker.sign-in');
            }

            Auth::guard('jobseeker')->login($jobseeker);
            return redirect()->route('jobseeker.registration');
        } catch (\Exception $e) {
            session()->flash('error', 'Google login failed.');
            return redirect()->route('jobseeker.sign-in');
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:jobseekers,email,' . $user->id,
            'gender' => 'required|string|in:Male,Female,Other',
            'phone_number' => 'required|digits:10',
            'dob' => 'required|date',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
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

                        if ($existsInRecruiters || $existsInTrainers || $existsInJobseekers) {
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
            'phone_number.digits' => 'The phone number must be exactly 10 digits.',

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
            'address' => $validated['address'],
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
            'high_education.*.string'   => 'The highest education must be a valid string.',
            'high_education.*.max'      => 'The highest education must not exceed 255 characters.',

            'field_of_study.*.required' => 'The field of study is required.',
            'field_of_study.*.string'   => 'The field of study must be a valid string.',
            'field_of_study.*.max'      => 'The field of study must not exceed 255 characters.',

            'institution.*.required'    => 'The institution name is required.',
            'institution.*.string'      => 'The institution must be a valid string.',
            'institution.*.max'         => 'The institution must not exceed 255 characters.',

            'graduate_year.*.required'  => 'The graduation year is required.',
            'graduate_year.*.string'    => 'The graduation year must be a valid string.',
            'graduate_year.*.max'       => 'The graduation year must not exceed 255 characters.',
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
            'end_to.*' => 'required|date',
            'currently_working' => 'array',
        ], [
            // Job Role
            'job_role.*.required' => 'Please enter your job role.',
            'job_role.*.string' => 'Job role should be a valid text.',
            'job_role.*.max' => 'Job role can’t be more than 255 characters.',

            // Organization
            'organization.*.required' => 'Please provide the organization name.',
            'organization.*.string' => 'Organization name must be a valid string.',
            'organization.*.max' => 'Organization name can’t exceed 255 characters.',

            // Start Date
            'starts_from.*.required' => 'Start date is required for each experience.',
            'starts_from.*.date' => 'Start date must be a valid date format.',

            // End Date
            'end_to.*.required' => 'Please provide the end date.',
            'end_to.*.date' => 'End date must be a valid date format.',

            // Currently Working
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


    public function updateAdditionalInfo(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            // Resume Messages
            'resume.required' => 'Please upload your resume.',
            'resume.file' => 'The resume must be a valid file.',
            'resume.mimes' => 'The resume must be a file of type: PDF, DOC, or DOCX.',
            'resume.max' => 'The resume must not be larger than 2MB.',

            // Profile Messages
            'profile.required' => 'Please upload your profile image or document.',
            'profile.file' => 'The profile must be a valid file.',
            'profile.mimes' => 'The profile must be a file of type: JPG, JPEG, PNG, or PDF.',
            'profile.max' => 'The profile file must not exceed 2MB.',
        ]);


        foreach (['resume', 'profile'] as $type) {
            if ($request->hasFile($type)) {
                $file = $request->file($type);
                $fileName = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $path = asset('uploads/' . $fileName);
                
                AdditionalInfo::updateOrCreate(
                    ['user_id' => $userId, 'doc_type' => $type],
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
        $file = AdditionalInfo::where('user_id', $userId)->where('doc_type', $type)->first();

        if ($file) {
            $filePath = public_path($file->document_path);
            if (file_exists($filePath)) unlink($filePath);
            $file->delete();

            return response()->json(['status' => 'success', 'message' => ucfirst($type) . ' deleted successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => ucfirst($type) . ' not found.'], 404);
    }


    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'contact' => ['required', function ($attribute, $value, $fail) {
                $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                $column = $isEmail ? 'email' : 'phone_number';

                $exists = DB::table('jobseekers')->where($column, $value)->exists();

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


     
    public function showOtpForm(){
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

    public function mentorshipDetails($id) {
       $mentorDetails = Mentors::select('mentors.*', 'booking_slots.*','booking_slots.id as booking_slot_id','mentors.id as mentor_id')
                            ->join('booking_slots', 'mentors.id', '=', 'booking_slots.user_id')
                            ->where('booking_slots.user_type', 'mentor')
                            ->where('mentors.id', $id)
                            ->with(['reviews', 'additionalInfo', 'profilePicture'])
                            ->first();


    //    echo "<pre>";
    //     print_r($mentorDetails); die;
        return view('site.mentorship-details', compact('mentorDetails'));
    }
    

    public function bookingSession($mentor_id, $slot_id) {
        $mentorDetails = Mentors::select('mentors.*','booking_slots.*','booking_slots.id as booking_slot_id','mentors.id as mentor_id')
                                ->where('mentors.id', $mentor_id)
                                ->join('booking_slots', 'mentors.id', '=', 'booking_slots.user_id')
                                ->where('booking_slots.id', $slot_id)
                                ->first();
        // echo "<pre>";
        // print_r($slot_id); die;
        return view('site.mentorship-book-session', compact('mentorDetails'));
    }


    public function submitMentorshipBooking(Request $request)
    {
        // Check if jobseeker is logged in
        if (!auth('jobseeker')->check()) {
            return redirect()->back()->with('error', 'Please log in to book a mentorship session.');
        }

        $request->validate([
            'mentor_id' => 'required|exists:mentors,id',
            'mode' => 'required|in:online,offline',
            'date' => 'required|date',
            'slot_id' => 'required|exists:booking_slots,id',
        ]);

        $jobseekerId = auth('jobseeker')->id();

        // Save the booking
        BookingSession::create([
            'jobseeker_id' => $jobseekerId,
            'user_type' => 'mentor',
            'user_id' => $request->mentor_id,
            'booking_slot_id' => $request->slot_id,
            'slot_date' => $request->date,
            'slot_mode' => $request->mode,
            'slot_time' => $request->slot_time,
            'status' => 'pending',
        ]);

        return redirect()->route('mentorship-booking-success')->with('success', 'Session booked successfully.');
    }







    public function getAvailableSlots(Request $request)
    {
        $mode = $request->query('mode');
        $date = $request->query('date'); // Expecting format: YYYY-MM-DD
        $mentor_id = $request->query('mentor_id');

        $formattedDate = date('Y-m-d', strtotime($date));

        // Fetch all slots for this mentor and mode
        $slots = BookingSlot::where('slot_mode', $mode)
                            ->where('user_type', 'mentor')
                            ->where('user_id', $mentor_id)
                            ->get();

        $slots->transform(function ($slot) use ($formattedDate) {
        $isUnavailable = false;
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

        $isUnavailable = in_array($formattedDate, $unavailableDates);

        $slot->is_unavailable = $isUnavailable;
        $slot->start_time = \Carbon\Carbon::parse($slot->start_time)->format('h:i A');
        $slot->end_time = \Carbon\Carbon::parse($slot->end_time)->format('h:i A');

        return $slot;
    });


        return response()->json($slots);
    }




    public function courseDetails($id)
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
        ->where('doc_type', 'profile') // ✅ important fix here
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

        return view('site.training-detail', compact(
            'material', 'user', 'userType', 'userId', 'average', 'ratingsPercent', 'reviews'
        ));
    }




    public function submitReview(Request $request)
    {
        $user = auth()->guard('jobseeker')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $allowedTypes = ['trainer', 'mentor', 'coach', 'assessor'];
        if (!in_array($request->user_type, $allowedTypes)) {
            return response()->json(['success' => false, 'message' => 'Invalid user type'], 400);
        }

        $data = [
            'jobseeker_id'     => $user->id,
            'user_type'        => $request->user_type,
            'user_id'          => $request->user_id,
            'reviews'          => $request->reviews,
            'ratings'          => $request->ratings,
            'created_at'       => now(),
            'updated_at'       => now(),
        ];

        if ($request->user_type === 'trainer' && $request->filled('material_id')) {
            $data['trainer_material'] = $request->material_id;
        }

        DB::table('reviews')->insert($data);

        return response()->json([
            'success' => true,
            'review' => [
                'jobseeker_name' => $user->name,
                'ratings'        => $request->ratings,
                'reviews'        => $request->reviews
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
        ->where('doc_type', 'profile') // ✅ important fix here
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
            'material', 'user', 'userType', 'userId', 'average', 'ratingsPercent', 'reviews'
        ));
    }
}