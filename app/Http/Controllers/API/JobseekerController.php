<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Jobseekers;
use App\Models\EducationDetail;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\Skills;
use App\Models\AdditionalInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
class JobseekerController extends Controller
{
    public function signIn(Request $request)
    {
        // Validate input
       $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // ✅ return only the first error
            ], 422);
        }

        // Find the jobseeker by email
        $jobseeker = Jobseekers::where('email', $request->email)->first();

        if (!$jobseeker || $jobseeker->status != 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Please contact admimnistrator.'
            ], 401);
        }

        if (!in_array($jobseeker->admin_status, ['approved', 'superadmin_approved'])) {
            return response()->json([
                'status' => false,
                'message' => 'Your request approval is pending from the administrator. Please contact the administrator for assistance.'
            ], 401);
        }

        // Check if user exists and password matches
        if (!$jobseeker || !Hash::check($request->password, $jobseeker->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $iSRegistered = $jobseeker->status !== null;

        return response()->json([
            'status' => true,
            'iSRegistered' => $iSRegistered,
            'message' => 'Login successful',
            'data' => [
                'id' => $jobseeker->id,
                'name' => $jobseeker->name,
                'email' => $jobseeker->email,
                'is_registered' => $jobseeker->is_registered,
                'mobile' => $jobseeker->phone_number,
            ]
        ]);
    }


    public function signUp(Request $request)
    {
        try {
            // Validation
           $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:jobseekers,email',
                'mobile' => 'required|string|unique:jobseekers,phone_number|regex:/^[0-9]{10}$/',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'email.required' => 'The email field is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email is already registered.',

                'mobile.required' => 'The mobile number is required.',
                'mobile.string' => 'The mobile number must be a string.',
                'mobile.unique' => 'This mobile number is already registered.',
                'mobile.regex' => 'The mobile number must be exactly 10 digits.',

                'password.required' => 'The password is required.',
                'password.min' => 'The password must be at least 6 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(), // ✅ return only the first error
                ], 422);
            }

            // Create jobseeker
            $jobseeker = Jobseekers::create([
                'email' => $request->email,
                'phone_number' => $request->mobile,
                'password' => Hash::make($request->password),
                'pass' => $request->password, // Optional: for development only
            ]);

            $contactMethod = $request->email ? 'email' : 'phone_number';
            $contactValue = $request->$contactMethod;

            // Send OTP (Email or SMS)
            if ($contactMethod === 'email') {
                Mail::html('
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <title>Welcome to Talentrek</title>
                            <style>
                                body {
                                    background-color: #f4f6f9;
                                    font-family: Arial, sans-serif;
                                    padding: 20px;
                                    margin: 0;
                                }
                                .email-container {
                                    background: #ffffff;
                                    max-width: 600px;
                                    margin: auto;
                                    padding: 30px;
                                    border-radius: 8px;
                                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                                }
                                h2 {
                                    color: #007bff;
                                    margin-bottom: 20px;
                                }
                                p {
                                    line-height: 1.6;
                                    color: #333333;
                                }
                                .footer {
                                    margin-top: 30px;
                                    font-size: 12px;
                                    color: #888888;
                                    text-align: center;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="email-container">
                                <h2>Welcome to Talentrek!</h2>
                                <p>Hello <strong>' . e($jobseeker->email) . '</strong>,</p>

                                <p>You have successfully signed up on <strong>Talentrek</strong>. We\'re excited to have you with us!</p>

                                <p>Start exploring career opportunities, connect with employers, and grow your professional journey.</p>

                                <p>If you ever need help, feel free to contact our support team.</p>

                                <p>Warm regards,<br><strong>The Talentrek Team</strong></p>
                            </div>

                            <div class="footer">
                                © ' . date('Y') . ' Talentrek. All rights reserved.
                            </div>
                        </body>
                        </html>
                        ', function ($message) use ($jobseeker) {
                            $message->to($jobseeker->email)
                                    ->subject('Welcome to Talentrek – Signup Successful');
                        });

            } else {
                // Send SMS - Simulate (Integrate with Twilio, Msg91, etc.)
                // SmsService::send($contactValue, "Your OTP is: $otp");
            }

            return response()->json([
                'status' => true,
                'message' => 'Registration successful',
                'data' => [
                    'id' => $jobseeker->id,
                    'email' => $jobseeker->email,
                    'mobile' => $jobseeker->phone_number,
                    'via' => $contactMethod,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function registration(Request $request)
    {
        DB::beginTransaction();
        try {
            // Check if jobseeker exists (based on email and mobile)
            $jobseeker = Jobseekers::where('email', $request->email)
                ->where('phone_number', $request->mobile)
                ->first();

            if (!$jobseeker) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Please complete signup before registration.'
                ], 400);
            }

            // Validate registration fields
            // $request->validate([
            //     'name'         => 'required|string|max:255',
            //     'country_code' => 'required|string|max:5',
            //     'gender'       => 'required|in:Male,Female,Other',
            //     'date_of_birth'=> 'required|date|before:today',
            //     'location'     => 'required|string|max:255',
            //     'address'      => 'required|string|max:500',
            //     //'password'     => 'required|string|min:6|confirmed',

            //     // Education
            //     'education' => 'required|array|min:1',
            //     'education.*.high_education' => 'required|string|max:255',
            //     'education.*.field_of_study' => 'required|string|max:255',
            //     'education.*.institution' => 'required|string|max:255',
            //     'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,

            //     // Experience
            //     'experience' => 'nullable|array',
            //     'experience.*.job_role' => 'required|string|max:255',
            //     'experience.*.organization' => 'required|string|max:255',
            //     'experience.*.start_date' => 'required|date|before_or_equal:today',
            //     'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',

            //     // Skills and links
            //     'skills' => 'nullable|string',
            //     'interest' => 'nullable|string',
            //     'job_category' => 'nullable|string',
            //     'website_link' => 'nullable|url',
            //     'portfolio_link' => 'nullable|url',

            //     // Files
            //     'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            //     'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            // ]);

            $data = $request->all();

            $rules = [
                'name' => 'required|string',
                'country_code' => 'required|string',
                'gender' => 'required|in:Male,Female,Other',
                //'date_of_birth' => 'required',
                'location' => 'required|string',
                'address' => 'required|string',
                'skills' => 'required|string',
                'interest' => 'required|string',
                'job_category' => 'required|string',
                'website_link' => 'nullable|url',
                'portfolio_link' => 'nullable|url',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'required|file|image|max:2048',
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

            // Add dynamic validation for education
            if (!empty($data['education'])) {
                foreach ($data['education'] as $index => $edu) {
                    $rules["education.$index.high_education"] = 'required|string';
                    $rules["education.$index.field_of_study"] = 'required|string';
                    $rules["education.$index.institution"] = 'required|string';
                    $rules["education.$index.graduate_year"] = ['required', 'digits:4', 'integer', 'max:' . now()->year];
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'The education details must be required.'
                ], 200);
            }

            // Add dynamic validation for experience
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

            // Update the jobseeker basic info
            $jobseeker->update([
                'name'         => $request->name,
                'phone_code'   => $request->country_code,
                'gender'       => $request->gender,
                'date_of_birth'=> Carbon::createFromFormat('d/m/Y', $request->date_of_birth),
                'address'      => $request->location,
                'city'         => $request->city,
                'state'      => $request->state,
                'country'      => $request->country,
                'pin_code'      => $request->pincode,
                'national_id'      => $request->national_id,
                'is_registered'=> true, // you should add this column to your table
            ]);

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

            // Save experience
            foreach ($request->experience as $exp) {
                WorkExperience::create([
                    'user_id'      => $jobseeker->id,
                    'user_type'    => 'jobseeker',
                    'job_role'     => $exp['job_role'],
                    'organization' => $exp['organization'],
                    'starts_from'  => Carbon::createFromFormat('d/m/Y', $exp['start_date']),
                    'end_to'       => strtolower(trim($exp['end_date'])) === 'work here' ? 'work here' : Carbon::createFromFormat('d/m/Y', $exp['end_date'])
                ]);
            }

            // Save skills and interests
            Skills::create([
                'jobseeker_id'   => $jobseeker->id,
                'skills'         => $request->skills,
                'interest'       => $request->interest,
                'job_category'   => $request->job_category,
                'website_link'   => $request->website_link,
                'portfolio_link' => $request->portfolio_link
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

            DB::commit();

            $contactMethod = $request->email ? 'email' : 'phone_number';
            $contactValue = $request->$contactMethod;

            // Send OTP (Email or SMS)
            if ($contactMethod === 'email') {
                // Send confirmation email
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
            } else {
                // Send SMS - Simulate (Integrate with Twilio, Msg91, etc.)
                // SmsService::send($contactValue, "Your OTP is: $otp");
            }

            
            return response()->json([
                'status'  => true,
                'message' => 'Registration completed successfully.',
                'data'    => [
                    'id'     => $jobseeker->id,
                    'email'  => $jobseeker->email,
                    'mobile' => $jobseeker->phone_number,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|exists:jobseekers,email',
            'phone_number' => 'nullable|string|exists:jobseekers,phone_number',
        ], [
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email is not registered.',
            'phone_number.exists' => 'This phone number is not registered.',
        ]);

        // Custom rule: At least one of email or phone_number must be provided
        $validator->after(function ($validator) use ($request) {
            if (empty($request->email) && empty($request->phone_number)) {
                $validator->errors()->add('email', 'Either email or phone number is required.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // ✅ One error only
            ], 422);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Use either email or phone
        $contactMethod = $request->email ? 'email' : 'phone_number';
        $contactValue = $request->$contactMethod;

        // Store OTP
        DB::table('jobseekers')->updateOrInsert(
            [$contactMethod => $contactValue],
            [
                'otp' => $otp,
            ]
        );

        // Send OTP (Email or SMS)
        if ($contactMethod === 'email') {
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
                    ', function ($message) use ($contactValue) {
                        $message->to($contactValue)
                                ->subject('Your Password Reset OTP – Talentrek');
                    });

        } else {
            // Send SMS - Simulate (Integrate with Twilio, Msg91, etc.)
            // SmsService::send($contactValue, "Your OTP is: $otp");
        }

        return response()->json([
            'message' => 'OTP sent successfully',
            'via' => $contactMethod,
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|exists:jobseekers,email',
            'phone_number' => 'nullable|string|exists:jobseekers,phone_number',
            'otp' => 'required|digits:6',
        ], [
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email is not registered.',
            'phone_number.exists' => 'This phone number is not registered.',
            'otp.required' => 'OTP is required.',
            'otp.digits' => 'OTP must be exactly 6 digits.',
        ]);

        // Custom rule: At least one of email or phone_number must be provided
        $validator->after(function ($validator) use ($request) {
            if (empty($request->email) && empty($request->phone_number)) {
                $validator->errors()->add('email', 'Either email or phone number is required.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // ✅ Return only the first error
            ], 422);
        }

        $contactMethod = $request->email ? 'email' : 'phone_number';
        $contactValue = $request->$contactMethod;
        $otp = $request->otp;

        // Fetch the jobseeker record
        $jobseeker = DB::table('jobseekers')
            ->where($contactMethod, $contactValue)
            ->where('otp', $otp)
            ->first();

        if (!$jobseeker) {
            return response()->json([
                'message' => 'Invalid OTP or contact info.',
            ], 401);
        }

        return response()->json([
            'message' => 'OTP verified successfully',
            'contact_method' => $contactMethod,
            'contact_value' => $contactValue
        ]);
    }

    public function resetPassword(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|exists:jobseekers,email',
            'phone_number' => 'nullable|string|exists:jobseekers,phone_number',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email is not registered.',
            'phone_number.exists' => 'This phone number is not registered.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 6 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        // Custom rule: At least one of email or phone_number must be provided
        $validator->after(function ($validator) use ($request) {
            if (empty($request->email) && empty($request->phone_number)) {
                $validator->errors()->add('email', 'Either email or phone number is required.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // ✅ Only one error shown
            ], 422);
        }

        $contactMethod = $request->email ? 'email' : 'phone_number';
        $contactValue = $request->$contactMethod;

        $jobseeker = DB::table('jobseekers')
            ->where($contactMethod, $contactValue)
            ->first();

        if (!$jobseeker) {
            return response()->json([
                'message' => 'OTP not verified or user not found.',
            ], 401);
        }

        // Update password
        DB::table('jobseekers')
            ->where($contactMethod, $contactValue)
            ->update([
                'password' => Hash::make($request->new_password),
                'pass' => $request->new_password,
            ]);

        // ✅ Send password reset confirmation email
        if ($contactMethod === 'email') {
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

        return response()->json([
            'message' => 'Password has been reset successfully.',
            'contact_method' => $contactMethod,
            'contact_value' => $contactValue
        ]);
    }

}
