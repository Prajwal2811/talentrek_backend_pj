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
use DateTime;
use Illuminate\Support\Facades\Mail;
class JobseekerController extends Controller
{
    public function signIn(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Find the jobseeker by email
        $jobseeker = Jobseekers::where('email', $request->email)->first();

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
                'email' => $jobseeker->email
            ]
        ]);


    }


    public function signUp(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'email' => 'required|email|unique:jobseekers,email',
                'mobile' => 'required|string|unique:jobseekers,phone_number|regex:/^[0-9]{10}$/',
                'password' => 'required|string|min:6|confirmed',
            ]);

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
            $validator = Validator::make($request->all(),[
                'name'         => 'required|string|max:255',
                'country_code' => 'required|string|max:5',
                'gender'       => 'required|in:Male,Female,Other',
                //'date_of_birth'=> 'required|date|before:today',
                'location'     => 'required|string|max:255',
                'address'      => 'required|string|max:500',
                //'password'     => 'required|string|min:6|confirmed',

                // Education
                'education' => 'required|array|min:1',
                'education.*.high_education' => 'required|string|max:255',
                'education.*.field_of_study' => 'required|string|max:255',
                'education.*.institution' => 'required|string|max:255',
                //'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,

                // Experience
                'experience' => 'nullable|array',
                'experience.*.job_role' => 'required|string|max:255',
                'experience.*.organization' => 'required|string|max:255',
                //'experience.*.start_date' => 'required|date|before_or_equal:today',
                'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',

                // Skills and links
                'skills' => 'nullable|string',
                'interest' => 'nullable|string',
                'job_category' => 'nullable|string',
                'website_link' => 'nullable|url',
                'portfolio_link' => 'nullable|url',

                // Files
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            
            if ($validator->fails()) {
                $firstError = collect($validator->errors()->messages())
                    ->map(function ($messages, $field) {
                        return [
                            'field' => $field,
                            'message' => $messages[0]
                        ];
                    })->first();

                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $firstError['message'],
                ], 200);
            }

            $inputDate = $request->date_of_birth ;
            $dateOfBirth = Carbon::createFromFormat('d/m/Y', $inputDate);
            $today = Carbon::today();

            if (empty($dateOfBirth)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => 'The Date of birth field is required.'                        
                ], 200);
            }
            if ($dateOfBirth->gte($today)) {
                return response()->json([
                                'status' => false,
                                'message' => 'Validation failed',
                                'errors' => 'The Date of birth must be a date before today.'
                            ], 200);
            }            

            if (Carbon::parse($dateOfBirth)->isToday() || Carbon::parse($dateOfBirth)->isFuture()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => 'The Date of birth must be a date before today.'                       
                ], 200);
            }

            $education =  $request->education;

            foreach ($education as $index => $edu) {
               
                $entryNumber = $index + 1; // 1-based index for user-friendly message
                $graduateYear = $edu['graduate_year'] ?? null;

                if (is_null($graduateYear)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "Education $entryNumber: The graduate_year field is required."                       
                    ], 200);
                }

                if (!is_numeric($graduateYear)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "Education $entryNumber: The graduate_year must be a number."
                    ], 200);
                }

                if (strlen((string)$graduateYear) !== 4) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "Education $entryNumber: The graduate_year must be exactly 4 digits."
                    ], 200);
                }

                if ((int)$graduateYear < 1900 || (int)$graduateYear > now()->year) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "Education $entryNumber: The graduate year must be between 1900 and " . now()->year . "."
                    ], 200);
                }

                // Stop after the first invalid one
                //break;
            }

            $experiences = $request->input('experience', []);

            foreach ($experiences as $index => $exp) {
                if (empty($exp['start_date'])) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "The start date field is required for experience " . ($index + 1) . "."
                    ], 200);
                }

                if (empty($exp['end_date'])) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "The start date field is required for experience " . ($index + 1) . "."
                    ], 200);
                }

                try {
                    // Format: d/m/Y — adjust if input format is different
                    $startDate = Carbon::createFromFormat('d/m/Y', $exp['start_date']);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "The start date for experience " . ($index + 1) . " must be a valid date in DD/MM/YYYY format."
                    ], 200);
                }

                try {
                    // Format: d/m/Y — adjust if input format is different
                    $endDate = Carbon::createFromFormat('d/m/Y', $exp['end_date']);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "The end date for experience " . ($index + 1) . " must be a valid date in DD/MM/YYYY format."
                    ], 200);
                }

                if ($startDate->gt(Carbon::today())) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "The start date for experience " . ($index + 1) . " must be a date before or equal to today."
                    ], 200);
                }

                if ($endDate->gt(Carbon::today())) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "The end date for experience " . ($index + 1) . " must be a date before or equal to today."
                    ], 200);
                }

                if ($endDate->lt($startDate)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation failed',
                        'errors' => "The end date for experience " . ($index + 1) . " must be after or equal to the start date."
                    ], 200);
                }
            }
            // Update the jobseeker basic info
            $jobseeker->update([
                'name'         => $request->name,
                'phone_code'   => $request->country_code,
                'gender'       => $request->gender,
                'date_of_birth'=> $dateOfBirth,
                'city'         => $request->location,
                'address'      => $request->address,
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
                    'starts_from'  => date('Y-m-d',strtotime($exp['start_date'])),
                    'end_to'       => date('Y-m-d',strtotime($exp['end_date']))
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 201);
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 201);
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 201);
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
