<?php
namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Jobseekers;
use App\Models\Assessors;
use App\Models\Mentors;
use App\Models\Coach;
use App\Models\Trainers;
use App\Models\EducationDetail;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\Skills;
use App\Models\AdditionalInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
class AppAuthenticationController extends Controller
{
    public function signIn(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
            'type'     => 'required|in:jobseeker,mentor,assessor,coach,trainer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // ✅ Only the first error message
            ], 422);
        }
        $map = [
            'mentor'    => 'mentors',
            'assessor'  => 'assessors',
            'jobseeker' => 'jobseeker',
        ];
        $type = $map[$request->type] ?? 'coach';
        $modelMap = [
            'jobseeker' => \App\Models\Jobseekers::class,
            'mentors' => \App\Models\Mentors::class,
            'assessors' => \App\Models\Assessors::class,
            'coach' => \App\Models\Coach::class,
            'trainer' => \App\Models\Trainers::class,
        ];
        $model = $modelMap[$type];
        $user = $model::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }
        $iSRegistered = $user->status !== null;
        return response()->json([
            'status' => true,
            'iSRegistered' => $iSRegistered,
            'message' => ucfirst($request->type) . ' login successful',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_registered' => $trainer->is_registered,
                'type' => $request->type,
            ]
        ]);
    }
    public function signUp(Request $request)
    {
        // Step 1: Validation
       $validator = Validator::make($request->all(), [
            'type'     => 'required|in:jobseeker,mentor,assessor,coach,trainer',
            'email'    => 'required|email',
            'mobile'   => 'required|string|regex:/^[0-9]{10}$/',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(), // ✅ Show only first error
            ], 422);
        }
        try {
            // Step 2: Model mapping
           $map = [
                'mentor'    => 'mentors',
                'assessor'  => 'assessors',
                'jobseeker' => 'jobseeker',
            ];
            $type = $map[$request->type] ?? 'coach';
        $modelMap = [
            'jobseeker' => \App\Models\Jobseekers::class,
            'mentors' => \App\Models\Mentors::class,
            'assessors' => \App\Models\Assessors::class,
            'coach' => \App\Models\Coach::class,
            'trainer' => \App\Models\Trainers::class,
        ];
        $model = $modelMap[$type];
            // Step 3: Check uniqueness manually
            $existingUser = $model::where('email', $request->email)
                ->orWhere('phone_number', $request->mobile)
                ->first();
            if ($existingUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email or Mobile already exists for ' . ucfirst($request->type)
                ], 409);
            }
            // Step 4: Create user
            $user = $model::create([
                'email' => $request->email,
                'phone_number' => $request->mobile,
                'password' => Hash::make($request->password),
                'pass' => $request->password, // Optional: dev only
            ]);
            $contactMethod = $request->email ? 'email' : 'phone_number';
            $contactValue = $request->$contactMethod;
            // Step 5: Send Email (or simulate SMS)
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
                            <p>Hello <strong>' . e($user->email) . '</strong>,</p>
                            <p>You have successfully signed up on <strong>Talentrek</strong> as a ' . ucfirst($request->type) . '. We\'re excited to have you with us!</p>
                            <p>Start exploring opportunities and grow your journey with us.</p>
                            <p>Warm regards,<br><strong>The Talentrek Team</strong></p>
                        </div>
                        <div class="footer">
                            © ' . date('Y') . ' Talentrek. All rights reserved.
                        </div>
                    </body>
                    </html>
                ', function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Welcome to Talentrek – Signup Successful');
                });
            } else {
                // Simulate SMS (you can integrate Twilio, Msg91, etc.)
            }
            // Step 6: Return response
            return response()->json([
                'status' => true,
                'message' => ucfirst($request->type) . ' registration successful',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'mobile' => $user->phone_number,
                    'type' => $request->type,
                    'via' => $contactMethod,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'         => 'required|in:jobseeker,mentor,assessor,coach,trainer',
            'email'        => 'nullable|email',
            'phone_number' => 'nullable|string|regex:/^[0-9]{10}$/',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (empty($request->email) && empty($request->phone_number)) {
                $validator->errors()->add('email', 'Either email or phone number is required.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // ✅ Only the first error
            ], 422);
        }
        $map = [
            'mentor'    => 'mentors',
            'assessor'  => 'assessors',
            'jobseeker' => 'jobseeker',
        ];
        $type = $map[$request->type] ?? 'coach';
        $modelMap = [
            'jobseeker' => \App\Models\Jobseekers::class,
            'mentors' => \App\Models\Mentors::class,
            'assessors' => \App\Models\Assessors::class,
            'coach' => \App\Models\Coach::class,
            'trainer' => \App\Models\Trainer::class,
        ];
        $model = $modelMap[$type];
        $contactMethod = $request->email ? 'email' : 'phone_number';
        $contactValue = $request->$contactMethod;
        // Check user existence
        $user = $model::where($contactMethod, $contactValue)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found for provided ' . $contactMethod,
            ], 404);
        }
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        // Update OTP
        $user->otp = $otp;
        $user->save();
        // Send OTP
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
            // Simulate SMS sending
            // SmsService::send($contactValue, "Your OTP is: $otp");
        }
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'via' => $contactMethod,
            'type' => $type
        ]);
    }
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'         => 'required|in:jobseeker,mentor,assessor,coach,trainer',
            'email'        => 'nullable|email',
            'phone_number' => 'nullable|string|regex:/^[0-9]{10}$/',
            'otp'          => 'required|digits:6',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (empty($request->email) && empty($request->phone_number)) {
                $validator->errors()->add('email', 'Either email or phone number is required.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(), // ✅ Only first error
            ], 422);
        }
        
        $map = [
            'mentor'    => 'mentors',
            'assessor'  => 'assessors',
            'jobseeker' => 'jobseeker',
        ];
        $type = $map[$request->type] ?? 'coach';
        $modelMap = [
            'jobseeker' => \App\Models\Jobseekers::class,
            'mentors' => \App\Models\Mentors::class,
            'assessors' => \App\Models\Assessors::class,
            'coach' => \App\Models\Coach::class,
            'trainer' => \App\Models\Trainers::class,
        ];
        $model = $modelMap[$type];
        $contactMethod = $request->email ? 'email' : 'phone_number';
        $contactValue = $request->$contactMethod;
        $otp = $request->otp;
        $user = $model::where($contactMethod, $contactValue)
                    ->where('otp', $otp)
                    ->first();
        if (!$user) {
            return response()->json([
                'message' => 'Invalid OTP or contact info.',
            ], 401);
        }
        // Optional: clear OTP after verification
        $user->otp = null;
        $user->save();
        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully',
            'type' => $type,
            'contact_method' => $contactMethod,
            'contact_value' => $contactValue,
            'user_id' => $user->id,
            'email' => $user->email ?? null,
            'phone_number' => $user->phone_number ?? null
        ]);
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'          => 'required|in:jobseeker,mentor,assessor,coach,trainer',
            'email'         => 'nullable|email',
            'phone_number'  => 'nullable|string|regex:/^[0-9]{10}$/',
            'new_password'  => 'required|string|min:6|confirmed',
        ]);

        // Require either email or phone_number
        $validator->after(function ($validator) use ($request) {
            if (empty($request->email) && empty($request->phone_number)) {
                $validator->errors()->add('email', 'Either email or phone number is required.');
            }
        });

        // Return only the first validation error
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        
        $map = [
            'mentor'    => 'mentors',
            'assessor'  => 'assessors',
            'jobseeker' => 'jobseeker',
        ];
        $type = $map[$request->type] ?? 'coach';
        $modelMap = [
            'jobseeker' => \App\Models\Jobseekers::class,
            'mentors' => \App\Models\Mentors::class,
            'assessors' => \App\Models\Assessors::class,
            'coach' => \App\Models\Coach::class,
            'trainer' => \App\Models\Trainers::class,
        ];
        $model = $modelMap[$type];
        $contactMethod = $request->email ? 'email' : 'phone_number';
        $contactValue = $request->$contactMethod;
        // Find the user
        $user = $model::where($contactMethod, $contactValue)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->pass = $request->new_password; // ⚠️ Remove this in production
        $user->save();
        // Send confirmation mail
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
                        <p>Hello <strong>' . e($user->email) . '</strong>,</p>
                        <p>Your password has been successfully updated for your Talentrek account.</p>
                        <p>If you didn\'t initiate this change, please contact our support team immediately.</p>
                        <p>Stay safe,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">
                        &copy; ' . date('Y') . ' Talentrek. All rights reserved.
                    </div>
                </body>
                </html>
            ', function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Talentrek Password Has Been Reset');
            });
        }
        return response()->json([
            'message' => 'Password has been reset successfully.',
            'contact_method' => $contactMethod,
            'contact_value' => $contactValue,
            'type' => $type,
        ]);
    }
}
