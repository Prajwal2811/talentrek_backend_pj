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
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use App\Models\BookingSlot;
use Carbon\Carbon;


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

    public function aboutCoach(){
        return view('site.mentor.about-mentor'); 
    }
    
   // Controller method for initial view
    public function manageBooking()
    {
        $mentor = auth()->user();

        $allSlots = BookingSlot::where('user_type', 'mentor')
            ->where('user_id', $mentor->id)
            ->get();

        $onlineSlots = $allSlots->where('slot_type', 'online');
        $offlineSlots = $allSlots->where('slot_type', 'offline');

        $unavailableDates = $allSlots->pluck('unavailable_dates')
            ->filter()
            ->flatMap(function ($dates) {
                return is_string($dates) ? json_decode($dates, true) ?? [] : (array)$dates;
            })
            ->countBy()
            ->filter(fn($count) => $count === $allSlots->count())
            ->keys()
            ->values();

        return view('site.mentor.manage-booking', [
            'bookingSlots'     => $allSlots,
            'onlineSlots'      => $onlineSlots,
            'offlineSlots'     => $offlineSlots,
            'unavailableDates' => $unavailableDates,
        ]);
    }



    public function createBooking(){
        return view('site.mentor.create-booking'); 
    }

    public function submitBooking(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:online,offline',
            'slots' => 'required|array|min:1',
            'slots.*' => 'string'
        ]);

        foreach ($request->slots as $slot) {
            [$start, $end] = explode(' - ', $slot);

            BookingSlot::create([
                'user_type' => 'mentor',
                'user_id' => auth()->id(),
                'slot_mode' => $request->mode,
                'start_time' => Carbon::createFromFormat('h:i a', $start)->format('H:i:s'),
                'end_time' => Carbon::createFromFormat('h:i a', $end)->format('H:i:s'),
                'unavailable_dates' => NULL,
            ]);
        }

        return redirect()->route('mentor.manage-bookings')->with('success', 'Booking slots saved successfully.');
    }




    public function chatWithJobseekerMentor(){
        return view('site.mentor.chat-jobseeker-mentor'); 
    }
    public function mentorReviews(){
        return view('site.mentor.reviews'); 
    }
    public function adminSupportMentor(){
        return view('site.mentor.admin-support-mentor'); 
    }
    public function settingsMentor(){
        return view('site.mentor.settings-mentor'); 
    }
}
