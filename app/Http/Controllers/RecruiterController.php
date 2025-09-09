<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recruiters;
use App\Models\Jobseekers;
use App\Models\Trainers;
use App\Models\Skills;
use App\Models\Feedback;
use App\Models\AdditionalInfo;
use App\Models\RecruiterCompany;
use App\Models\RecruiterJobseekersShortlist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\RegistrationSuccess;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\SubscriptionPlan;
use App\Models\PurchasedSubscription;
class RecruiterController extends Controller
{
     public function showSignInForm(){
          return view('site.recruiter.sign-in'); 
     }
     public function showSignUpForm()
     {
          return view('site.recruiter.sign-up');
     }
     public function showRegistrationForm()
     {
          return view('site.recruiter.registration');
     }


     public function showRecruiterDashboard()
     {
     $recruiterId = auth()->guard('recruiter')->id();

     $scheduled_jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
          ->join('recruiter_jobseeker_shortlist as shortlist', 'jobseekers.id', '=', 'shortlist.jobseeker_id')
          ->where('shortlist.recruiter_id', $recruiterId)
          ->where('jobseekers.status', 'active')
          ->where(function ($query) {
               $query->where('shortlist.interview_status', 'scheduled')
                    ->orWhere('shortlist.interview_status', 'cancelled')
                    ->orWhere('shortlist.interview_status', 'completed');
          })
          ->select(
               'jobseekers.*',
               'shortlist.admin_status as shortlist_admin_status',
               'shortlist.interview_request',
               'shortlist.jobseeker_id as jobseeker_id',
               'shortlist.*'
          )
          ->orderBy('shortlist.created_at', 'desc')
          ->get();

     // ✅ Total shortlisted jobseekers
     $totalShortlisted = DB::table('recruiter_jobseeker_shortlist')
          ->where('recruiter_id', $recruiterId)
          ->count();

     // ✅ Total interviews scheduled
     $totalScheduled = DB::table('recruiter_jobseeker_shortlist')
          ->where('recruiter_id', $recruiterId)
          ->where('interview_status', 'scheduled')
          ->count();

     return view('site.recruiter.dashboard', compact(
          'scheduled_jobseekers',
          'totalShortlisted',
          'totalScheduled'
     ));
     }




     public function showForgotPasswordForm()
     {
          return view('site.recruiter.forget-password');
     }
     public function showOtpForm(){
        return view('site.recruiter.verify-otp'); 
     }

     public function showResetPasswordForm()
     {
          return view('site.recruiter.reset-password'); 
     }
     public function showAdminSupportForm()
     {
          return view('site.recruiter.admin-support'); 
     }
     public function postRegistration(Request $request)
     {
          $validated = $request->validate([
               'email' => 'required|email|unique:recruiters,email',
               'phone_number' => 'required|unique:recruiters,phone_number',
               'password' => 'required|min:6|same:confirm_password',
               'confirm_password' => 'required|min:6',
          ]);
          

          $recruiters = Recruiters::create([

               'company_id' => NULL,
               'name' => $request->name,
               'email' => $request->email,
               'phone_number' => $request->phone_number,
               'password' => Hash::make($request->password),
               'pass' => $request->password,
          ]);

          session([
               'recruiter_id' => $recruiters->id,
               'email' => $request->email,
               'phone_number' => $request->phone_number,
          ]);

          return redirect()->route('recruiter.registration');
     }

    
     // public function storeRecruiterInformation(Request $request)
     // {
     //      $validated = $request->validate([
     //           'recruiter_id' => 'required|exists:recruiters,id',
     //           'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
     //           'national_id' => [
     //                'required',
     //                'min:10',
     //                function ($attribute, $value, $fail) {
     //                     $existsInRecruiters = Recruiters::where('national_id', $value)->exists();
                       
     //                     if ($existsInRecruiters) {
     //                          $fail('The national ID has already been taken in another account.');
     //                     }
     //                },
     //           ],
     //           'company_name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
     //           'company_website' => 'required|url',
     //           'company_city' => 'required|string|max:255',
     //           'company_address' => 'required|string|max:500',
     //           'business_email' => 'required|email|unique:recruiters_company,business_email,' . $request->company_id,
     //           'phone_code' => 'required|string',
     //           'company_phone_number' => 'required|unique:recruiters_company,company_phone_number,' . $request->company_id,
     //           'no_of_employee' => 'required|string|max:255',
     //           'industry_type' => 'required|string|max:255',
     //           'registration_number' => 'required|string|max:255',
     //           'company_profile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
     //           // 'registration_documents' => 'required|array',
     //           'registration_documents.*' => 'file|mimes:pdf,doc,docx,jpeg,jpg,png|max:2048',
     //           ], [
     //           'company_id.required' => 'Company ID is required.',
     //           'company_id.exists' => 'Selected company does not exist.',
     //           'name.required' => 'Name is required.',
     //           'name.regex' => 'The full name should contain only letters and single spaces.',
     //           'email.required' => 'Email is required.',
     //           'email.email' => 'Enter a valid email address.',
     //           'email.unique' => 'This email is already in use.',
     //           'national_id.required' => 'National ID is required.',
     //           'national_id.min' => 'National ID must be at least 10 characters.',
     //           'company_name.required' => 'Company name is required.',
     //           'company_name.regex' => 'The company name should contain only letters and single spaces.',
     //           'company_website.required' => 'Company website is required.',
     //           'company_website.url' => 'Enter a valid URL.',
     //           'company_city.required' => 'Company city is required.',
     //           'company_address.required' => 'Company address is required.',
     //           'business_email.required' => 'Business email is required.',
     //           'business_email.email' => 'Enter a valid business email address.',
     //           'business_email.unique' => 'This business email is already used by another company.',
     //           'phone_code.required' => 'Phone code is required.',
     //           'company_phone_number.required' => 'Company phone number is required.',
     //           'company_phone_number.unique' => 'This phone number is already used.',
     //           'no_of_employee.required' => 'Number of employees is required.',
     //           'industry_type.required' => 'Industry type is required.',
     //           'registration_number.required' => 'Registration number is required.',
     //           'company_profile.required' => 'Company profile image is required.',
     //           'company_profile.image' => 'Company profile must be an image.',
     //           'company_profile.mimes' => 'Only jpg, jpeg, and png files are allowed for company profile.',
     //           'company_profile.max' => 'Company profile image must not exceed 2MB.',
     //           'registration_documents.required' => 'At least one registration document is required.',
     //           'registration_documents.*.mimes' => 'Only pdf, doc, docx, jpg, jpeg, and png files are allowed.',
     //           'registration_documents.*.max' => 'Each document must not exceed 2MB.',
     //           ]);


