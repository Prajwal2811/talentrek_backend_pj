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

        if (Auth::guard('jobseeker')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('jobseeker.profile');
        } else {
            session()->flash('error', 'Either Email/Password is incorrect');
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
        //dd($request->all());exit;
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

        return redirect()->back()->with('success', 'Personal information updated successfully!');
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

        return redirect()->back()->with('success', 'Education information saved successfully!');
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

        return redirect()->back()->with('success', 'Work Experience information saved successfully!');
    }


    public function updateSkillsInfo(Request $request)
    {
        //dd($request->all());exit;
        $user = auth()->user();
        $user_id =  $user->id;
        $validated = $request->validate([
            'skills' => 'nullable|string',
            'interest' => 'nullable|string',
            'job_category' => 'nullable|string|max:255',
            'website_link' => 'nullable|url',
            'portfolio_link' => 'nullable|url',
        ]);
        $skills = Skills::where('jobseeker_id', $user_id) ->first();
        if ($skills) {
            // Update if record exists
            $skills->update([
                'skills' => $validated['skills'],
                'interest' => $validated['interest'],
                'job_category' => $validated['job_category'],
                'website_link' => $validated['website_link'],
                'portfolio_link' => $validated['portfolio_link'],
            ]);

            return redirect()->back()->with('success', 'Skills updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Skills record not found for update.');
        }                       

        
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