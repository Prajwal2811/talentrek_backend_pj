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

        return response()->json([
            'status' => true,
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

            return response()->json([
                'status' => true,
                'message' => 'Registration successful',
                'data' => [
                    'id' => $jobseeker->id,
                    'email' => $jobseeker->email,
                    'mobile' => $jobseeker->phone_number,
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
            $request->validate([
                'name'         => 'required|string|max:255',
                'country_code' => 'required|string|max:5',
                'gender'       => 'required|in:Male,Female,Other',
                'date_of_birth'=> 'required|date|before:today',
                'location'     => 'required|string|max:255',
                'address'      => 'required|string|max:500',
                'password'     => 'required|string|min:6|confirmed',

                // Education
                'education' => 'required|array|min:1',
                'education.*.high_education' => 'required|string|max:255',
                'education.*.field_of_study' => 'required|string|max:255',
                'education.*.institution' => 'required|string|max:255',
                'education.*.graduate_year' => 'required|digits:4|integer|min:1900|max:' . now()->year,

                // Experience
                'experience' => 'nullable|array',
                'experience.*.job_role' => 'required|string|max:255',
                'experience.*.organization' => 'required|string|max:255',
                'experience.*.start_date' => 'required|date|before_or_equal:today',
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

            // Update the jobseeker basic info
            $jobseeker->update([
                'name'         => $request->name,
                'phone_code'   => $request->country_code,
                'gender'       => $request->gender,
                'date_of_birth'=> $request->date_of_birth,
                'city'         => $request->location,
                'address'      => $request->address,
                'password'     => Hash::make($request->password),
                'pass'         => $request->password,
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
                    'starts_from'  => $exp['start_date'],
                    'end_to'       => $exp['end_date']
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
            ], 422);
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
            // Send email (example only)
            // Mail::raw("Your OTP is: $otp", function ($message) use ($contactValue) {
            //     $message->to($contactValue)
            //         ->subject('Password Reset OTP');
            // });
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
            'new_password' => 'required|string|min:6|confirmed', // includes new_password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
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

        return response()->json([
            'message' => 'Password has been reset successfully.',
            'contact_method' => $contactMethod,
            'contact_value' => $contactValue
        ]);
    }
    

}