     //      DB::beginTransaction();

     //      try {
     //           // Step 1: Update company
     //           $company = RecruiterCompany::find($validated['company_id']);
     //           $company->update([
     //                'company_name' => $validated['company_name'],
     //                'company_website' => $validated['company_website'],
     //                'company_city' => $validated['company_city'],
     //                'company_address' => $validated['company_address'],
     //                'business_email' => $validated['business_email'],
     //                'phone_code' => $validated['phone_code'],
     //                'company_phone_number' => $validated['company_phone_number'],
     //                'no_of_employee' => $validated['no_of_employee'],
     //                'industry_type' => $validated['industry_type'],
     //                'registration_number' => $validated['registration_number'],
     //                'is_registered' => 1
     //           ]);

     //           // Step 2: Create recruiter
     //           $recruiter = Recruiters::create([
     //                'name' => $validated['name'],
     //                'email' => $validated['email'],
     //                'national_id' => $validated['national_id'],
     //                'company_id' => $validated['company_id'],
     //                'role' => 'main',
     //           ]);

     //           // Step 3: Update company with recruiter_id
     //           // $company->update([
     //           //      'recruiter_id' => $recruiter->id,
     //           // ]);

     //           // Step 4: Upload company profile
     //           if ($request->hasFile('company_profile')) {
     //                $existingProfile = AdditionalInfo::where('user_id', $recruiter->id)
     //                     ->where('user_type', 'recruiter')
     //                     ->where('doc_type', 'company_profile')
     //                     ->first();

     //                if (!$existingProfile) {
     //                     $originalName = $request->file('company_profile')->getClientOriginalName();
     //                     $storedName = 'company_profile_' . time() . '.' . $request->file('company_profile')->getClientOriginalExtension();
     //                     $request->file('company_profile')->move('uploads/', $storedName);

     //                     AdditionalInfo::create([
     //                          'user_id'       => $recruiter->id,
     //                          'user_type'     => 'recruiter',
     //                          'doc_type'      => 'company_profile',
     //                          'document_name' => $originalName,
     //                          'document_path' => asset('uploads/' . $storedName),
     //                     ]);
     //                }
     //           }

     //           // Step 5: Upload registration documents
     //           if ($request->hasFile('registration_documents')) {
     //                foreach ($request->file('registration_documents') as $file) {
     //                     $originalName = $file->getClientOriginalName();
     //                     $storedName = 'registration_documents_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
     //                     $file->move('uploads/', $storedName);

     //                     AdditionalInfo::create([
     //                          'user_id'       => $recruiter->id,
     //                          'user_type'     => 'recruiter',
     //                          'doc_type'      => 'registration_documents',
     //                          'document_name' => $originalName,
     //                          'document_path' => asset('uploads/' . $storedName),
     //                     ]);
     //                }
     //           }

     //           DB::commit(); // ✅ All operations succeeded
     //           return redirect()->route('recruiter.login')->with('success', 'Company and Recruiter information saved successfully.');

