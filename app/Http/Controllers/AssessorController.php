<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Coach;
use App\Models\Mentors;
use App\Models\Trainers;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\TrainingExperience;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\AdditionalInfo;
use App\Models\Review;
use App\Models\BookingSlotUnavailableDate;
use App\Models\BookingSession;
use App\Models\TrainingCategory;
use App\Models\BookingSlot;
use App\Models\Assessors;
use App\Models\Notification;
use Carbon\Carbon;
use DB;
use Auth;
use App\Models\SubscriptionPlan;
use App\Models\PurchasedSubscription;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AssessorController extends Controller
{
    public function showSignInForm(){
        return view('site.assessor.sign-in'); 
    }
    public function showSignUpForm()
    {
    return view('site.assessor.sign-up');
    }
    public function showRegistrationForm()
    {
    $categories = TrainingCategory::all();
    return view('site.assessor.registration', compact('categories'));
    }
    public function showForgotPasswordForm()
    {
        return view('site.assessor.forget-password');
    }
    public function showOtpForm()
    {
        return view('site.assessor.verify-otp'); 
    }
    public function showResetPasswordForm()
    {
        return view('site.assessor.reset-password'); 
    }

    public function postRegistration(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:assessors,email',
            'phone_number' => 'required|unique:assessors,phone_number',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);

        $assessors = Assessors::create([
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'pass' => $request->password,
        ]);

        session([
            'assessor_id' => $assessors->id,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('assessor.registration');
    }


    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'contact' => ['required', function ($attribute, $value, $fail) {
                $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                $column = $isEmail ? 'email' : 'phone_number';

                $exists = DB::table('assessors')->where($column, $value)->exists();

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
        DB::table('assessors')->where($contactMethod, $contact)->update([
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
        return redirect()->route('assessor.verify-otp')->with('success', 'OTP sent!');
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
        DB::table('assessors')->where($contactMethod, $contact)->update([
            'otp' => $otp,
            'updated_at' => now()
        ]);

        // === OTP sending is disabled for now ===
        if ($contactMethod === 'email') {
            // Mail::html(view('emails.otp', compact('otp'))->render(), function ($message) use ($contact) {
            //     $message->to($contact)->subject('Your OTP has been resent – Talentrek');
            // });
        } else {
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

        $assessors = DB::table('assessors')
            ->where($column, $contact)
            ->where('otp', $request->otp)
            ->first();

        if (!$assessors) {
            return back()
                ->withErrors(['otp' => 'Invalid OTP or contact. Please enter the correct 6-digit OTP.'])
                ->withInput();
        }

        // Save verified user ID in session
        session(['verified_recruiter' => $assessors->id]);

        return redirect()->route('assessor.reset-password');
    }

    public function resetPassword(Request $request){
       $request->validate([
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
        $assessorId = session('verified_recruiter');
       
        if (!$assessorId) {
            return redirect()->route('assessor.forget.password')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $updated = DB::table('assessors')->where('id', $assessorId)->update([
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

        return redirect()->route('assessor.login')->with('success', 'Password change successfully.');
    } 

    

    public function loginAssessor(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $assessor = Assessors::where('email', $request->email)->first();

        if (!$assessor) {
            // Email does not exist
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }

        if ($assessor->status !== 'active') {
            // Status is inactive or blocked
            session()->flash('error', 'Your account is inactive. Please contact administrator.');
            return back()->withInput($request->only('email'));
        }

        // ✅ Check admin_status
        if ($assessor->admin_status === 'superadmin_reject' || $assessor->admin_status === 'rejected') {
            session()->flash('error', 'Your account has been rejected by administrator.');
            return back()->withInput($request->only('email'));
        }

        if ($assessor->admin_status !== 'superadmin_approved') {
            session()->flash('error', 'Your account is not yet approved by administrator.');
            return back()->withInput($request->only('email'));
        }

        // ✅ Now attempt login only if status is active and admin_status is approved
        if (Auth::guard('assessor')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('assessor.dashboard');
        } else {
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }
    }


    public function showAssessorDashboard()
    {
       $sessions = BookingSession::select(
            'jobseeker_saved_booking_session.*',
            'jobseekers.name',
            'additional_info.document_path as img',
            'jobseeker_saved_booking_session.id as session_id'
            )
            ->where('jobseeker_saved_booking_session.user_id', auth()->id())
            ->where('jobseeker_saved_booking_session.user_type', 'assessor')
            ->join('jobseekers', 'jobseekers.id', '=', 'jobseeker_saved_booking_session.jobseeker_id')
            ->leftJoin('additional_info', function ($join) {
                $join->on('additional_info.user_id', '=', 'jobseekers.id')
                    ->where('additional_info.user_type', 'jobseeker')
                    ->where('additional_info.doc_type', 'profile_picture');
            })
            ->orderBy('jobseeker_saved_booking_session.slot_date', 'asc')
            ->get()
            ->map(function ($session) {
                $latestExperience = $session->jobseeker->experiences()
                    ->orderBy('end_to', 'desc')
                    ->orderBy('starts_from', 'desc')
                    ->first();
                
                return [
                    'session_id' => $session->session_id,
                    'name' => $session->name,
                    'role' => $latestExperience->job_role ?? 'N/A', 
                    'date' => \Carbon\Carbon::parse($session->slot_date)->format('d/m/Y'),
                    'time' => $session->slot_time,
                    'mode' => ucfirst($session->slot_mode),
                    'img' => $session->img,
                    'feedback' => $session->feedback ?? null,
                    'cancellation_reason' => $session->cancellation_reason ?? null,
                    'status' => $session->status,
                ];
            })
            ->groupBy('status');

        // echo "<pre>";    
        // print_r( $sessions);exit; 
       
        $today = \Carbon\Carbon::today()->format('Y-m-d');
   
        $todayCount = BookingSession::where('jobseeker_saved_booking_session.user_id', auth()->id())
            ->where('jobseeker_saved_booking_session.user_type', 'assessor')
            ->whereDate('jobseeker_saved_booking_session.slot_date', $today)
            ->count();
           
        $upcomingCount = BookingSession::where('jobseeker_saved_booking_session.user_id', auth()->id())
            ->where('jobseeker_saved_booking_session.user_type', 'assessor')
            ->where('jobseeker_saved_booking_session.status', 'pending')
            ->count();
            
        // ✅ Properly formatted cancelled sessions for modal use
        $cancelled = BookingSession::select(
            'jobseeker_saved_booking_session.*',
            'jobseekers.name',
            'additional_info.document_path as img',
            'jobseeker_saved_booking_session.id as session_id'
            )
            ->where('jobseeker_saved_booking_session.user_id', auth()->id())
            ->where('jobseeker_saved_booking_session.user_type', 'assessor')
            ->where('jobseeker_saved_booking_session.status', 'cancelled')
            ->join('jobseekers', 'jobseekers.id', '=', 'jobseeker_saved_booking_session.jobseeker_id')
            ->leftJoin('additional_info', function ($join) {
                $join->on('additional_info.user_id', '=', 'jobseekers.id')
                    ->where('additional_info.user_type', 'jobseeker')
                    ->where('additional_info.doc_type', 'profile_picture');
            })
            ->orderBy('jobseeker_saved_booking_session.slot_date', 'asc')
            ->get()
            ->map(function ($session) {
                $latestExperience = $session->jobseeker->experiences()
                    ->orderBy('end_to', 'desc')
                    ->orderBy('starts_from', 'desc')
                    ->first();
                
                return [
                    'session_id' => $session->session_id,
                    'name' => $session->name,
                    'role' => $latestExperience->job_role ?? 'N/A',
                    'date' => \Carbon\Carbon::parse($session->slot_date)->format('d/m/Y'),
                    'time' => $session->slot_time,
                    'mode' => ucfirst($session->slot_mode),
                    'img' => $session->img,
                    'feedback' => $session->feedback ?? null,
                    'cancellation_reason' => $session->cancellation_reason ?? null,
                    'status' => $session->status,
                ];
            });
        // echo "<pre>";    
        // print_r($cancelled);exit; 

        return view('site.assessor.assessor-dashboard', [
            'upcoming' => $sessions['pending'] ?? [],
            'completed' => $sessions['completed'] ?? [],
            'cancelled' => $cancelled,
            'todayCount' => $todayCount,
            'upcomingCount' => $upcomingCount,
        ]);   
    }

    public function logoutAssessor(Request $request)
    {
        Auth::guard('assessor')->logout();
        
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect()->route('assessor.login')->with('success', 'Logged out successfully');
    }

    public function storeAssessorInformation(Request $request)
    {
        $assessorId = session('assessor_id');

        if (!$assessorId) {
            return redirect()->route('assessor.signup')->with('error', 'Session expired. Please sign up again.');
        }

        $assessor = Assessors::find($assessorId);

        if (!$assessor) {
            return redirect()->route('assessor.signup')->with('error', 'Trainer not found.');
        }

        $validated = $request->validate([
            'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
            'email' => 'required|email|unique:assessors,email,' . $assessor->id,
            'phone_number' => 'required|unique:assessors,phone_number,' . $assessor->id,
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
                function ($attribute, $value, $fail) use ($assessor) {
                    $existsInAssessor = Assessors::where('national_id', $value)
                        ->where('id', '!=', $assessor->id)
                        ->exists();

                    if ( $existsInAssessor) {
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
            'area_of_interest' => 'required|string',
            'job_category' => 'required|string',
            'website_link' => 'nullable|url',
            'portfolio_link' => 'nullable|url',

            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'training_certificate' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            // ✅ Custom messages
            'name.required' => 'Please enter your full name.',
            'name.regex' => 'The name should contain only letters and single spaces.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'phone_number.required' => 'Please enter your phone number.',
            'phone_number.unique' => 'This phone number is already taken.',
            'dob.required' => 'Please enter your date of birth.',
            'city.required' => 'Please enter your city.',
            'state.required' => 'Please enter your state.',
            'national_id.required' => 'Please enter your national ID.',
            'national_id.min' => 'National ID must be at least 10 characters.',

            'high_education.*.required' => 'Please enter your highest education.',
            'institution.*.required' => 'Please enter the institution name.',
            'graduate_year.*.required' => 'Please enter your graduation year.',
            'job_role.*.required' => 'Please enter your job role.',
            'organization.*.required' => 'Please enter the organization name.',
            'starts_from.*.required' => 'Please enter the start date.',
            'end_to.*.required' => 'Please enter the end date.',

            'training_skills.required' => 'Please enter your training skills.',
            'area_of_interest.required' => 'Please enter your area of interest.',
            'job_category.required' => 'Please select your job category.',
            'website_link.required' => 'Please enter your website link.',
            'website_link.url' => 'Please enter a valid website URL.',
            'portfolio_link.required' => 'Please enter your portfolio link.',
            'portfolio_link.url' => 'Please enter a valid portfolio URL.',

            'resume.required' => 'Please upload your resume.',
            'resume.mimes' => 'Resume must be a file of type: pdf, doc, docx.',
            'profile_picture.required' => 'Please upload your profile picture.',
            'profile_picture.image' => 'Profile picture must be an image.',
            'training_certificate.required' => 'Please upload your training certificate.',
            'training_certificate.mimes' => 'Training certificate must be a file of type: pdf, doc, docx.',
        ]);


        DB::beginTransaction();

        // Update assessor profile
        $assessor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'phone_code' => $validated['phone_code'],
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
                'user_id' => $assessor->id,
                'user_type' => 'assessor',
                'high_education' => $education,
                'field_of_study' => $request->field_of_study[$index] ?? null,
                'institution' => $request->institution[$index],
                'graduate_year' => $request->graduate_year[$index],
            ]);
        }

   
        // Save work experiences
        if ($request->has('job_role')) {
            foreach ($request->job_role as $index => $role) {
                $isCurrentlyWorking = $request->input("currently_working.$index") === 'on';

                $startDate = $request->starts_from[$index] ?? null;
                $endDate = $isCurrentlyWorking 
                    ? 'work here'
                    : ($request->end_to[$index] ?? null);

                WorkExperience::create([
                    'user_id'       => $assessor->id,
                    'user_type'     => 'assessor',
                    'job_role'      => $role,
                    'organization'  => $request->organization[$index] ?? null,
                    'starts_from'   => $startDate,
                    'end_to'        => $endDate,
                ]);
            }
        }

        // Save training experience
        TrainingExperience::create([
            'user_id' => $assessor->id,
            'user_type' => 'assessor',
            'training_skills' => $request->training_skills,
            'area_of_interest' => $request->area_of_interest,
            'job_category' => $request->job_category,
            'website_link' => $request->website_link,
            'portfolio_link' => $request->portfolio_link,
        ]);

        // File uploads
        $uploadTypes = [
            'resume' => 'assessor_resume',
            'profile_picture' => 'assessor_profile_picture',
            'training_certificate' => 'assessor_training_certificate',
        ];

        foreach ($uploadTypes as $field => $docType) {
            if ($request->hasFile($field)) {
                $existing = AdditionalInfo::where([
                    ['user_id', $assessor->id],
                    ['user_type', 'assessor'],
                    ['doc_type', $docType],
                ])->first();

                if (!$existing) {
                    $originalName = $request->file($field)->getClientOriginalName();
                    $extension = $request->file($field)->getClientOriginalExtension();
                    $filename = $docType . '_' . time() . '.' . $extension;
                    $request->file($field)->move('uploads/', $filename);

                    AdditionalInfo::create([
                        'user_id' => $assessor->id,
                        'user_type' => 'assessor',
                        'doc_type' => $docType,
                        'document_name' => $originalName,
                        'document_path' => asset('uploads/' . $filename),
                    ]);
                }
            }
        }

        DB::commit();

        $data = [
            'sender_id' => $assessor->id,
            'sender_type' => 'Registration by Assessor.',
            'receiver_id' => '1',
            'message' => 'Welcome to Talentrek – Registration Successful by '.$assessor->name,
            'is_read' => 0,
            'is_read_admin' => 0,
            'user_type' => 'assessor'
        ];

        Notification::insert($data);
        session()->forget('assessor_id');
        return redirect()->route('assessor.login')->with('success_popup', true);
    }

    public function showSettingsAssessor(){
        $assessor = Auth::guard('assessor')->user();
        $assessorId = $assessor->id;

        // assessor with training experience
        $assessorDetails = DB::table('assessors')
            ->leftJoin('training_experience', function ($join) {
                $join->on('training_experience.user_id', '=', 'assessors.id')
                    ->where('training_experience.user_type', 'assessor');
            })
            ->where('assessors.id', $assessorId)
            ->select(
                'assessors.*',
                'training_experience.training_experience',
                'training_experience.training_skills',
                'training_experience.area_of_interest',
                'training_experience.job_category',
                'training_experience.website_link',
                'training_experience.portfolio_link'
            )
            ->first();
        // dd( $assessorDetails);exit;        
        $educationDetails = DB::table('education_details')
            ->where([
                ['user_id', '=', $assessorId],
                ['user_type', '=', 'assessor']
            ])
            ->get();

        $workExperiences = DB::table('work_experience')
            ->where([
                ['user_id', '=', $assessorId],
                ['user_type', '=', 'assessor']
            ])
            ->get();

        $categories = TrainingCategory::all();    

        return view('site.assessor.settings-assesor', compact(
            'assessor',
            'assessorDetails',
            'educationDetails',
            'workExperiences',
            'categories'
        ));
        
    }

    public function assessorProfileUpdate(Request $request)
    {
        $assessor = auth()->guard('assessor')->user();

        $validated = $request->validate([
            'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
            'email' => 'required|email|unique:assessors,email,' . $assessor->id,
            'phone' => 'required|digits:9',
            'dob' => 'required|date',
            'national_id' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'pin_code' => 'required|digits:5',
            'about_assessor' => 'nullable|string',
            'per_slot_price' => 'required',
            
        ]);

        $assessor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'],
            'date_of_birth' => $validated['dob'] ?? null,
            'national_id' => $validated['national_id'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'] ?? null,
            'pin_code' => $validated['pin_code'] ?? null,
            'about_assessor' => $validated['about_assessor'] ?? null,
            'per_slot_price' => $validated['per_slot_price'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully!'
        ]);
    }



    public function updateAssessorEducationInfo(Request $request)
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
            ->where('user_type', 'assessor')
            ->pluck('id')
            ->toArray();

        $toDelete = array_diff($existingIds, $incomingIds);
        EducationDetails::whereIn('id', $toDelete)->delete();

        foreach ($request->input('high_education', []) as $i => $education) {
            $data = [
                'user_id' => $userId,
                'user_type' => 'assessor',
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

        return response()->json(['status' => 'success', 'message' => 'Education details updated successfully!']);
    }

    public function updateAssessorWorkExperienceInfo(Request $request)
    {
        $user_id = auth()->guard('assessor')->id(); // Use assessor guard

        $validated = $request->validate([
            'job_role.*' => 'required|string|max:255',
            'organization.*' => 'required|string|max:255',
            'starts_from.*' => 'required|date',
            'end_to.*' => 'nullable|date',
        ]);

        $incomingIds = $request->input('work_id', []);
        $existingIds = WorkExperience::where('user_id', $user_id)
            ->where('user_type', 'assessor')
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
                'user_type' => 'assessor',
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




    public function updateAssessorSkillsInfo(Request $request)
    {
        $user_id = auth()->id();

        $validated = $request->validate([
            'training_skills' => 'required|string',
            'area_of_interest' => 'required|string',
            'job_category' => 'required|string',
            'website_link' => 'required|url',
            'portfolio_link' => 'required|url',
        ]);

        $skills = TrainingExperience::where('user_id', $user_id)
            ->where('user_type', 'assessor')
            ->first();

        if ($skills) {
            $skills->update($validated);
        } else {
            TrainingExperience::create([
                'user_id' => $user_id,
                'user_type' => 'assessor',
                'training_skills' => $validated['training_skills'],
                'area_of_interest' => $validated['area_of_interest'],
                'job_category' => $validated['job_category'],
                'website_link' => $validated['website_link'],
                'portfolio_link' => $validated['portfolio_link'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Assessor skills updated successfully!',
        ]);
    }


    public function updateAssessorAdditionalInfo(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'training_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $documentTypes = [
            'resume' => 'assessor_resume',
            'profile' => 'assessor_profile_picture',
            'training_certificate' => 'assessor_training_certificate'
        ];

        foreach ($documentTypes as $field => $docType) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = $docType . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $path = asset('uploads/' . $fileName);

                AdditionalInfo::updateOrCreate(
                    ['user_id' => $userId, 'user_type' => 'assessor', 'doc_type' => $docType],
                    ['document_path' => $path, 'document_name' => $fileName]
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Assessor documents updated successfully!'
        ]);
    }


    public function deleteAssessorDocument($type)
    {
        $userId = auth()->id();

        $documentTypes = [
            'resume' => 'assessor_resume',
            'profile' => 'assessor_profile_picture',
            'training_certificate' => 'assessor_training_certificate'
        ];

        if (!isset($documentTypes[$type])) {
            return response()->json(['message' => 'Invalid document type'], 400);
        }

        $deleted = AdditionalInfo::where([
            'user_id' => $userId,
            'user_type' => 'assessor',
            'doc_type' => $documentTypes[$type]
        ])->delete();

        return response()->json([
            'message' => $deleted ? 'File deleted successfully' : 'File not found'
        ]);
    }

     public function deleteAccount()
     {
          $assessorId = auth()->id();
          Assessors::where('id', $assessorId)->delete();
          auth()->logout();

          return redirect()->route('assessor.login')->with('success', 'Your account has been deleted successfully.');
     }

     public function assessorReviews()
    {
        $reviews = DB::table('reviews')
            ->leftJoin('jobseekers', 'jobseekers.id', '=', 'reviews.jobseeker_id')
            // ->leftJoin('courses', 'courses.id', '=', 'reviews.course_id')
            ->where('reviews.user_type', 'assessor')
            ->select(
                'reviews.id',
                'reviews.reviews',
                'reviews.ratings',
                'reviews.created_at',
                'jobseekers.name as jobseeker_name',
                // 'courses.title as course_title'
            )
            ->get();

        return view('site.assessor.reviews', compact('reviews'));
    }

    public function deleteAssessorReview($id)
    {
        DB::table('reviews')
            ->where('id', $id)
            ->where('user_type', 'assessor')
            ->delete();

        return redirect()->route('assessor.reviews')->with('success', 'Assessor review deleted successfully.');
    }

    // Controller method for initial view
    public function manageBooking()
    {
        $assessor = auth()->user();

        // Load all slots with their unavailable dates
        $allSlots = BookingSlot::with('unavailableDates')
            ->where('user_type', 'assessor')
            ->where('user_id', $assessor->id)
            ->get();

        $onlineSlots = $allSlots->where('slot_mode', 'online')->values();
        $offlineSlots = $allSlots->where('slot_mode', 'offline')->values();

        $unavailableDatesMap = [];
        foreach ($allSlots as $slot) {
            foreach ($slot->unavailableDates as $date) {
                $unavailableDatesMap[$slot->id][] = $date->unavailable_date;
            }
        }

        return view('site.assessor.manage-booking', [
            'onlineSlots' => $onlineSlots,
            'offlineSlots' => $offlineSlots,
            'unavailableDatesMap' => $unavailableDatesMap,
        ]);
    }

    public function createBooking(){
        return view('site.assessor.create-booking'); 
    }

    public function submitBooking(Request $request)
    {
        $request->validate([
            'slots' => 'required|array|min:1',
            'slots.*' => 'string',
        ]);

        foreach ($request->slots as $slot) {
            // Example slot: "online | 01:00 am - 02:00 am"
            [$mode, $range] = explode(' | ', $slot);
            [$start, $end] = explode(' - ', $range);

            BookingSlot::create([
                'user_type' => 'assessor',
                'user_id' => auth()->id(),
                'slot_mode' => $mode,
                'start_time' => Carbon::createFromFormat('h:i a', $start)->format('H:i:s'),
                'end_time' => Carbon::createFromFormat('h:i a', $end)->format('H:i:s'),
                'is_available' => true,
            ]);
        }

        return redirect()->route('assessor.manage-bookings')->with('success', 'Booking slots saved successfully.');
    }

    public function updateStatus(Request $request)
    {
        $date = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d');
        $slot = BookingSlot::find($request->slot_id);

        if (!$slot) {
            return response()->json(['status' => 'error', 'message' => 'Slot not found.'], 404);
        }

        // Update is_available field in BookingSlot (optional: you can remove this if availability is fully date-based)
        $slot->is_available = $request->is_available;
        $slot->save();

        if ($request->is_available == 0) {
            // Mark this specific date as unavailable
            BookingSlotUnavailableDate::firstOrCreate([
                'booking_slot_id' => $slot->id,
                'unavailable_date' => $date,
            ]);
        } else {
            // Remove unavailable entry for that date
            BookingSlotUnavailableDate::where('booking_slot_id', $slot->id)
                ->where('unavailable_date', $date)
                ->delete();
        }

        return response()->json(['status' => 'success', 'message' => 'Slot status updated.']);
    }

    // public function updateSlotTime(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:booking_slots,id',
    //         'start_time' => 'required',
    //         'end_time' => 'required|after:start_time',
    //     ]);

    //     // Convert to 24-hour format
    //     $startTime = Carbon::createFromFormat('h:i a', $request->start_time)->format('H:i:s');
    //     $endTime = Carbon::createFromFormat('h:i a', $request->end_time)->format('H:i:s');

    //     $slot = BookingSlot::findOrFail($request->id);
        
    //     $slot->update([
    //         'start_time' => $startTime,
    //         'end_time' => $endTime,
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Slot time updated successfully.',
    //         'slot' => $slot,
    //     ]);
    // }
    public function updateSlotTime(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:booking_slots,id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $startTime = Carbon::createFromFormat('h:i a', $request->start_time)->format('H:i:s');
        $endTime   = Carbon::createFromFormat('h:i a', $request->end_time)->format('H:i:s');

        if ($startTime === $endTime) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Start time and end time cannot be the same.',
            ], 422);
        }

        $userId   = auth()->id();
        $userType = 'assessor';

        $slot = BookingSlot::where('id', $request->id)
            ->where('user_id', $userId)
            ->where('user_type', $userType)
            ->firstOrFail();

    
        $exists = BookingSlot::where('id', '!=', $slot->id)
            ->where('user_type', $userType)
            ->where('user_id', $userId)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q1) use ($startTime, $endTime) {
                    $q1->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
                });
            })
            ->exists();


        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This time slot is already selected.',
            ], 422);
        }

    
        $slot->update([
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ]);
      
        return response()->json([
            'status'  => 'success',
            'message' => 'slot updated successfully.',
            'slot'    => $slot,
        ]);
    }
  


    
    public function deleteSlot(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:booking_slots,id',
        ]);

        $slot = BookingSlot::findOrFail($request->id);
        
        // Optional: Ensure current assessor owns this slot
        if ($slot->user_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        // Delete the slot and its related unavailable dates
        $slot->unavailableDates()->delete(); // If using relation
        $slot->delete();

        return response()->json(['status' => 'success']);
    }

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
            $assessor = auth('assessor')->user();

            // Create the new subscription
            $newSubscription = PurchasedSubscription::create([
                'user_id' => $assessor->id,
                'user_type' => 'assessor',
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

            if (!$assessor->active_subscription_plan_id) {
                $shouldUpdate = true;
            } else {
                $currentActive = PurchasedSubscription::find($assessor->active_subscription_plan_id);
                if (!$currentActive || $newSubscription->end_date->gt($currentActive->end_date)) {
                    $shouldUpdate = true;
                }
            }

            if ($shouldUpdate) {
                $assessor->isSubscribtionBuy = 'yes';
                $assessor->active_subscription_plan_id = $newSubscription->id;
                $assessor->save();
            }

            DB::commit();

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

    public function chatWithJobseekerAssessor(){
        return view('site.assessor.chat-jobseeker-assessor'); 
    }

    public function adminSupportAssessor(){
        return view('site.assessor.admin-support-assessor'); 
    }

     public function getUnreadCount(Request $request)
    {
        $assessorId = auth()->guard('assessor')->id();

        $query = DB::table('admin_group_chats')
            ->where('receiver_id', $assessorId)
            ->where('receiver_type', 'assessor')
            ->where('is_read', 0);

        // Agar admin-support page par hai to mark all as read
        if ($request->query('mark_read') == 1) {
            $query->update(['is_read' => 1]);
            $count = 0;
        } else {
            $count = $query->count();
        }

        return response()->json(['count' => $count]);
    }


    public function markMessagesRead()
    {
        $assessorId = auth()->guard('assessor')->id();

        DB::table('admin_group_chats')
            ->where('receiver_id', $assessorId)
            ->where('receiver_type', 'assessor')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    public function markMessagesSeen()
    {
        $assessorId = auth()->guard('assessor')->id();

        DB::table('admin_group_chats')
            ->where('receiver_id', $assessorId)
            ->where('receiver_type', 'assessor')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        // Realtime broadcast
        event(new \App\Events\MessageSeen($assessorId, 'assessor', 'admin', 'admin'));

        return response()->json(['success' => true]);
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check user
            $assessor = Assessors::where('email', $googleUser->getEmail())->first();

            // Case 1: New Google user (email not exist)
            if (!$assessor) {
                $plainPassword = Str::random(16);

                $assessor = Assessors::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'status'            => 'active',
                    'password'          => bcrypt($plainPassword),
                    'pass'              => $plainPassword,
                    'email_verified_at' => now(),
                    'is_registered'     => 0, //  not registered yet
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                ]);

                // Store ID + email in session
                session([
                    'assessor_id'    => $assessor->id,
                    'email' => $assessor->email,
                ]);

                //  Send to registration form
                return redirect()->route('assessor.registration');
            }

            // Agar inactive account hai
            if ($assessor->status !== 'active') {
                session()->flash('error', 'Your account is inactive. Please contact administrator.');
                return redirect()->route('assessor.login');
            }

            // Case 2: Existing user with complete registration
            if ($assessor->is_registered == 1) {
                // ✅ Direct login and go to profile/dashboard
                Auth::guard('assessor')->login($assessor);
                return redirect()->route('assessor.dashboard');
            }

            // Case 3: Existing but registration incomplete
            session([
                'assessor_id'    => $assessor->id,
                'email' => $assessor->email,
            ]);
            return redirect()->route('assessor.registration');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            session()->flash('error', 'Invalid state. Please try again.');
        } catch (\Exception $e) {
            session()->flash('error', 'Google login failed. Please try again.');
        }

        return redirect()->route('assessor.login');
    }

    
}

