<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Jobseekers;

use App\Models\Recruiters;

use App\Models\Trainers;

use App\Models\TrainingExperience;

use App\Models\EducationDetails;

use App\Models\WorkExperience;

use App\Models\Additionalinfo;

use App\Models\TrainerAssessment;

use App\Models\AssessmentQuestion;

use App\Models\AssessmentOption;

use App\Models\TrainingMaterial;

use App\Models\TrainingBatch;

use App\Models\TrainingMaterialsDocument;

use App\Models\JobseekerTrainingMaterialPurchase;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Models\Review;

use App\Models\Mentors;

use App\Models\Assessors;

use App\Models\Notification;

use Carbon\Carbon;

use App\Services\ZoomService;

use App\Models\SubscriptionPlan;

use App\Models\PurchasedSubscription;

use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Str;





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

                        <p>Hi <strong>' . e($trainers->name ?? $trainers->email) . '</strong>,</p>



                        <p>Thank you for completing your registration on <strong>Talentrek</strong>. We\'re thrilled to have you with us!</p>



                        <p>You can now start exploring job opportunities, connect with trainers, and grow your career.</p>



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

        ', function ($message) use ($trainers) {

            $message->to($trainers->email)

                ->subject('Welcome to Talentrek – Registration Successful');

        });

      

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

            'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',

            'email' => 'required|email|unique:trainers,email,' . $trainer->id,

            'phone_number' => 'required|unique:trainers,phone_number,' . $trainer->id,

            'phone_code' => 'required',

            'dob' => 'required|date',

            'address' => 'required|string|max:255',

            'city' => 'required|string|max:255',

            'state' => 'required|string|max:255',

            'country' => 'required|string|max:255',

            'pin_code' => 'required|digits:5',

            'national_id' => [

                'required',

                'min:10',

                function ($attribute, $value, $fail) use ($trainer) {

                    $existsInTrainers = Trainers::where('national_id', $value)->where('id', '!=', $trainer->id)->exists();

                    if ($existsInTrainers) {

                        $fail('The national ID has already been taken.');

                    }

                },

            ],

            'high_education.*' => 'required|string',

            'field_of_study.*' => 'required|string',

            'institution.*' => 'required|string',

            'graduate_year.*' => 'required|string',

            'job_role.*' => 'required|string',

            'organization.*' => 'required|string',

            'starts_from.*' => 'required|date',

            'end_to.*' => 'required|date',

            'training_experience' => 'required|string',

            'training_skills' => 'required|string',

            'website_link' => 'nullable|url',

            'portfolio_link' => 'nullable|url',

            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',

            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            'training_certificate' => 'required|file|mimes:pdf,doc,docx|max:2048',

        ],

            [

                // Custom error messages

                'name.required' => 'Please enter your full name.',

                'name.regex' => 'The name should contain only letters and single spaces.',

                'email.required' => 'Email is required.',

                'email.email' => 'Please provide a valid email address.',

                'email.unique' => 'This email is already registered.',

                'phone_number.required' => 'Phone number is required.',

                'phone_number.unique' => 'This phone number is already in use.',

                'dob.required' => 'Please enter your date of birth.',

                'dob.date' => 'Invalid date format for date of birth.',

                'address.required' => 'Please enter your address.',

                'city.required' => 'Please enter your city.',

                'state.required' => 'Please enter your state.',

                'country.required' => 'Please enter your country.',

                'pin_code.required' => 'Please enter your pin code.',

                'national_id.required' => 'Please enter your national ID.',

                'national_id.min' => 'National ID must be at least 10 digits.',



                'high_education.*.required' => 'Please select your highest education.',

                'field_of_study.*.required' => 'Please select your field of study.',

                'institution.*.required' => 'Please enter your institution name.',

                'graduate_year.*.required' => 'Please select your graduation year.',



                'job_role.*.required' => 'Please enter your Job role.',

                'organization.*.required' => 'Please enter your organization name.',

                'starts_from.*.required' => 'Please select start date.',

                'starts_from.*.date' => 'Invalid start date format.',

                'end_to.*.required' => 'Please select end date.',

                'end_to.*.date' => 'Invalid end date format.',



                'training_experience.required' => 'Please enter your training experience.',

                'training_skills.required' => 'Please specify your training skills.',

                'website_link.required' => 'Website link is required.',

                'website_link.url' => 'Please provide a valid website URL.',

                'portfolio_link.required' => 'Portfolio link is required.',

                'portfolio_link.url' => 'Please provide a valid portfolio URL.',



                'resume.required' => 'Please upload your resume.',

                'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',

                'resume.max' => 'Resume file must not exceed 2MB.',

                'profile_picture.required' => 'Please upload your profile picture.',

                'profile_picture.image' => 'Profile picture must be an image.',

                'profile_picture.mimes' => 'Allowed image types are JPG, JPEG, and PNG.',

                'profile_picture.max' => 'Profile picture must not exceed 2MB.',

                'training_certificate.required' => 'Please upload your training certificate.',

                'training_certificate.mimes' => 'Certificate must be a PDF, DOC, or DOCX file.',

                'training_certificate.max' => 'Certificate file must not exceed 2MB.',

            ]);



        DB::transaction(function () use ($request, $trainer, $validated) {

            // Update trainer

            $trainer->update([

                'name' => $validated['name'],

                'email' => $validated['email'],

                'phone_code' => $validated['phone_code'],

                'phone_number' => $validated['phone_number'],

                'date_of_birth' => $validated['dob'],

                'address' => $validated['address'],

                'city' => $validated['city'],

                'state' => $validated['state'],

                'country' => $validated['country'],

                'pin_code' => $validated['pin_code'],

                'national_id' => $validated['national_id'],

                'is_registered' => 1

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

            // foreach ($request->job_role as $index => $role) {

            //     WorkExperience::create([

            //         'user_id' => $trainer->id,

            //         'user_type' => 'trainer',

            //         'job_role' => $role,

            //         'organization' => $request->organization[$index],

            //         'starts_from' => $request->starts_from[$index],

            //         'end_to' => $request->end_to[$index],

            //     ]);

            // }

            foreach ($request->job_role as $index => $role) {

                $isCurrentlyWorking = isset($request->currently_working[$index]) && $request->currently_working[$index] === 'on';



                WorkExperience::create([

                    'user_id' => $trainer->id,

                    'user_type' => 'trainer',

                    'job_role' => $role,

                    'organization' => $request->organization[$index],

                    'starts_from' => $request->starts_from[$index],

                    'end_to' => $isCurrentlyWorking ? 'work here' : $request->end_to[$index], // ✅ Save "Work Here"

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

                'profile_picture' => 'trainer_profile_picture',

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

                        <p>Hi <strong>' . e($trainer->name ?? $trainer->email) . '</strong>,</p>



                        <p>Thank you for completing your registration on <strong>Talentrek</strong>. We\'re thrilled to have you with us!</p>



                        <p>You can now start exploring job opportunities, connect with trainers, and grow your career.</p>



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

            ', function ($message) use ($trainer) {

                    $message->to($trainer->email)

                        ->subject('Welcome to Talentrek – Registration Successful');

        });



        $data = [

            'sender_id' => $trainer->id,

            'sender_type' => 'Registration by Trainer.',

            'receiver_id' => '1',

            'message' => 'Welcome to Talentrek – Registration Successful by '.$trainer->name,

            'is_read' => 0,

            'is_read_admin' => 0,

            'user_type' => 'trainer'

        ];



        Notification::insert($data);

        session()->forget('trainer_id');

        return redirect()->route('trainer.login')->with('success_popup', true);





    }





    public function loginTrainer(Request $request)

    {

        $this->validate($request, [

            'email'    => 'required|email',

            'password' => 'required'

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



        // ✅ Check admin_status

        if ($trainer->admin_status === 'superadmin_reject' || $trainer->admin_status === 'rejected') {

            session()->flash('error', 'Your account has been rejected by administrator.');

            return back()->withInput($request->only('email'));

        }



        if ($trainer->admin_status !== 'superadmin_approved') {

            session()->flash('error', 'Your account is not yet approved by administrator.');

            return back()->withInput($request->only('email'));

        }



        // ✅ Attempt login only if status = active and admin_status = approved

        if (Auth::guard('trainer')->attempt(['email' => $request->email, 'password' => $request->password])) {

            return redirect()->route('trainer.dashboard');

        } else {

            session()->flash('error', 'Invalid email or password.');

            return back()->withInput($request->only('email'));

        }

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

            $data = [

                'sender_id' => auth()->id(),

                'sender_type' => 'Trainer add assessment for material/course',

                'receiver_id' => '1',

                'message' => $questionData['text'].' assessment add successfully for material/course.',

                'is_read' => 0,

                'is_read_admin' => 0,

                'user_type' => 'coach'

            ];



            Notification::insert($data);

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



            // 'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',







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



            $file->move('uploads', $thumbnailFileName);



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



            'admin_status'           => null,



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



                    $uploadedFile->move('uploads', $fileName);



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







        $data = [



            'sender_id' => $trainer->id,



            'sender_type' => 'Recorded Traning Material Added',



            'receiver_id' => '1',



            'message' => $request->training_title.' Recorded Training course saved successfully.',



            'is_read' => 0,



            'is_read_admin' => 0,



            'user_type' => 'trainer'



        ];







        Notification::insert($data);



       return redirect()->route('training.list')->with('success', 'Recorded Training course saved successfully.');



    }











    public function saveTrainingOnlineData(Request $request)



    {



        // print_r($_POST);exit;



        $trainer = auth()->user();







        // Validate input



        $request->validate([



            'training_title'         => 'required|string|max:255',



            'training_sub_title'     => 'required|string|max:255',



            'training_objective'     => 'required|string',



            'training_descriptions'  => 'nullable|string',



            'training_category'      => 'required|string|in:online,classroom',



            'training_level'         => 'required|string|in:Beginner,Intermediate,Advanced',



            'training_price'         => 'required|numeric|min:0',



            'training_offer_price'   => 'required|numeric|min:0',



           







            'content_sections.*.batch_no'   => 'required|string|max:255',



            'content_sections.*.batch_date' => 'required|date',



            'content_sections.*.start_time' => 'required|string',



            'content_sections.*.end_time'   => 'required|string',



            'content_sections.*.duration'   => 'required|string',



            'content_sections.*.strength'   => 'required|integer|min:1',



            'content_sections.*.days'       => 'required',



        ]);



        



        



        DB::beginTransaction();







        try {



            $thumbnailFileName = null;



            $thumbnailFilePath = null;







            if ($request->hasFile('thumbnail')) {



                $file = $request->file('thumbnail');



                $thumbnailFileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();



                $file->move('uploads', $thumbnailFileName);



                $thumbnailFilePath = asset('uploads/' . $thumbnailFileName);



            }



 



            // Insert course



            $trainingId = DB::table('training_materials')->insertGetId([



                'trainer_id'             => $trainer->id,



                'training_title'         => trim($request->training_title),



                'training_sub_title'     => trim($request->training_sub_title),



                'training_objective'     => $request->training_objective,



                'training_descriptions'  => $request->training_descriptions,



                'training_level'         => $request->training_level,



                'training_price'         => $request->training_price,



                'training_offer_price'   => $request->training_offer_price,



                'training_type'          => 'online',



                'session_type'           => $request->training_category,



                'thumbnail_file_name'    => $thumbnailFileName,



                'thumbnail_file_path'    => $thumbnailFilePath,



                'admin_status'           => null,



                'rejection_reason'       => null,

                

                'created_at'             => now(),



                'updated_at'             => now(),



            ]);



            



            // Insert batches



            foreach ($request->input('content_sections', []) as $section) {



                // $zoom = new ZoomService();



                



                // $startTime = $section['batch_date'] . ' ' . $section['start_time'];



               



                // $zoomMeeting = $zoom->createMeeting("Batch #{$section['batch_no']}", $startTime);



             



                // if (!$zoomMeeting || !isset($zoomMeeting['start_url'])) {



                //     throw new \Exception("Zoom creation failed for batch {$section['batch_no']}");



                // }



   



                DB::table('training_batches')->insert([



                    'trainer_id'           => $trainer->id,



                    'training_material_id' => $trainingId,



                    'batch_no'             => $section['batch_no'],



                    'start_date'           => $section['batch_date'],



                    'end_date'             => $section['end_date'],



                    'start_timing'         => $section['start_time'],



                    'end_timing'           => $section['end_time'],



                    'duration'             => $section['duration'],



                    'strength'             => $section['strength'],



                    'days'                 => json_encode(json_decode($section['days'], true)), // convert from stringified JSON



                    // 'zoom_start_url'       => $zoomMeeting['start_url'],



                    // 'zoom_join_url'        => $zoomMeeting['join_url'],



                    'created_at'           => now(),



                    'updated_at'           => now(),



                ]);



            }







            DB::commit(); 



            $data = [



                'sender_id' => $trainer->id,



                'sender_type' => 'Online Training Material Added',



                'receiver_id' => '1',



                'message' => $request->training_title.' Online Training course saved successfully.',



                'is_read' => 0,



                'is_read_admin' => 0,



                'user_type' => 'trainer'



            ];







            Notification::insert($data);



            return redirect()->route('training.list')->with('success', 'Training and batches saved successfully.');



        } catch (\Exception $e) {



            DB::rollBack();



            Log::error('Training Save Error: ' . $e->getMessage());



            return redirect()->back()->withInput()->with('error', 'An error occurred while saving the training.');



        }



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











    // public function updateRecordedTraining(Request $request, $id)



    // {



    //     $data = $request->validate([



    //         'training_title' => 'required|string|max:255',



    //         'training_sub_title' => 'required|string|max:255',



    //         'training_descriptions' => 'nullable|string',



    //         'training_category' => 'required|string',



    //         'training_level' => 'required|string',



    //         'training_price' => 'required|numeric',



    //         'training_offer_price' => 'required|numeric',



    //         // 'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',







    //         'content_sections' => 'array',



    //         'content_sections.*.title' => 'required|string|max:255',



    //         'content_sections.*.description' => 'required|string',



    //         'content_sections.*.file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mov,avi,mkv|max:51200',



    //         'content_sections.*.file_duration' => 'required|string|max:255',







    //     ], [



    //         // Optional custom messages (if needed)



    //         'training_title.required' => 'Please enter the training title.',



    //         'training_sub_title.required' => 'Please enter the training subtitle.',



    //         'training_category.required' => 'Please select a training category.',



    //         'training_level.required' => 'Please select the training level.',



    //         'training_price.required' => 'Please enter the training price.',



    //         'training_offer_price.required' => 'Please enter the offer price.',



    //         'content_sections.*.title.required' => 'Please enter the section title.',



    //         'content_sections.*.description.required' => 'Please enter the section description.',



    //         'content_sections.*.file_duration.required' => 'Please enter the section file_duration.',



    //     ], [



    //         //    Custom attribute names



    //         'training_title' => 'Training Title',



    //         'training_sub_title' => 'Training Subtitle',



    //         'training_descriptions' => 'Training Description',



    //         'training_category' => 'Training Category',



    //         'training_level' => 'Training Level',



    //         'training_price' => 'Training Price',



    //         'training_offer_price' => 'Training Offer Price',



    //         'thumbnail' => 'Thumbnail Image',







    //         'content_sections' => 'Content Sections',



    //         'content_sections.*.title' => 'Section Title',



    //         'content_sections.*.description' => 'Section Description',



    //         'content_sections.*.file' => 'Section File',



    //         'content_sections.*.file_duration' => 'Section file_duration',



    //     ]);







    //     $training = TrainingMaterial::findOrFail($id);



    //     $training->fill($data);







    //     if ($request->hasFile('thumbnail')) {



    //         $file = $request->file('thumbnail');



    //         $name = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();



    //         $file->move(public_path('uploads'), $name);



    //         $training->thumbnail_file_name = $name;



    //         $training->thumbnail_file_path = asset('uploads/' . $name);



    //     }







    //     $training->save();







    //     if (!empty($data['content_sections']) && is_array($data['content_sections'])) {



    //         $existingIds = TrainingMaterialsDocument::where('training_material_id', $id)->pluck('id')->toArray();



    //         $requestIds = [];







    //         foreach ($data['content_sections'] as $section) {



    //             if (!empty($section['document_id'])) {



    //                 $doc = TrainingMaterialsDocument::where('id', $section['document_id'])



    //                     ->where('training_material_id', $training->id)



    //                     ->first();







    //                 if ($doc) {



    //                     $doc->training_title = $section['title'];



    //                     $doc->description = $section['description'];







    //                     if (!empty($section['file']) && $section['file'] instanceof \Illuminate\Http\UploadedFile) {



    //                         $file = $section['file'];



    //                         $name = 'section_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();



    //                         $path = $file->storeAs('uploads', $name, 'public');



    //                         $doc->file_name = $name;



    //                         $doc->file_path = asset('storage/' . $path);



    //                     } else {



    //                         //  Preserve existing file if no new upload



    //                         $doc->file_name = $section['existing_file_name'] ?? $doc->file_name;



    //                         $doc->file_path = $section['existing_file_path'] ?? $doc->file_path;



    //                     }







    //                     $doc->save();



    //                     $requestIds[] = $doc->id;



    //                 }



    //             }



    //             else {



    //                 $doc = new TrainingMaterialsDocument();



    //                 $doc->training_material_id = $training->id;



    //                 $doc->trainer_id = auth()->id();



    //                 $doc->training_title = $section['title'];



    //                 $doc->description = $section['description'];







    //                 if (!empty($section['file']) && $section['file'] instanceof \Illuminate\Http\UploadedFile) {



    //                     $file = $section['file'];



    //                     $name = 'section_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();



    //                     $path = $file->storeAs('uploads', $name, 'public');



    //                     $doc->file_name = $name;



    //                     $doc->file_path = asset('storage/' . $path);



    //                 } else {



    //                     //  Preserve old file (if passed by hidden input — useful when editing a just-added section)



    //                     $doc->file_name = $section['existing_file_name'] ?? null;



    //                     $doc->file_path = $section['existing_file_path'] ?? null;



    //                 }







    //                 $doc->save();



    //                 $requestIds[] = $doc->id;



    //             }







    //         }







    //         $toDelete = array_diff($existingIds, $requestIds);



    //         if (!empty($toDelete)) {



    //             TrainingMaterialsDocument::whereIn('id', $toDelete)->delete();



    //         }



    //     }







    //     return redirect()->route('training.list')->with('success', 'Recorded training course updated successfully!');



    // }







    public function updateRecordedTraining(Request $request, $id)



    {



        $trainer = auth()->user();







        // ✅ Main training details ka validation



        $request->validate([



            'training_title'        => 'required|string|max:255',



            'training_sub_title'    => 'required|string|max:255',



            'training_descriptions' => 'nullable|string',



            'training_category'     => 'required|string',



            'training_level'        => 'required|string',



            'training_price'        => 'required|numeric',



            'training_offer_price'  => 'required|numeric',



        ], [



            'training_title.required'        => 'Please enter the training title.',



            'training_sub_title.required'    => 'Please enter the training subtitle.',



            'training_category.required'     => 'Please select a training category.',



            'training_level.required'        => 'Please select the training level.',



            'training_price.required'        => 'Please enter the training price.',



            'training_offer_price.required'  => 'Please enter the offer price.',



        ]);







        $training = TrainingMaterial::findOrFail($id);







        // ✅ Thumbnail update



        if ($request->hasFile('thumbnail')) {



            $file = $request->file('thumbnail');



            $thumbnailFileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();



            $file->move('uploads', $thumbnailFileName);



            $training->thumbnail_file_name = $thumbnailFileName;



            $training->thumbnail_file_path = asset('uploads/' . $thumbnailFileName);



        }







        // ✅ Update main details



        $training->update([



            'training_title'        => $request->training_title,



            'training_sub_title'    => $request->training_sub_title,



            'training_descriptions' => $request->training_descriptions,



            'training_category'     => $request->training_category,



            'training_level'        => $request->training_level,



            'training_price'        => $request->training_price,



            'training_offer_price'  => $request->training_offer_price,



        ]);







        // ✅ Content sections update/create



        if ($request->has('content_sections')) {



            $existingIds = TrainingMaterialsDocument::where('training_material_id', $id)->pluck('id')->toArray();



            $requestIds = [];







            foreach ($request->content_sections as $index => $section) {



                // 🔹 File fetch from Laravel file bag



                $uploadedFile = $request->file("content_sections.$index.file");







                if (!empty($section['document_id'])) {



                    // 🔹 Update existing record



                    $doc = TrainingMaterialsDocument::find($section['document_id']);



                    if ($doc) {



                        $doc->training_title = $section['title'] ?? $doc->training_title;



                        $doc->description    = $section['description'] ?? $doc->description;



                        $doc->file_duration  = $section['file_duration'] ?? $doc->file_duration;







                        if ($uploadedFile) {



                            $fileName = 'section_' . time() . '_' . $index . '.' . $uploadedFile->getClientOriginalExtension();



                            $uploadedFile->move('uploads', $fileName);



                            $doc->file_name = $fileName;



                            $doc->file_path = asset('uploads/' . $fileName);



                        }







                        $doc->save();



                        $requestIds[] = $doc->id;



                    }



                } else {



                    // 🔹 Create new record



                    $filePath = null;



                    $fileName = null;



                    if ($uploadedFile) {



                        $fileName = 'section_' . time() . '_' . $index . '.' . $uploadedFile->getClientOriginalExtension();



                        $uploadedFile->move('uploads', $fileName);



                        $filePath = asset('uploads/' . $fileName);



                    }







                    $docId = DB::table('training_materials_documents')->insertGetId([



                        'trainer_id'           => $trainer->id,



                        'training_material_id' => $training->id,



                        'training_title'       => $section['title'] ?? null,



                        'description'          => $section['description'] ?? null,



                        'file_path'            => $filePath,



                        'file_name'            => $fileName,



                        'file_duration'        => $section['file_duration'] ?? null,



                        'created_at'           => now(),



                        'updated_at'           => now(),



                    ]);



                    $requestIds[] = $docId;



                }



            }







            // ✅ Delete removed sections



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



                'end_date',



                'start_timing',



                'end_timing',



                'duration',



                'strength',



                'days' // assuming stored as JSON or comma-separated string



            ])



            ->get()



            ->map(function ($batch) {



                return [



                    'id' => $batch->id,



                    'batch_no' => $batch->batch_no,



                    'start_date' => $batch->start_date,



                    'end_date' => $batch->end_date,



                    'start_timing' => $batch->start_timing,



                    'end_timing' => $batch->end_timing,



                    'duration' => $batch->duration,



                    'strength' => $batch->strength,



                    'days'         => json_decode($batch->days, true) ?? [],



                ];



            });







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



        // 'thumbnail'              => 'nullable|image|max:2048',







        // 'content_sections.*.batch_no'   => 'required|string|max:255',



        // 'content_sections.*.batch_date' => 'required|date',



        // 'content_sections.*.end_date'   => 'required|date', // ✅ Required now



        // 'content_sections.*.start_time' => 'required|string',



        // 'content_sections.*.end_time'   => 'required|string',



        // 'content_sections.*.duration'   => 'required|string',



        // 'content_sections.*.strength'   => 'required|integer|min:1',



        // 'content_sections.*.days'       => 'required',



    ], [



        'content_sections.*.strength.required' => 'Please enter batch strength.',



        'content_sections.*.strength.integer'  => 'Batch strength must be a number.',



        'content_sections.*.days.required'     => 'Please select at least one day.',



        'content_sections.*.end_date.required' => 'End date is missing for one or more batches.',



    ]);







    $training = TrainingMaterial::findOrFail($id);







    // Handle thumbnail if uploaded



    if ($request->hasFile('thumbnail')) {



        $file = $request->file('thumbnail');



        $fileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();



        $file->move('uploads', $fileName);



        $training->thumbnail_file_path = url('uploads/' . $fileName);



        $training->thumbnail_file_name = $fileName;



    }







    // Update training fields



    $training->training_title         = $request->training_title;



    $training->training_sub_title     = $request->training_sub_title;



    $training->training_objective     = $request->training_objective;



    $training->training_descriptions  = $request->training_descriptions;



    $training->training_level         = $request->training_level;



    $training->session_type           = $request->training_category;



    $training->training_price         = $request->training_price;



    $training->training_offer_price   = $request->training_offer_price;



    $training->save();







    // Delete existing batches



    TrainingBatch::where('training_material_id', $training->id)->delete();







    // Insert updated batches with Zoom meeting info and end_date



    if ($request->has('content_sections')) {



        foreach ($request->content_sections as $batch) {



            $zoom = new ZoomService();



            $startTime = $batch['batch_date'] . ' ' . $batch['start_time'];







            // $zoomMeeting = $zoom->createMeeting("Batch #{$batch['batch_no']}", $startTime);







            // if (!$zoomMeeting || !isset($zoomMeeting['start_url'])) {



            //     throw new \Exception("Zoom creation failed for batch {$batch['batch_no']}");



            // }







            TrainingBatch::create([



                'trainer_id'           => auth()->id(),



                'training_material_id' => $training->id,



                'batch_no'             => $batch['batch_no'],



                'start_date'           => $batch['batch_date'],



                'end_date'             => $batch['end_date'], // ✅ Added



                'start_timing'         => $batch['start_time'],



                'end_timing'           => $batch['end_time'],



                'duration'             => $batch['duration'],



                'strength'             => $batch['strength'],



                'days'                 => json_encode(json_decode($batch['days'], true)),



                //  om_join_url'        => $zoomMeeting['join_url'],



                'created_at'           => now(),



                'updated_at'           => now(),



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

            'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',

            'email' => 'required|email|unique:jobseekers,email,' . $user->id,

            'phone' => 'required|digits:9',

            'dob' => 'required|date',

            'address' => 'required|string|max:255',

            'city' => 'required|string|max:255',

            'state' => 'required|string|max:255',

            'country' => 'required|string|max:255',

            'pin_code' => 'required|digits:5',

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

        ], [

            'name.required' => 'Please enter your name.',

            'name.regex' => 'The name should contain only letters and single spaces.',

            'email.required' => 'Please enter your email.',

            'email.email' => 'Please enter a valid email address.',

            'email.unique' => 'This email is already taken.',

            'phone.required' => 'Please enter your phone number.',

            'phone.digits' => 'Phone number must be 9 digits.',

            'dob.required' => 'Please enter your date of birth.',

            'dob.date' => 'Please enter a valid date of birth.',

            'address.required' => 'Please enter your address.',

            'address.string' => 'Location must be a valid string.',

            'national_id.required' => 'Please enter your national ID.',

            'national_id.min' => 'National ID must be at least 10 characters.',

        ]);





        $user->update([

            'name' => $validated['name'],

            'email' => $validated['email'],

            'phone_number' => $validated['phone'],

            'date_of_birth' => $validated['dob'],

            'address' => $validated['address'],

            'city' => $validated['city'],

            'state' => $validated['state'],

            'country' => $validated['country'],

            'pin_code' => $validated['pin_code'],

            'national_id' => $validated['national_id'],

        ]);



        $data = [

            'sender_id' => $user->id,

            'sender_type' => 'Trainer updated his profile',

            'receiver_id' => '1',

            'message' => $validated['name'].' updated his profile successfully.',

            'is_read' => 0,

            'is_read_admin' => 0,

            'user_type' => 'trainer'

        ];



        Notification::insert($data);

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

        ], [

            'high_education.*.required' => 'Please enter your highest education.',

            'high_education.*.string' => 'Education must be a valid string.',

            

            'field_of_study.*.required' => 'Please enter your field of study.',

            'field_of_study.*.string' => 'Field of study must be a valid string.',

            

            'institution.*.required' => 'Please enter the name of the institution.',

            'institution.*.string' => 'Institution name must be a valid string.',

            

            'graduate_year.*.required' => 'Please enter your graduation year.',

            'graduate_year.*.string' => 'Graduation year must be a valid string.',

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

        ], [

            'job_role.*.required' => 'Please enter your job role.',

            'job_role.*.string' => 'Job role must be a valid string.',

            

            'organization.*.required' => 'Please enter your organization name.',

            'organization.*.string' => 'Organization name must be a valid string.',

            

            'starts_from.*.required' => 'Please enter your start date.',

            'starts_from.*.date' => 'Start date must be a valid date.',

            

            'end_to.*.date' => 'End date must be a valid date.',

            

            'currently_working.array' => 'Currently working must be an array of values.',

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

            $isCurrentlyWorking = isset($request->currently_working[$i]) && $request->currently_working[$i] == 1;



            $startDate = $request->starts_from[$i] ?? null;

            $endDate = $isCurrentlyWorking ? 'Work here' : ($request->end_to[$i] ?? null);



            // Validation

            if (!$isCurrentlyWorking && $startDate && $endDate && $endDate < $startDate) {

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



        // Map your input keys to doc_type values

        $uploadTypes = [

            'resume' => 'resume',

            'profile_picture' => 'trainer_profile_picture',

            'training_certificate' => 'training_certificate',

        ];



        // Validation rules for each input field

        $validated = $request->validate([

            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',

            'profile_picture' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'training_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

        ]);



        // Loop through each input and save the uploaded file

        foreach ($uploadTypes as $inputName => $docType) {

            if ($request->hasFile($inputName)) {

                $file = $request->file($inputName);

                $fileName = $docType . '_' . time() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('uploads'), $fileName);

                $path = asset('uploads/' . $fileName);



                AdditionalInfo::updateOrCreate(

                    ['user_id' => $userId, 'user_type' => 'trainer', 'doc_type' => $docType],

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

   





    // public function traineesJobseekers() {

        

    //     $trainerId = Auth::guard('trainer')->id();



    //     $coursePurchasesJobseekers = JobseekerTrainingMaterialPurchase::with(['jobseeker','profilePicture', 'material', 'batch'])

    //     ->where('trainer_id', $trainerId)

    //     ->get();



    //     return view('site.trainer.trainees-jobseekers', compact(

    //         'coursePurchasesJobseekers',

    //     ));

    // }



    public function traineesJobseekers()

    {

        $trainerId = Auth::guard('trainer')->id();



        $coursePurchasesJobseekers = JobseekerTrainingMaterialPurchase::with([

            'jobseeker:id,name',

            'jobseeker.profilePicture:id,user_id,document_path',

            'jobseeker.experiences:id,user_id,job_role,end_to',

            'material:id,training_title,session_type',

            'material.lessons:id,training_material_id',

            'batch:id,batch_no',

        ])

        ->where('trainer_id', $trainerId)

        ->get()

        ->map(function ($item) {

            $sessionType = strtolower($item->material->session_type ?? $item->material->training_type ?? 'recorded');



            $mode = $sessionType === 'classroom' ? 'Offline' :

                    ($sessionType === 'online' ? 'Online' : 'Recorded');



            $designation = optional($item->jobseeker->experiences->sortByDesc('end_to')->first())->job_role ?? '—';



            $data = [

                'id' => $item->id,

                'name' => $item->jobseeker->name,

                'designation' => $designation,

                'avatar' => $item->jobseeker->profilePicture?->document_path 

                            ? asset($item->jobseeker->profilePicture->document_path)

                            : asset('default-avatar.png'),

                'courseName' => $item->material->training_title ?? '—',

                'mode' => $mode,

                'enrollmentNo' => $item->enrollment_no ?? '—',

            ];



            if ($mode === 'Recorded') {

                $data['totalLessons'] = $item->material->lessons->count();

            } else {

                $data['batchName'] = $item->batch->batch_no ?? '—';

            }



            return $data;

        });





        // echo "<pre>";

        // print_r($coursePurchasesJobseekers);exit;



        return view('site.trainer.trainees-jobseekers', [

            'jobseekersData' => $coursePurchasesJobseekers

        ]);

    }







    public function showTrainerDashboard()

    {

        $trainerId = Auth::guard('trainer')->id();



        $coursePurchasesJobseekers = JobseekerTrainingMaterialPurchase::with([

            'jobseeker:id,name',

            'jobseeker.profilePicture:id,user_id,document_path',

            'jobseeker.experiences:id,user_id,job_role,end_to',

            'material:id,training_title,session_type',

            'material.lessons:id,training_material_id',

            'batch:id,batch_no',

        ])

        ->where('trainer_id', $trainerId)

        ->get()

        ->map(function ($item) {

            $sessionType = strtolower($item->material->session_type ?? $item->material->training_type ?? 'recorded');



            $mode = $sessionType === 'classroom' ? 'Offline' :

                    ($sessionType === 'online' ? 'Online' : 'Recorded');



            $designation = optional($item->jobseeker->experiences->sortByDesc('end_to')->first())->job_role ?? '—';



            $data = [

                'id' => $item->id,

                'name' => $item->jobseeker->name,

                'designation' => $designation,

                'avatar' => $item->jobseeker->profilePicture?->document_path 

                            ? asset($item->jobseeker->profilePicture->document_path)

                            : asset('default-avatar.png'),

                'courseName' => $item->material->training_title ?? '—',

                'mode' => $mode,

                'enrollmentNo' => $item->enrollment_no ?? '—',

            ];



            if ($mode === 'Recorded') {

                $data['totalLessons'] = $item->material->lessons->count();

            } else {

                $data['batchName'] = $item->batch->batch_no ?? '—';

            }



            return $data;

        });



        $today = Carbon::today()->toDateString();       // Current date (YYYY-MM-DD)

        $nowTime = Carbon::now()->format('H:i:s');      // Current time (HH:MM:SS)



        $batches = DB::table('training_batches as b')

            ->join('training_materials as m', 'b.training_material_id', '=', 'm.id')

            ->select(

                'b.*',

                'm.training_title as training_name',     

                'm.training_type',

                'm.training_level'

            )

            ->where('m.trainer_id', $trainerId)

            ->whereDate('b.start_date', $today)

            ->orderBy('b.start_timing', 'asc')

            ->get();





        // echo "<pre>";

        // print_r($batches);

        // exit;









        return view('site.trainer.trainer-dashboard', [

            'jobseekersData' => $coursePurchasesJobseekers,

            'batches' => $batches

        ]);

     

    }





    // public function showSubscriptionPlans()

    // {

    //     $user = Auth::guard('trainer')->user();



    //     // If trainer has already purchased, redirect to dashboard

    //     if ($user->isSubscribtionBuy === 'yes') {

    //         return redirect()->route('trainer.dashboard');

    //     }



    //     // Fetch available subscription plans

    //     $subscriptions = SubscriptionPlan::where('user_type', 'trainer')->get();



    //     return view('trainer.subscription', compact('subscriptions'));

    // }





    public function processSubscriptionPayment(Request $request)

    {

        $request->validate([

            'plan_id' => 'required|exists:subscription_plans,id',

            'card_number' => 'required|string|min:12|max:19',

            'expiry' => 'required|string',

            'cvv' => 'required|string|min:3|max:4',

        ]);



        $plan = SubscriptionPlan::findOrFail($request->plan_id);



        DB::beginTransaction();

        try {

            $trainer = auth('trainer')->user();



            // Create the new subscription

            $newSubscription = PurchasedSubscription::create([

                'user_id' => $trainer->id,

                'user_type' => 'trainer',

                'subscription_plan_id' => $plan->id,

                'start_date' => now(),

                'end_date' => now()->addDays($plan->duration_days),

                'amount_paid' => $plan->price,

                'payment_status' => 'paid',

            ]);



            // Update trainer only if:

            // - They have no active subscription, OR

            // - The new subscription ends later than the current one

            $shouldUpdate = false;



            if (!$trainer->active_subscription_plan_id) {

                $shouldUpdate = true;

            } else {

                $currentActive = PurchasedSubscription::find($trainer->active_subscription_plan_id);

                if (!$currentActive || $newSubscription->end_date->gt($currentActive->end_date)) {

                    $shouldUpdate = true;

                }

            }



            if ($shouldUpdate) {

                $trainer->isSubscribtionBuy = 'yes';

                $trainer->active_subscription_plan_id = $newSubscription->id;

                $trainer->save();

            }



            DB::commit();



            $data = [

                'sender_id' => $trainer->id,

                'sender_type' => 'Annual subscription for trainer.',

                'receiver_id' => '1',

                'message' => 'Trainer Plan Subscription paid in AED '.$plan->price .' active from '.now().' to '.now()->addDays($plan->duration_days) ,

                'is_read' => 0,

                'is_read_admin' => 0,

                'user_type' => 'trainer'

            ];



            Notification::insert($data);



            return response()->json([

                'status' => 'success',

                'message' => 'Subscription purchased successfully!'

            ]);



        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'status' => 'error',

                'message' => 'Something went wrong while purchasing the subscription.',

                'error' => $e->getMessage()

            ], 500);

        }

    }





    public function redirectToGoogle()
    {
        return Socialite::driver('google')
        ->redirectUrl(config('services.google.trainer_redirect'))
        ->redirect();

    }



    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
            ->redirectUrl(config('services.google.trainer_redirect'))
            ->stateless()
            ->user();


            $trainer = Trainers::where('email', $googleUser->getEmail())->first();

            if (!$trainer) {
                $plainPassword = Str::random(16);

                $trainer = trainers::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'status'            => 'active',
                    'password'          => bcrypt($plainPassword),
                    'pass'              => $plainPassword,
                    'email_verified_at' => now(),
                    'is_registered'     => 0,
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                ]);

                session([
                    'trainer_id' => $trainer->id,
                    'email'       => $trainer->email,
                ]);

                
                return redirect()->route('trainer.registration');
            }

            if ($trainer->status !== 'active') {
                return redirect()
                    ->route('trainer.login')
                    ->with('error', 'Your account is inactive. Please contact administrator.');
            }

            if ($trainer->is_registered == 1) {
                Auth::guard('trainer')->login($trainer);
                return redirect()->route('trainer.dashboard');
            }

            session([
                'trainer_id' => $trainer->id,
                'email'       => $trainer->email,
            ]);

            return redirect()->route('trainer.registration');

        } catch (\Exception $e) {
            return redirect()
                ->route('trainer.login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }

}

