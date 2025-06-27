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
use DB;

class JobseekerController extends Controller
{
    public function postRegistration(Request $request)
    {
        $validated = $request->validate([
       
            'email' => 'required|email|unique:jobseekers,email',
            'phone_number' => 'required|digits:10|unique:jobseekers,phone_number',
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

        return view('site.jobseeker.registration');
    }
  
    public function showDetailsForm()
    {
        $email = session('email');
        $phone = session('phone_number');
        $jobseekerId = session('jobseeker_id');
        $jobseeker = Jobseekers::find($jobseekerId);

        return view('site.jobseeker.registration', compact('jobseeker','email','phone'));
    }


    // public function storeJobseekerInformation(Request $request)
    // {
    //     $jobseekerId = session('jobseeker_id');

    //     if (!$jobseekerId) {
    //         return redirect()->route('signup.form')->with('error', 'Session expired. Please sign up again.');
    //     }

    //     $jobseeker = Jobseekers::find($jobseekerId);

    //     if (!$jobseeker) {
    //         return redirect()->route('signup.form')->with('error', 'Jobseeker not found.');
    //     }

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:jobseekers,email,' . $jobseeker->id,
    //         'phone_number' => 'required|digits:10|unique:jobseekers,phone_number,' . $jobseeker->id,
    //         'dob' => 'required|date',
    //         'city' => 'required|string|max:255',
    //         'address' => 'required|string|max:500',
    //         'gender' => 'required|string|in:Male,Female,Other',

    //         'high_education' => 'required|string|max:255',
    //         'field_of_study' => 'nullable|string|max:255',
    //         'institution' => 'nullable|string|max:255',
    //         'graduate_year' => 'nullable|numeric',

    //         'job_role' => 'nullable|string|max:255',
    //         'organization' => 'nullable|string|max:255',
    //         'starts_from' => 'nullable|date',
    //         'end_to' => 'nullable|date|after_or_equal:starts_from',

    //         'skills' => 'nullable|string',
    //         'interest' => 'nullable|string',
    //         'job_category' => 'nullable|string|max:255',
    //         'website_link' => 'nullable|url',
    //         'portfolio_link' => 'nullable|url',
    //     ]);

    //     // Update existing jobseeker details
    //     $jobseeker->update([
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'phone_number' => $validated['phone_number'],
    //         'date_of_birth' => $validated['dob'],
    //         'city' => $validated['city'],
    //         'address' => $validated['address'],
    //         'gender' => $validated['gender'],
    //     ]);

    //     // Insert related education data
    //     EducationDetails::create([
    //         'user_id' => $jobseeker->id,
    //         'user_type' => 'jobseeker',
    //         'high_education' => $request->high_education,
    //         'field_of_study' => $request->field_of_study,
    //         'institution' => $request->institution,
    //         'graduate_year' => $request->graduate_year,
    //     ]);

    //     // Insert work experience
    //     WorkExperience::create([
    //         'user_id' => $jobseeker->id,
    //         'user_type' => 'jobseeker',
    //         'job_role' => $request->job_role,
    //         'organization' => $request->organization,
    //         'starts_from' => $request->starts_from,
    //         'end_to' => $request->end_to,
    //     ]);

    //     // Insert skills
    //     Skills::create([
    //         'jobseeker_id' => $jobseeker->id,
    //         'skills' => $request->skills,
    //         'interest' => $request->interest,
    //         'job_category' => $request->job_category,
    //         'website_link' => $request->website_link,
    //         'portfolio_link' => $request->portfolio_link,
    //     ]);

    //     // Clear session
    //     session()->forget('jobseeker_id');

    //     return redirect()->route('jobseeker.sign-in')->with('success_popup', true);

    // }

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
            'phone_number' => 'required|digits:10|unique:jobseekers,phone_number,' . $jobseeker->id,
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
        return redirect()->route('jobseeker.sign-in')->with('success_popup', true);
    }
 

    public function showSignInForm()
    {
        return view('site.jobseeker.sign-in'); 
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

        if (Auth::guard('jobseeker')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => "active"])) {
            return redirect()->route('jobseeker.profile');
        } else {
            session()->flash('error', 'Either Email/Password is incorrect');
            return back()->withInput($request->only('email'));
        }
    }

    // public function getJobseekerAllDetails(){
    //     $jobseeker = Auth::guard('jobseeker')->user();
    //     $jobseekerId = $jobseeker->id;
    //     $data = DB::table('jobseekers')
    //         ->leftJoin('education_details', 'education_details.user_id', '=', 'jobseekers.id')
    //         ->leftJoin('work_experience', 'work_experience.user_id', '=', 'jobseekers.id')
    //         ->leftJoin('skills', 'skills.jobseeker_id', '=', 'jobseekers.id')
    //         ->where('jobseekers.id', $jobseekerId)
    //         ->select('jobseekers.*', 'education_details.*','work_experience.*','skills.*', ) 
    //         ->first();
    //     echo "<pre>";
    //     print_r($data);exit;
    //     echo "</pre>";
    //      return view('site.jobseeker.profile', compact('data'));
    // }

    public function getJobseekerAllDetails()
    {
        $jobseeker = Auth::guard('jobseeker')->user();
        $jobseekerId = $jobseeker->id;

        $data = DB::table('jobseekers')
            ->leftJoin('education_details', 'education_details.user_id', '=', 'jobseekers.id')
            ->leftJoin('work_experience', 'work_experience.user_id', '=', 'jobseekers.id')
            ->leftJoin('skills', 'skills.jobseeker_id', '=', 'jobseekers.id')
            ->where('jobseekers.id', $jobseekerId)
            ->select('jobseekers.*', 'education_details.*','work_experience.*','skills.*', ) 
         
            ->get();

        echo "<pre>";
        print_r($data);
        exit;

        return view('site.jobseeker.profile', compact('data'));
    }



    
    public function logoutJobseeker(Request $request)
    {
        Auth::guard('jobseeker')->logout();
       
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect()->route('jobseeker.sign-in')->with('success', 'Logged out successfully');
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

    public function updateEducationInfo(Request $request )
    {
        //dd($request->all());exit;
        $user = auth()->user();
        $user_id =  $user->id;
        
        $validated = $request->validate([
            'high_education' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'graduate_year' => 'nullable|numeric',
        ]);

        $education = EducationDetails::where('user_id', $user_id)
                    ->where('user_type', 'jobseeker')
                    ->first();
        if ($education) {
            // Update if record exists
            $education->update([
                'high_education' => $validated['high_education'],
                'field_of_study' => $validated['field_of_study'],
                'institution' => $validated['institution'],
                'graduate_year' => $validated['graduate_year'],
            ]);

            return redirect()->back()->with('success', 'Education information updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Education record not found for update.');
        }

    }


    // public function updateWorkExprienceInfo(Request $request)
    // {
    //     //dd($request->all());exit;
    //     $user = auth()->user();
    //     $user_id =  $user->id;
    //     $validated = $request->validate([
    //         'job_role' => 'nullable|string|max:255',
    //         'organization' => 'nullable|string|max:255',
    //         'starts_from' => 'nullable|date',
    //         'end_to' => 'nullable|date|after_or_equal:starts_from',
    //     ]);
    //     $workExperience = WorkExperience::where('user_id', $user_id)
    //                 ->where('user_type', 'jobseeker')
    //                 ->first();
        
    //     if ($workExperience) {
    //         // Update if record exists
    //         $workExperience->update([
    //             'job_role' => $validated['job_role'],
    //             'organization' => $validated['organization'],
    //             'starts_from' => $validated['starts_from'],
    //             'end_to' => $validated['end_to'],
    //         ]);

    //         return redirect()->back()->with('success', 'Work Exprience information updated successfully!');
    //     } else {
    //         return redirect()->back()->with('error', 'Work Exprience record not found for update.');
    //     }            

    // }
    public function updateWorkExprienceInfo(Request $request)
    {
        $user_id = auth()->id();

        $validated = $request->validate([
            'job_role' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'starts_from' => 'nullable|date',
            'end_to' => 'nullable|date|after_or_equal:starts_from',
            'currently_working' => 'nullable|boolean',
        ]);

        $workExperience = WorkExperience::where('user_id', $user_id)
            ->where('user_type', 'jobseeker')
            ->first();

        if ($workExperience) {
            $endToValue = $request->currently_working ? 'Work here' : ($validated['end_to'] ?? null);

            $workExperience->update([
                'job_role' => $validated['job_role'] ?? null,
                'organization' => $validated['organization'] ?? null,
                'starts_from' => $validated['starts_from'] ?? null,
                'end_to' => $endToValue,
            ]);

            return redirect()->back()->with('success', 'Work Experience information updated successfully!');
        }

        return redirect()->back()->with('error', 'Work Experience record not found for update.');
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

     



   

}