     //      } catch (\Exception $e) {
     //           DB::rollBack(); // ❌ Something went wrong
     //           return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
     //      }
     // }
     public function storeRecruiterInformation(Request $request)
     {
          $validated = $request->validate([
               'recruiter_id' => 'required|exists:recruiters,id',
               'name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
               'national_id' => [
                    'required',
                    'min:10',
                    function ($attribute, $value, $fail) {
                         if (Recruiters::where('national_id', $value)->exists()) {
                              $fail('The national ID has already been taken in another account.');
                         }
                    },
               ],
               'company_name' => 'required|regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
               'company_website' => 'required|url',
               'company_city' => 'required|string|max:255',
               'company_address' => 'required|string|max:500',
               'business_email' => 'required|email|unique:recruiters_company,business_email',
               'phone_code' => 'required|string',
               'company_phone_number' => 'required|unique:recruiters_company,company_phone_number',
               'no_of_employee' => 'required|string|max:255',
               'industry_type' => 'required|string|max:255',
               'registration_number' => 'required|string|max:255',
               'company_profile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
               'registration_documents.*' => 'file|mimes:pdf,doc,docx,jpeg,jpg,png|max:2048',
          ], [
               'name.required' => 'Name is required.',
               'name.regex' => 'The full name should contain only letters and single spaces.',
               'national_id.required' => 'National ID is required.',
               'company_name.required' => 'Company name is required.',
               'company_website.url' => 'Enter a valid URL.',
               'business_email.unique' => 'This business email is already used.',
               'company_phone_number.unique' => 'This phone number is already used.',
          ]);

          DB::beginTransaction();

          try {
               // Step 1: Save Company
               // Step 1: Save Company
          $company = RecruiterCompany::create([
               'company_name' => $validated['company_name'],
               'company_website' => $validated['company_website'],
               'company_city' => $validated['company_city'],
               'company_address' => $validated['company_address'],
               'business_email' => $validated['business_email'],
               'phone_code' => $validated['phone_code'],
               'company_phone_number' => $validated['company_phone_number'],
               'no_of_employee' => $validated['no_of_employee'],
               'industry_type' => $validated['industry_type'],
               'registration_number' => $validated['registration_number'],
               'is_registered' => 1,
          ]);

          $recruiter = Recruiters::find($request->recruiter_id);

          if (!$recruiter) {
          return back()->withErrors(['error' => 'Recruiter not found.']);
          }

          $email = $recruiter->email;


          $recruiter->update([
               'name'        => $validated['name'],
               'email'        => $email,
               'national_id' => $validated['national_id'],
               'company_id'  => $company->id,
               'role'        => 'main',
               'status'      => 'active',
          ]);

          // Step 3: Update company with recruiter_id
          $company->update([
               'recruiter_id' => $recruiter->id,
          ]);


          // Step 4: Upload company profile
          if ($request->hasFile('company_profile')) {
               $originalName = $request->file('company_profile')->getClientOriginalName();
               $storedName = 'company_profile_' . time() . '.' . $request->file('company_profile')->getClientOriginalExtension();
               $request->file('company_profile')->move('uploads/', $storedName);

               AdditionalInfo::create([
                    'user_id'       => $recruiter->id,
                    'user_type'     => 'recruiter',
                    'doc_type'      => 'company_profile',
                    'document_name' => $originalName,
                    'document_path' => asset('uploads/' . $storedName),
               ]);
          }

          // Step 5: Upload registration documents
          if ($request->hasFile('registration_documents')) {
               foreach ($request->file('registration_documents') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $storedName = 'registration_documents_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/', $storedName);

                    AdditionalInfo::create([
                         'user_id'       => $recruiter->id,
                         'user_type'     => 'recruiter',
                         'doc_type'      => 'registration_documents',
                         'document_name' => $originalName,
                         'document_path' => asset('uploads/' . $storedName),
                    ]);
               }
          }

          DB::commit();
          $data = [
               'sender_id' => $recruiter->id,
               'sender_type' => 'Registration by Company and Recruiter.',
               'receiver_id' => '1',
               'message' => 'Welcome to Talentrek – Registration Successful by '.$recruiter->name,
               'is_read' => 0,
               'is_read_admin' => 0,
               'user_type' => 'recruiter'
          ];

          Notification::insert($data);
          return redirect()->route('recruiter.login')->with('success', 'Company and Recruiter information saved successfully.');

     } catch (\Exception $e) {
          DB::rollBack();
          return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
     }
     }



    public function loginRecruiter(Request $request)
     {
     $this->validate($request, [
          'email'    => 'required|email',
          'password' => 'required'
     ]);

     $recruiter = Recruiters::where('email', $request->email)->first();

     if (!$recruiter) {
          session()->flash('error', 'Recruiter account not found.');
          return back()->withInput($request->only('email'));
     }

     if ($recruiter->status !== 'active') {
          session()->flash('error', 'Your account is inactive. Please contact support.');
          return back()->withInput($request->only('email'));
     }

     // ✅ Check admin_status
     if ($recruiter->admin_status === 'superadmin_reject' || $recruiter->admin_status === 'rejected') {
          session()->flash('error', 'Your account has been rejected by administrator.');
          return back()->withInput($request->only('email'));
     }

     if ($recruiter->admin_status !== 'superadmin_approved') {
          session()->flash('error', 'Your account is not yet approved by administrator.');
          return back()->withInput($request->only('email'));
     }

     // ✅ Attempt login only if status = active and admin_status = approved
     if (Auth::guard('recruiter')->attempt([
          'email'    => $request->email,
          'password' => $request->password
     ])) {
          return redirect()->route('recruiter.dashboard');
     } else {
          session()->flash('error', 'Incorrect password.');
          return back()->withInput($request->only('email'));
     }
     }

    
 
     public function logoutrecruiter(Request $request)
     {
          Auth::guard('recruiter')->logout();
          $request->session()->invalidate(); 
          $request->session()->regenerateToken(); 
          return redirect()->route('recruiter.login')->with('success', 'Logged out successfully');
     }

     public function submitForgetPassword(Request $request)
     {
          $request->validate([
               'contact' => ['required', function ($attribute, $value, $fail) {
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $column = $isEmail ? 'business_email' : 'company_phone_number';

                    $exists = DB::table('recruiters_company')->where($column, $value)->exists();

                    if (!$exists) {
                         $fail("This " . ($isEmail ? 'email' : 'mobile number') . " is not registered.");
                    }
               }],
          ]);

          $otp = rand(100000, 999999);
          $contact = $request->contact;
          $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
          $contactMethod = $isEmail ? 'business_email' : 'company_phone_number';

          // Save OTP in database
          DB::table('recruiters_company')->where($contactMethod, $contact)->update([
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
          return redirect()->route('recruiter.verify-otp')->with('success', 'OTP sent!');


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
        DB::table('recruiters_company')->where($contactMethod, $contact)->update([
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
          $column = $isEmail ? 'business_email' : 'company_phone_number';

          $recruiterCompany = DB::table('recruiters_company')
               ->where($column, $contact)
               ->where('otp', $request->otp)
               ->first();

          if (!$recruiterCompany) {
               return back()
                    ->withErrors(['otp' => 'Invalid OTP or contact. Please enter the correct 6-digit OTP.'])
                    ->withInput();
          }

          // Save verified user ID in session
          session(['verified_recruiter' => $recruiterCompany->id]);

          return redirect()->route('recruiter.reset-password');
     }

     public function resetPassword(Request $request){
       $request->validate([
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);
        $recruiterId = session('verified_recruiter');
       
        if (!$recruiterId) {
            return redirect()->route('recruiter.forget.password')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $updated = DB::table('recruiters_company')->where('id', $recruiterId)->update([
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

        return redirect()->route('recruiter.login')->with('success', 'Password change successfully.');
    }

   

     public function showJobseekerListForm()
     {
          $recruiterId = auth()->user()->id;
          $shortlistedIds = RecruiterJobseekersShortlist::where('recruiter_id', $recruiterId)
                              ->pluck('jobseeker_id')
                              ->toArray();

          $jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
                    ->where('status', 'active')
                    ->whereIn('admin_status', ['approved', 'superadmin_approved'])
                    ->whereNotIn('id', $shortlistedIds)
                    ->get();
          

         $shortlisted_jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
                                   ->join('recruiter_jobseeker_shortlist as shortlist', 'jobseekers.id', '=', 'shortlist.jobseeker_id')
                                   ->where('shortlist.recruiter_id', $recruiterId)
                                   ->where('jobseekers.status', 'active')
                                   ->where('shortlist.interview_status', NULL)
                                   ->select(
                                        'jobseekers.*',
                                        'shortlist.admin_status as shortlist_admin_status',
                                        'shortlist.interview_request',
                                        'shortlist.jobseeker_id as jobseeker_id', // ✅ explicit alias
                                   )
                                   ->get();

        

          $scheduled_jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
                                   ->join('recruiter_jobseeker_shortlist as shortlist', 'jobseekers.id', '=', 'shortlist.jobseeker_id')
                                   ->where('shortlist.recruiter_id', $recruiterId)
                                   ->where('jobseekers.status', 'active')
                                   ->where('shortlist.interview_status', 'scheduled')
                                   ->orWhere('shortlist.interview_status', 'cancelled')
                                   ->orWhere('shortlist.interview_status', 'completed')
                                   ->select(
                                        'jobseekers.*',
                                        'shortlist.admin_status as shortlist_admin_status',
                                        'shortlist.interview_request',
                                        'shortlist.jobseeker_id as jobseeker_id', // ✅ explicit alias
                                        'shortlist.*', // ✅ explicit alias
                                   )
                                   ->orderBy('shortlist.created_at', 'desc')
                                   ->get();
     //  echo "<pre>";
     //          print_r($scheduled_jobseekers);die;
          return view('site.recruiter.recruiter-jobseekers', compact('jobseekers', 'shortlisted_jobseekers','scheduled_jobseekers'));
     }


     public function shortlistSubmit(Request $request)
     {
          $recruiterId = auth()->id(); // ✅ current recruiter id (could be sub-recruiter)
          $jobseekerId = $request->input('jobseeker_id');

          // ✅ get main recruiter id (if recruiter_of is 0, he is the main recruiter)
          $mainRecruiterId = Recruiters::where('id', $recruiterId)
                                        ->value('recruiter_of') ?: $recruiterId;

          // ✅ get company of the main recruiter
          $recruiterCompany = RecruiterCompany::where('recruiter_id', $mainRecruiterId)->first();

          if (!$recruiterCompany) {
               return redirect()->back()->with('error', 'Recruiter company not found.');
          }

          // ✅ check if jobseeker already shortlisted by same company
          $alreadyShortlisted = RecruiterJobseekersShortlist::where('company_id', $recruiterCompany->id)
                              ->where('jobseeker_id', $jobseekerId)
                              ->exists();

          if ($alreadyShortlisted) {
               return redirect()->back()->with('error', 'Someone has already shortlisted him from your company.');
          }

          // ✅ save to shortlist table
          RecruiterJobseekersShortlist::create([
               'company_id'        => $recruiterCompany->id,
               'recruiter_id'      => $recruiterId,   // keep current recruiter who shortlisted
               'jobseeker_id'      => $jobseekerId,
               'interview_request' => 'yes',
               'admin_status'      => 'pending',
          ]);

          return redirect()->back()->with('success', 'Jobseeker shortlisted successfully');
     }


     public function replyFeedback(Request $request)
     {
          $request->validate([
               'jobseeker_id'     => 'required|exists:jobseekers,id',
               'feedback'         => 'required|string|max:1000',
               'interview_result' => 'required|in:pass,fail',
          ]);

          $recruiter = Auth::user();

          // ✅ Save feedback in feedbacks table
          Feedback::create([
               'sender_id'        => $recruiter->id,
               'sender_type'      => 'recruiter',
               'to_id'            => $request->jobseeker_id,
               'to_type'          => 'jobseeker',
               'feedback_message' => $request->feedback,
          ]);

          // Update interview result in recruiter_jobseeker_shortlist (example table)
          DB::table('recruiter_jobseeker_shortlist')
               ->where('jobseeker_id', $request->jobseeker_id)
               ->where('recruiter_id', $recruiter->id) // ensure current recruiter’s record
               ->update([
                    'interview_result' => $request->interview_result,
                    'updated_at'       => now(),
               ]);

          return back()->with('success', 'Feedback & Interview Result saved successfully!');
     }



     public function updateStatus(Request $request)
     {
     // Validation
     $validator = Validator::make($request->all(), [
          'jobseeker_id' => 'required|exists:jobseekers,id',
          'status'       => 'required|in:cancelled,completed',
     ]);

     if ($validator->fails()) {
          return redirect()->back()->withErrors($validator)->withInput();
     }

     // Find shortlist record for this recruiter + jobseeker
     $shortlist = RecruiterJobseekersShortlist::where('jobseeker_id', $request->jobseeker_id)
          ->where('recruiter_id', auth('recruiter')->id())
          ->first();

     if (!$shortlist) {
          return redirect()->back()->with('error', 'Record not found.');
     }

     // Update interview status
     $shortlist->interview_status = ucfirst($request->status); // "Cancelled" / "Completed"
     $shortlist->save();

     return redirect()->back()->with('success', 'Interview status updated successfully.');
     }

     public function interviewRequestSubmit(Request $request)
     {
          $request->validate([
               'jobseeker_id' => 'required|integer',
          ]);

          $recruiter = auth()->user();
          $jobseekerId = $request->input('jobseeker_id');

          $shortlist = RecruiterJobseekersShortlist::where([
               'company_id' => $recruiter->id,
               'recruiter_id' => $recruiter->recruiter_id,
               'jobseeker_id' => $jobseekerId,
          ])->first();

          if ($shortlist) {
               $shortlist->update([
                    'interview_request' => 'yes',
               ]);
          } else {
               RecruiterJobseekersShortlist::create([
                    'company_id' => $recruiter->id,
                    'recruiter_id' => $recruiter->recruiter_id,
                    'jobseeker_id' => $jobseekerId,
                    'status' => 'yes',
                    'admin_status' => 'pending',
                    'interview_request' => 'yes',
                    'interview_url' => null,
               ]);
          }

          return response()->json(['success' => true, 'message' => 'Interview request sent.']);
     }




     public function getJobseekerDetails($jobseeker_id)
     {
          $recruiterId = auth()->id();
          $jobseeker = Jobseekers::with(['educations', 'experiences'])
                         ->where('id', $jobseeker_id)
                         ->firstOrFail();

          $skill = Skills::where('jobseeker_id', $jobseeker_id)
                         ->first(); 

          $additional =  AdditionalInfo::where('user_id', $jobseeker_id)
                         ->where('user_type', 'jobseeker')
                         ->first();              
          // echo "<pre>";
          // print_r( $skills);exit;
          // echo "</pre>";
          $shortlisted_jobseeker = Jobseekers::with(['educations', 'experiences', 'skills'])
               ->join('recruiter_jobseeker_shortlist as shortlist', 'jobseekers.id', '=', 'shortlist.jobseeker_id')
               ->where('shortlist.recruiter_id', $recruiterId)
               ->where('jobseekers.status', 'active')
               ->select(
                    'jobseekers.*',
                    'shortlist.admin_status as shortlist_admin_status',
                    'shortlist.interview_request'
               )
               ->get();

          return view('site.recruiter.jobseeker-view-details', compact('jobseeker','skill','additional','shortlisted_jobseeker'));
     }

     public function showRecruitmentSettingForm()
     {
     $recruiter = auth('recruiter')->user(); // logged in recruiter
     $recruiterRole = $recruiter->role;

     // Get company of this recruiter (assuming recruiter table has recruiter_company_id)
     $recruiterId = auth('recruiter')->id();

     $companyDetails = RecruiterCompany::with('recruiters')
     ->whereHas('recruiters', function ($q) use ($recruiterId) {
          $q->where('id', $recruiterId);
     })
     ->first();


     // Documents (common for the company or per recruiter depending on your schema)
     $companyProfile = AdditionalInfo::where('user_id', $recruiter->id)
          ->where('doc_type', 'company_profile')
          ->first();

     $registrationDoc = AdditionalInfo::where('user_id', $recruiter->id)
          ->where('doc_type', 'register_document')
          ->first();

     return view('site.recruiter.setting', compact('companyDetails', 'companyProfile', 'registrationDoc'));
     }


   
     public function updateCompanyProfile(Request $request)
     {
     $user      = auth()->user();
     $recruiter = Recruiters::find($user->id);
     $company   = RecruiterCompany::find($recruiter->company_id);

     if (!$recruiter || !$company) {
          return response()->json(['message' => 'Profile not found.'], 404);
     }

     $rules = [
          'company_name'         => 'required|string|max:255',
          'company_phone_number' => 'required|digits:9',
          'business_email'       => [
               'required', 'email',
               Rule::unique('recruiters_company', 'business_email')->ignore($company->id),
          ],
          'industry_type'      => 'required|string',
          'establishment_date' => 'required|date',
          'company_website'    => 'nullable|url',

          // Recruiters validation
          'recruiters.*.id'          => 'required|exists:recruiters,id',
          'recruiters.*.name'        => 'required|string|max:255',
          'recruiters.*.email'       => 'required|email',
          'recruiters.*.national_id' => 'required|digits:15',
          'recruiters.*.mobile'      => 'required|digits:9',
     ];

     $messages = [
          'company_name.required'          => 'Company name is required.',
          'company_name.string'            => 'Company name must be a valid string.',
          'company_name.max'               => 'Company name cannot exceed 255 characters.',

          'company_phone_number.required'  => 'Company phone number is required.',
          'company_phone_number.digits'    => 'Company phone number must be exactly 9 digits.',

          'business_email.required'        => 'Business email is required.',
          'business_email.email'           => 'Business email must be a valid email address.',
          'business_email.unique'          => 'This business email is already taken.',

          'industry_type.required'         => 'Industry type is required.',
          'industry_type.string'           => 'Industry type must be a valid string.',

          'establishment_date.required'    => 'Establishment date is required.',
          'establishment_date.date'        => 'Establishment date must be a valid date.',

          'company_website.url'            => 'Company website must be a valid URL.',

          // Recruiters messages
          'recruiters.*.id.required'       => 'Recruiter ID is required.',
          'recruiters.*.id.exists'         => 'Recruiter ID must exist in the system.',

          'recruiters.*.name.required'     => 'Recruiter name is required.',
          'recruiters.*.name.string'       => 'Recruiter name must be a valid string.',
          'recruiters.*.name.max'          => 'Recruiter name cannot exceed 255 characters.',

          'recruiters.*.email.required'    => 'Recruiter email is required.',
          'recruiters.*.email.email'       => 'Recruiter email must be a valid email address.',

          'recruiters.*.national_id.required' => 'Recruiter national ID is required.',
          'recruiters.*.national_id.digits'   => 'Recruiter national ID must be exactly 15 digits.',

          'recruiters.*.mobile.required'   => 'Recruiter mobile number is required.',
          'recruiters.*.mobile.digits'     => 'Recruiter mobile number must be exactly 9 digits.',
          ];

          // Usage
          $validated = $request->validate($rules, $messages);



     // ✅ Custom validation for recruiter email & national_id uniqueness
     $validator->after(function ($validator) use ($request) {
          foreach ($request->recruiters as $index => $recData) {
               $recId = $recData['id'] ?? null;

               // Email uniqueness
               if (!empty($recData['email'])) {
                    $exists = Recruiters::where('email', $recData['email'])
                         ->where('id', '!=', $recId)
                         ->exists();
                    if ($exists) {
                         $validator->errors()->add("recruiters.$index.email", 'The email has already been taken.');
                    }
               }

               // National ID uniqueness across all user types
               if (!empty($recData['national_id'])) {
                    $duplicate = Recruiters::where('national_id', $recData['national_id'])
                         ->where('id', '!=', $recId)
                         ->exists()
                         || Trainers::where('national_id', $recData['national_id'])->exists()
                         || Jobseekers::where('national_id', $recData['national_id'])->exists();

                    if ($duplicate) {
                         $validator->errors()->add("recruiters.$index.national_id", 'The national ID has already been taken.');
                    }
               }
          }
     });

     if ($validator->fails()) {
          return response()->json([
               'status' => 'error',
               'errors' => $validator->errors(),
          ], 422);
     }

     $validated = $validator->validated();

     // ✅ Update company
     $company->update([
          'company_name'         => $validated['company_name'],
          'company_phone_number' => $validated['company_phone_number'],
          'business_email'       => $validated['business_email'],
          'industry_type'        => $validated['industry_type'],
          'establishment_date'   => Carbon::parse($validated['establishment_date'])->format('Y-m-d'),
          'company_website'      => $validated['company_website'] ?? null,
     ]);

     // ✅ Update recruiters
     foreach ($request->recruiters as $data) {
          $rec = Recruiters::find($data['id']);
          if ($rec) {
               // Sub recruiters can only update themselves
               if ($recruiter->role === 'sub_recruiter' && $rec->id !== $recruiter->id) {
                    continue;
               }

               $rec->update([
                    'name'        => $data['name'],
                    'email'       => $data['email'],
                    'national_id' => $data['national_id'],
                    'mobile'      => $data['mobile'],
               ]);
          }
     }

     return response()->json([
          'status'  => 'success',
          'message' => 'Company and recruiter profile updated successfully!',
     ]);
     }






     public function updateCompanyDocument(Request $request)
     {
          $userId = auth()->id();

          $validated = $request->validate([
               'company_profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
               'register_document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
          ], [
               'company_profile.file' => 'The company profile must be a valid file.',
               'company_profile.mimes' => 'The company profile must be a file of type: jpg, jpeg, png.',
               'company_profile.max' => 'The company profile must not be greater than 2MB.',

               'register_document.file' => 'The registration document must be a valid file.',
               'register_document.mimes' => 'The registration document must be a file of type: pdf, doc, docx.',
               'register_document.max' => 'The registration document must not be greater than 2MB.',
          ]);

          foreach (['company_profile', 'register_document'] as $type) {
               if ($request->hasFile($type)) {
                    $file = $request->file($type);
                    $fileName = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads'), $fileName);
                    $path = asset('uploads/' . $fileName);

                    AdditionalInfo::updateOrCreate(
                         ['user_id' => $userId, 'doc_type' => $type],
                         [
                              'document_path' => $path,
                              'document_name' => $fileName,
                              'user_type' => 'recruiter', // Ensures recruiter type is stored
                         ]
                    );
               }
          }

          return response()->json([
               'status' => 'success',
               'message' => 'Company documents updated successfully!',
          ]);
     }


     public function deleteCompanyDocument($type)
     {
          $userId = auth()->id();

          $record = AdditionalInfo::where('user_id', $userId)
                         ->where('doc_type', $type)
                         ->first();

          if ($record) {
               $filePath = public_path(parse_url($record->document_path, PHP_URL_PATH));
               if (file_exists($filePath)) {
                    unlink($filePath);
               }
               $record->delete();

               return response()->json([
                    'status' => 'success',
                    'message' => ucfirst(str_replace('_', ' ', $type)) . ' deleted successfully.',
               ]);
          }

          return response()->json([
               'status' => 'error',
               'message' => 'Document not found.',
          ], 404);
     }


     public function deleteAccount()
     {
          $user = auth()->user(); 

          $companyId = $user->id; 
          $recruiterId = $user->recruiter_id;

          RecruiterCompany::where('id', $companyId)->delete();
          Recruiters::where('id', $recruiterId)->delete();

          auth()->logout();

          return redirect()->route('recruiter.login')->with('success', 'Your account has been deleted successfully.');
     }






    public function filterJobseekers(Request $request)
     {
     $recruiterId = auth()->user()->recruiter_id;

     // Get shortlisted IDs
     $shortlistedIds = RecruiterJobseekersShortlist::where('recruiter_id', $recruiterId)
          ->pluck('jobseeker_id')
          ->toArray();

     // Get shortlisted jobseekers with total experience
     $shortlisted_jobseekers = $this->getShortlistedJobseekers($recruiterId);

     // Get non-shortlisted jobseekers
     $jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
          ->where('status', 'active')
          ->whereIn('admin_status', ['approved', 'superadmin_approved'])
          ->whereNotIn('id', $shortlistedIds)
          ->get();

     // Apply filters
     $filtered = $jobseekers->filter(function ($jobseeker) use ($request) {
          return $this->applyFilters($jobseeker, $request);
     });

     // Render HTML
     $jobseekerListHtml = view('site.recruiter.partials.jobseeker-list', ['jobseekers' => $filtered])->render();
     $shortlistedListHtml = view('site.recruiter.partials.jobseeker-list', ['jobseekers' => $shortlisted_jobseekers])->render();

     return response()->json([
          'jobseekers_html' => $jobseekerListHtml,
          'shortlisted_html' => $shortlistedListHtml
     ]);
     }

     private function getShortlistedJobseekers($recruiterId)
     {
     $shortlisted = Jobseekers::with(['educations', 'experiences', 'skills'])
          ->join('recruiter_jobseeker_shortlist as shortlist', 'jobseekers.id', '=', 'shortlist.jobseeker_id')
          ->where('shortlist.recruiter_id', $recruiterId)
          ->where('jobseekers.status', 'active')
          ->select(
               'jobseekers.*',
               'shortlist.admin_status as shortlist_admin_status',
               'shortlist.interview_request'
          )
          ->get();

     foreach ($shortlisted as $jobseeker) {
          $jobseeker->total_experience = $this->calculateExperience($jobseeker);
     }

     return $shortlisted;
     }

     private function calculateExperience($jobseeker)
     {
     $totalExp = 0;
     foreach ($jobseeker->experiences as $exp) {
          if ($exp->starts_from && $exp->end_to) {
               $start = Carbon::parse($exp->starts_from);
               $end = Carbon::parse($exp->end_to);
               $totalExp += $start->diffInDays($end);
          }
     }
     $years = floor($totalExp / 365);
     return $years . ' years';
     }

     private function applyFilters($jobseeker, $request)
     {
     $totalExp = 0;
     foreach ($jobseeker->experiences as $exp) {
          if ($exp->starts_from && $exp->end_to) {
               $start = Carbon::parse($exp->starts_from);
               $end = Carbon::parse($exp->end_to);
               $totalExp += $start->diffInDays($end);
          }
     }

     $yearsExp = floor($totalExp / 365);
     $jobseeker->total_experience = $yearsExp . ' years';

     // Experience filter
     if ($request->filled('experience') && !in_array('all', $request->experience)) {
          $match = false;
          if (in_array('fresher', $request->experience) && $yearsExp <= 3) $match = true;
          if (in_array('experienced', $request->experience) && $yearsExp > 3) $match = true;
          if (!$match) return false;
     }

     // Education filter
     if ($request->filled('education')) {
          $eduMatch = $jobseeker->educations->pluck('high_education')->intersect($request->education)->isNotEmpty();
          if (!$eduMatch) return false;
     }

     // Gender filter
     if ($request->filled('gender') && !in_array('all', $request->gender)) {
          $filterGenders = array_map('strtolower', $request->gender);
          $jobseekerGender = strtolower($jobseeker->gender);
          if (!in_array($jobseekerGender, $filterGenders)) {
               return false;
          }
     }

     // Certificate filter
     if ($request->filled('certificate') && !in_array('all', $request->certificate)) {
          $certificateCount = $jobseeker->skills->count();
          $match = false;
          if (in_array('0-5', $request->certificate) && $certificateCount >= 0 && $certificateCount <= 5) $match = true;
          if (in_array('5+', $request->certificate) && $certificateCount > 5) $match = true;
          if (in_array('not-certified', $request->certificate) && $certificateCount == 0) $match = true;
          if (!$match) return false;
     }

     return true;
     }



     public function processSubscriptionPayment(Request $request)
     {
     $request->validate([
          'plan_id'     => 'required|exists:subscription_plans,id',
          'card_number' => 'required|string|min:12|max:19',
          'expiry'      => 'required|string',
          'cvv'         => 'required|string|min:3|max:4',
     ]);

     $plan = SubscriptionPlan::findOrFail($request->plan_id);

     DB::beginTransaction();
     try {
          $recruiter = auth('recruiter')->user();
          $companyData = RecruiterCompany::where('recruiter_id', $recruiter->id)->firstOrFail();

          // Create purchased subscription
          $newSubscription = PurchasedSubscription::create([
               'user_id'              => $recruiter->id,
               'user_type'            => 'recruiter',
               'company_id'           => $companyData->id,
               'subscription_plan_id' => $plan->id,
               'start_date'           => now(),
               'end_date'             => now()->addDays($plan->duration_days),
               'amount_paid'          => $plan->price,
               'payment_status'       => 'paid',
          ]);

          // Determine if we should update active subscription
          $shouldUpdate = false;
          if (!$companyData->active_subscription_plan_id) {
               $shouldUpdate = true;
          } else {
               $currentActive = PurchasedSubscription::find($companyData->active_subscription_plan_id);
               if (!$currentActive || $newSubscription->end_date->gt($currentActive->end_date)) {
                    $shouldUpdate = true;
               }
          }

          if ($shouldUpdate) {
               $companyData->isSubscribtionBuy = 'yes';
               $companyData->active_subscription_plan_id   = $newSubscription->id;
               $companyData->active_subscription_plan_slug = $plan->slug;

               // Set recruiter count properly instead of null
               if ($companyData->recruiter_count !== null) {
                    $companyData->recruiter_count = null;
               }

               $companyData->save();
          }

          DB::commit();

          return response()->json([
               'status'  => 'success',
               'message' => 'Subscription purchased successfully!',
          ]);

     } catch (\Exception $e) {
          DB::rollBack();
          return response()->json([
               'status'  => 'error',
               'message' => 'Something went wrong while purchasing the subscription.',
               'error'   => $e->getMessage(),
          ], 500);
     }
     }




     public function addOthers(Request $request)
     {
          $validated = $request->validate([
     'main_recruiter_id'        => 'required|exists:recruiters,id',
     'company_id'               => 'required|exists:recruiters_company,id',
     'recruiters'               => 'required|array',
     'recruiters.*.name'        => 'required|string|max:100',
     'recruiters.*.email'       => 'required|email',
     'recruiters.*.national_id' => 'required|digits_between:10,15',
     ], [
     'main_recruiter_id.required'        => 'Main recruiter is required.',
     'main_recruiter_id.exists'          => 'Selected main recruiter does not exist.',
     'company_id.required'               => 'Company is required.',
     'company_id.exists'                 => 'Selected company does not exist.',
     'recruiters.required'               => 'At least one recruiter must be added.',
     'recruiters.array'                  => 'Recruiters must be an array.',
     'recruiters.*.name.required'        => 'Recruiter name is required.',
     'recruiters.*.name.string'          => 'Recruiter name must be a string.',
     'recruiters.*.name.max'             => 'Recruiter name may not be greater than 100 characters.',
     'recruiters.*.email.required'       => 'Recruiter email is required.',
     'recruiters.*.email.email'          => 'Recruiter email must be a valid email address.',
     'recruiters.*.national_id.required' => 'Recruiter national ID is required.',
     'recruiters.*.national_id.digits_between' => 'Recruiter national ID must be between 10 and 15 digits.',
     ]);


     DB::beginTransaction();

     try {
          $company    = RecruiterCompany::findOrFail($validated['company_id']);
          $addedCount = 0;

          foreach ($validated['recruiters'] as $i => $rec) {

               // Skip update, now we want to throw error if exists
               $existing = Recruiters::where('email', $rec['email'])
                    ->orWhere('national_id', $rec['national_id'])
                    ->first();

               if ($existing) {
                    DB::rollBack();
                    return response()->json([
                         'status'  => 'duplicate',
                         'message' => "Recruiter with email '{$rec['email']}' or National ID '{$rec['national_id']}' already exists."
                    ], 422);
               }

               // Create new recruiter
               $username = strtolower(str_replace(' ', '', $rec['name']));
               $password = $username . '@talentrek';

               Recruiters::create([
                    'name'         => $rec['name'],
                    'email'        => $rec['email'],
                    'company_id'   => $validated['company_id'],
                    'national_id'  => $rec['national_id'],
                    'role'         => 'sub_recruiter',
                    'recruiter_of' => $validated['main_recruiter_id'],
                    'password'     => Hash::make($password),
                    'pass'         => $password, // ⚠️ temporary only
               ]);

               $addedCount++;
          }

          // Update recruiter count
          $company->recruiter_count = ($company->recruiter_count ?? 0) + $addedCount;
          $company->save();

          DB::commit();

          return response()->json([
               'status'  => 'success',
               'message' => 'Recruiters added successfully.'
          ], 200);

     } catch (\Exception $e) {
          DB::rollBack();
          return response()->json([
               'status'  => 'error',
               'message' => $e->getMessage()
          ], 500);
     }
     }



     public function getUnreadCount(Request $request)
     {
        $recruiterId = auth()->guard('recruiter')->id();

        $query = DB::table('admin_group_chats')
            ->where('receiver_id', $recruiterId)
            ->where('receiver_type', 'recruiter')
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
          $recruiterId = auth()->guard('recruiter')->id();

          DB::table('admin_group_chats')
               ->where('receiver_id', $recruiterId)
               ->where('receiver_type', 'recruiter')
               ->where('is_read', 0)
               ->update(['is_read' => 1]);

          return response()->json(['success' => true]);
     }

     public function markMessagesSeen()
     {
          $recruiterId = auth()->guard('recruiter')->id();

          DB::table('admin_group_chats')
               ->where('receiver_id', $recruiterId)
               ->where('receiver_type', 'recruiter')
               ->where('is_read', 0)
               ->update(['is_read' => 1]);

          // Realtime broadcast
          event(new \App\Events\MessageSeen($recruiterId, 'recruiter', 'admin', 'admin'));

          return response()->json(['success' => true]);
     }






}
