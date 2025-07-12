<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Mentors;
use App\Models\Trainers;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\TrainingExperience;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\AdditionalInfo;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;



class MentorController extends Controller
{
    public function showSignInForm(){
        return view('site.mentor.sign-in'); 
    }
    public function showSignUpForm()
    {
    return view('site.mentor.sign-up');
    }
    public function showRegistrationForm()
    {
    return view('site.mentor.registration');
    }
    public function showForgotPasswordForm()
    {
        return view('site.mentor.forget-password');
    }
    public function showOtpForm()
    {
        return view('site.mentor.verify-otp'); 
    }
    public function showResetPasswordForm()
    {
        return view('site.mentor.reset-password'); 
    }

    public function postRegistration(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:mentors,email',
            'phone_number' => 'required|unique:mentors,phone_number',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
        

        $mentors = Mentors::create([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'pass' => $request->password,
        ]);
        
      
        session([
            'mentor_id' => $mentors->id,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('mentor.registration');
    }

    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'contact' => ['required', function ($attribute, $value, $fail) {
                $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                $column = $isEmail ? 'email' : 'phone_number';

                $exists = DB::table('mentors')->where($column, $value)->exists();

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
        DB::table('mentors')->where($contactMethod, $contact)->update([
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
        return redirect()->route('mentor.verify-otp')->with('success', 'OTP sent!');
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

        $mentors = DB::table('mentors')
            ->where($column, $contact)
            ->where('otp', $request->otp)
            ->first();

        if (!$mentors) {
            return back()
                ->withErrors(['otp' => 'Invalid OTP or contact. Please enter the correct 6-digit OTP.'])
                ->withInput();
        }

        // Save verified user ID in session
        session(['verified_recruiter' => $mentors->id]);

        return redirect()->route('mentor.reset-password');
    }

    public function resetPassword(Request $request){
       $request->validate([
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
        $mentorId = session('verified_recruiter');
       
        if (!$mentorId) {
            return redirect()->route('mentor.forget.password')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $updated = DB::table('mentors')->where('id', $mentorId)->update([
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

        return redirect()->route('mentor.login')->with('success', 'Password change successfully.');
    } 

    public function storeMentorInformation(Request $request)
    {
        $mentorId = session('mentor_id');

        if (!$mentorId) {
            return redirect()->route('mentor.signup')->with('error', 'Session expired. Please sign up again.');
        }

        $mentor = Mentors::find($mentorId);

        if (!$mentor) {
            return redirect()->route('mentor.signup')->with('error', 'Trainer not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mentors,email,' . $mentor->id,
            'phone_number' => 'required|unique:mentors,phone_number,' . $mentor->id,
            'dob' => 'required|date',
            'city' => 'required|string|max:255',
            'national_id' => [
                'required',
                'min:10',
                function ($attribute, $value, $fail) use ($mentor) {
                    $existsInRecruiters = Recruiters::where('national_id', $value)->exists();
                    $existsInTrainers = Trainers::where('national_id', $value)->exists();
                    $existsInJobseekers = Jobseekers::where('national_id', $value)->exists();
                    $existsInMentors = Mentors::where('national_id', $value)
                        ->where('id', '!=', $mentor->id)
                        ->exists();

                    if ($existsInRecruiters || $existsInTrainers || $existsInJobseekers || $existsInMentors) {
                        $fail('The national ID has already been taken.');
                    }
                },
            ],

            'high_education.*' => 'required|string',
            'field_of_study.*' => 'nullable|string',
            'institution.*' => 'required|string',
            'graduate_year.*' => 'required|string',

            'job_role.*' => 'required|string',
            'organization.*' => 'required|string',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'required|date',

            'training_skills' => 'required|string',
            'website_link' => 'required|url',
            'portfolio_link' => 'required|url',

            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'training_certificate' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        DB::beginTransaction();

        // Update mentor profile
        $mentor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'date_of_birth' => $validated['dob'],
            'city' => $validated['city'],
            'national_id' => $validated['national_id'],
        ]);

        // Save education
        foreach ($request->high_education as $index => $education) {
            EducationDetails::create([
                'user_id' => $mentor->id,
                'user_type' => 'mentor',
                'high_education' => $education,
                'field_of_study' => $request->field_of_study[$index] ?? null,
                'institution' => $request->institution[$index],
                'graduate_year' => $request->graduate_year[$index],
            ]);
        }

        // Save work experience
        foreach ($request->job_role as $index => $role) {
            WorkExperience::create([
                'user_id' => $mentor->id,
                'user_type' => 'mentor',
                'job_role' => $role,
                'organization' => $request->organization[$index],
                'starts_from' => $request->starts_from[$index],
                'end_to' => $request->end_to[$index],
            ]);
        }

        // Save training experience
        TrainingExperience::create([
            'user_id' => $mentor->id,
            'user_type' => 'mentor',
            'training_skills' => $request->training_skills,
            'website_link' => $request->website_link,
            'portfolio_link' => $request->portfolio_link,
        ]);

        // File uploads
        $uploadTypes = [
            'resume' => 'mentor_resume',
            'profile_picture' => 'mentor_profile_picture',
            'training_certificate' => 'mentor_training_certificate',
        ];

        foreach ($uploadTypes as $field => $docType) {
            if ($request->hasFile($field)) {
                $existing = AdditionalInfo::where([
                    ['user_id', $mentor->id],
                    ['user_type', 'mentor'],
                    ['doc_type', $docType],
                ])->first();

                if (!$existing) {
                    $originalName = $request->file($field)->getClientOriginalName();
                    $extension = $request->file($field)->getClientOriginalExtension();
                    $filename = $docType . '_' . time() . '.' . $extension;
                    $request->file($field)->move('uploads/', $filename);

                    AdditionalInfo::create([
                        'user_id' => $mentor->id,
                        'user_type' => 'mentor',
                        'doc_type' => $docType,
                        'document_name' => $originalName,
                        'document_path' => asset('uploads/' . $filename),
                    ]);
                }
            }
        }

        DB::commit();

        session()->forget('mentor_id');
        return redirect()->route('mentor.login')->with('success_popup', true);
    }

    public function loginMentor(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $mentor = Mentors::where('email', $request->email)->first();

        if (!$mentor) {
            // Email does not exist
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }

        if ($mentor->status !== 'active') {
            // Status is inactive or blocked
            session()->flash('error', 'Your account is inactive. Please contact administrator.');
            return back()->withInput($request->only('email'));
        }

        // Now attempt login only if status is active
        if (Auth::guard('mentor')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // return view('site.trainer.trainer-dashboard');
            return redirect()->route('mentor.dashboard');
        } else {
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }
    }

    public function showMentorDashboard()
    {
        return view('site.mentor.mentor-dashboard');    
    }

    public function logoutMentor(Request $request)
    {
        Auth::guard('mentor')->logout();
        
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect()->route('mentor.login')->with('success', 'Logged out successfully');
    }

    public function aboutMentor(){
        return view('site.mentor.about-mentor'); 
    }
    public function manageBookingSlotsMentor(){
        return view('site.mentor.manage-booking-slots-mentor'); 
    }
    public function createBookingSlotsMentor(){
        return view('site.mentor.create-booking-slots-mentor'); 
    }
    public function chatWithJobseekerMentor(){
        return view('site.mentor.chat-jobseeker-mentor'); 
    }
    // public function mentorReviews(){
    //     $mentorReview = Review::where('user_type', 'mentor')->get();
    //     return view('site.mentor.reviews',compact('mentorReview')); 
    // }
   

    public function mentorReviews()
    {
        $reviews = DB::table('reviews')
            ->leftJoin('jobseekers', 'jobseekers.id', '=', 'reviews.jobseeker_id')
            // ->leftJoin('courses', 'courses.id', '=', 'reviews.course_id')
            ->where('reviews.user_type', 'mentor')
            ->select(
                'reviews.id',
                'reviews.reviews',
                'reviews.ratings',
                'reviews.created_at',
                'jobseekers.name as jobseeker_name',
                // 'courses.title as course_title'
            )
            ->get();

        return view('site.mentor.reviews', compact('reviews'));
    }

    public function deleteMentorReview($id)
    {
        DB::table('reviews')
            ->where('id', $id)
            ->where('user_type', 'mentor')
            ->delete();

        return redirect()->route('mentor.reviews')->with('success', 'Mentor review deleted successfully.');
    }

    public function adminSupportMentor(){
        return view('site.mentor.admin-support-mentor'); 
    }
    public function settingsMentor(){
        $mentor = Auth::guard('mentor')->user();
        $mentorId = $mentor->id;

        // Mentor with training experience
        $mentorDetails = DB::table('mentors')
            ->leftJoin('training_experience', function ($join) {
                $join->on('training_experience.user_id', '=', 'mentors.id')
                    ->where('training_experience.user_type', 'mentor');
            })
            ->where('mentors.id', $mentorId)
            ->select(
                'mentors.*',
                'training_experience.training_experience',
                'training_experience.training_skills',
                'training_experience.website_link',
                'training_experience.portfolio_link'
            )
            ->first();

        $educationDetails = DB::table('education_details')
            ->where([
                ['user_id', '=', $mentorId],
                ['user_type', '=', 'mentor']
            ])
            ->get();

        $workExperiences = DB::table('work_experience')
            ->where([
                ['user_id', '=', $mentorId],
                ['user_type', '=', 'mentor']
            ])
            ->get();

        return view('site.mentor.settings-mentor', compact(
            'mentor',
            'mentorDetails',
            'educationDetails',
            'workExperiences'
        ));
    }

    public function getMentorAllDetails()
    {
        $mentor = Auth::guard('mentor')->user();
        $mentorId = $mentor->id;

        // Mentor with training experience
        $mentorDetails = DB::table('mentors')
            ->leftJoin('training_experience', function ($join) {
                $join->on('training_experience.user_id', '=', 'mentors.id')
                    ->where('training_experience.user_type', 'mentor');
            })
            ->where('mentors.id', $mentorId)
            ->select(
                'mentors.*',
                'training_experience.training_experience',
                'training_experience.training_skills',
                'training_experience.website_link',
                'training_experience.portfolio_link'
            )
            ->first();

        $educationDetails = DB::table('education_details')
            ->where([
                ['user_id', '=', $mentorId],
                ['user_type', '=', 'mentor']
            ])
            ->get();

        $workExperiences = DB::table('work_experience')
            ->where([
                ['user_id', '=', $mentorId],
                ['user_type', '=', 'mentor']
            ])
            ->get();

        return view('site.mentor.settings-mentor', compact(
            'mentor',
            'mentorDetails',
            'educationDetails',
            'workExperiences'
        ));
    }



    public function mentorProfileUpdate(Request $request)
    {
        $mentor = auth()->guard('mentor')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mentors,email,' . $mentor->id,
            'phone' => 'required|string|max:15',
            'dob' => 'nullable|date',
            'national_id' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'about_coach' => 'nullable|string',
        ]);

        $mentor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'],
            'date_of_birth' => $validated['dob'] ?? null,
            'national_id' => $validated['national_id'] ?? null,
            'city' => $validated['location'] ?? null,
            'about_coach' => $validated['about_coach'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully!'
        ]);
    }



    public function updateMentorEducationInfo(Request $request)
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
            ->where('user_type', 'mentor')
            ->pluck('id')
            ->toArray();

        $toDelete = array_diff($existingIds, $incomingIds);
        EducationDetails::whereIn('id', $toDelete)->delete();

        foreach ($request->input('high_education', []) as $i => $education) {
            $data = [
                'user_id' => $userId,
                'user_type' => 'mentor',
                'high_education' => $request->high_education[$i],
                'field_of_study' => $request->field_of_study[$i] ?? null,
                'institution' => $request->institution[$i] ?? null,
                'graduate_year' => $request->graduate_year[$i] ?? null,
            ];

            if (!empty($request->education_id[$i])) {
                EducationDetails::where('id', $request->education_id[$i])->update($data);
            } else {
                EducationDetails::create($data);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Education details saved successfully!']);
    }

    public function updateMentorWorkExperienceInfo(Request $request)
    {
        $user_id = auth()->guard('mentor')->id(); // Use mentor guard

        $validated = $request->validate([
            'job_role.*' => 'required|string|max:255',
            'organization.*' => 'required|string|max:255',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'nullable|date',
        ]);

        $incomingIds = $request->input('work_id', []);
        $existingIds = WorkExperience::where('user_id', $user_id)
            ->where('user_type', 'mentor')
            ->pluck('id')
            ->toArray();

        // Delete removed work experiences
        $toDelete = array_diff($existingIds, $incomingIds);
        WorkExperience::whereIn('id', $toDelete)->delete();

        $currentlyWorkingIndexes = $request->has('currently_working') ? array_keys($request->currently_working) : [];

        foreach ($request->job_role as $i => $role) {
            $isCurrent = in_array($i, $currentlyWorkingIndexes);
            $start = $request->starts_from[$i] ?? null;
            $end = $isCurrent ? 'Work here' : ($request->end_to[$i] ?? null);

            if (!$isCurrent && $start && $end && $end < $start) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ["end_to.$i" => ["End date must be after or equal to start date."]]
                ], 422);
            }

            $data = [
                'user_id' => $user_id,
                'user_type' => 'mentor',
                'job_role' => $role,
                'organization' => $request->organization[$i],
                'starts_from' => $start,
                'end_to' => $end,
            ];

            if (!empty($request->work_id[$i])) {
                WorkExperience::where('id', $request->work_id[$i])->update($data);
            } else {
                WorkExperience::create($data);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Work experience saved successfully!',
        ]);
    }




    public function updateMentorSkillsInfo(Request $request)
    {
        $user_id = auth()->id();

        $validated = $request->validate([
            'training_skills' => 'required|string',
            'website_link' => 'required|url',
            'portfolio_link' => 'required|url',
        ]);

        $skills = TrainingExperience::where('user_id', $user_id)
            ->where('user_type', 'mentor')
            ->first();

        if ($skills) {
            $skills->update($validated);
        } else {
            TrainingExperience::create([
                'user_id' => $user_id,
                'user_type' => 'mentor',
                'training_skills' => $validated['training_skills'],
                'website_link' => $validated['website_link'],
                'portfolio_link' => $validated['portfolio_link'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor skills updated successfully!',
        ]);
    }


    public function updateMentorAdditionalInfo(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'training_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $documentTypes = [
            'resume' => 'mentor_resume',
            'profile' => 'mentor_profile_picture',
            'training_certificate' => 'mentor_training_certificate'
        ];

        foreach ($documentTypes as $field => $docType) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = $docType . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $path = asset('uploads/' . $fileName);

                AdditionalInfo::updateOrCreate(
                    ['user_id' => $userId, 'user_type' => 'mentor', 'doc_type' => $docType],
                    ['document_path' => $path, 'document_name' => $fileName]
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor documents updated successfully!'
        ]);
    }


    public function deleteMentorDocument($type)
    {
        $userId = auth()->id();

        $documentTypes = [
            'resume' => 'mentor_resume',
            'profile' => 'mentor_profile_picture',
            'training_certificate' => 'mentor_training_certificate'
        ];

        if (!isset($documentTypes[$type])) {
            return response()->json(['message' => 'Invalid document type'], 400);
        }

        $deleted = AdditionalInfo::where([
            'user_id' => $userId,
            'user_type' => 'mentor',
            'doc_type' => $documentTypes[$type]
        ])->delete();

        return response()->json([
            'message' => $deleted ? 'File deleted successfully' : 'File not found'
        ]);
    }

     public function deleteAccount()
     {
          $mentorId = auth()->id();
          Mentors::where('id', $mentorId)->delete();
          auth()->logout();

          return redirect()->route('mentor.login')->with('success', 'Your account has been deleted successfully.');
     }


}
