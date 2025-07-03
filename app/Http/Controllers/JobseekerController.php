<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Jobseekers;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\Skills;
use App\Models\Additionalinfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;    
use DB;

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
            // 'phone_number' => 'required|digits:10|unique:jobseekers,phone_number',
            'phone_number' => 'required|unique:jobseekers,phone_number',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
     

         $jobseekers = Jobseekers::create([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'pass' => $request->password,
             
        ]);
        session([
            'jobseeker_id' => $jobseekers->id,
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:jobseekers,email,' . $jobseeker->id,
            'phone_number' => 'required|unique:jobseekers,phone_number,' . $jobseeker->id,
            'dob' => 'required|date',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'gender' => 'required|string|in:Male,Female,Other',

            // // Education array validations
            'high_education.*' => 'required|string',
            'field_of_study.*' => 'nullable|string',
            'institution.*' => 'required|string',
            'graduate_year.*' => 'required|string',

            // Work experience array validations
            'job_role.*' => 'required|string',
            'organization.*' => 'required|string',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'required|date|after_or_equal:starts_from.*',

            // skills validations
            'skills' => 'required|string',
            'interest' => 'required|string',
            'job_category' => 'required|string|max:255',
            'website_link' => 'required|url',
            'portfolio_link' => 'required|url',

             // Files
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
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
                WorkExperience::create([
                    'user_id' => $jobseeker->id,
                    'user_type' => 'jobseeker',
                    'job_role' => $role,
                    'organization' => $request->organization[$index] ?? null,
                    'starts_from' => $request->starts_from[$index] ?? null,
                    'end_to' => $request->end_to[$index] ?? null,
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
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'date_of_birth' => $validated['dob'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
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
        ]);

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
            'skills' => 'nullable|string',
            'interest' => 'nullable|string',
            'job_category' => 'nullable|string|max:255',
            'website_link' => 'nullable|url',
            'portfolio_link' => 'nullable|url',
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
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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


    public function resetPassword(Request $request){
       $request->validate([
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
        $jobseekerId = session('verified_jobseeker');
       
        if (!$jobseekerId) {
            return redirect()->route('jobseeker.forget-password')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $updated = DB::table('jobseekers')->where('id', $jobseekerId)->update([
            'password' => Hash::make($request->new_password),
            'pass' => $request->new_password,
            'otp' => null, 
            'updated_at' => now(),
        ]);
         
        session()->forget('verified_jobseeker');
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

        return redirect()->route('signin.form')->with('success', 'Password change successfully.');
    }

    




   
    
}