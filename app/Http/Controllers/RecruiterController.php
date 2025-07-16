<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recruiters;
use App\Models\Jobseekers;
use App\Models\Trainers;
use App\Models\Skills;
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

use Carbon\Carbon;

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
          return view('site.recruiter.dashboard');
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
     public function postRegistration(Request $request)
     {
          $validated = $request->validate([
               'email' => 'required|email|unique:recruiters_company,business_email',
               'phone_number' => 'required|unique:recruiters_company,company_phone_number',
               'password' => 'required|min:6|same:confirm_password',
               'confirm_password' => 'required|min:6',
          ]);
          

          $recruiterCompanies = RecruiterCompany::create([

               'recruiter_id' => NULL,
               'company_name' => $request->company_name,
               'business_email' => $request->email,
               'company_phone_number' => $request->phone_number,
               'password' => Hash::make($request->password),
               'pass' => $request->password,
          ]);

          session([
               'company_id' => $recruiterCompanies->id,
               'business_email' => $request->email,
               'company_phone_number' => $request->phone_number,
          ]);

          return redirect()->route('recruiter.registration');
     }

    
     public function storeRecruiterInformation(Request $request)
     {
          $validated = $request->validate([
               'company_id' => 'required|exists:recruiters_company,id',
               'name' => 'required|string|max:255',
               'email' => 'required|email|unique:recruiters,email',
               'national_id' => [
                    'required',
                    'min:10',
                    function ($attribute, $value, $fail) {
                         $existsInRecruiters = Recruiters::where('national_id', $value)->exists();
                         $existsInTrainers = Trainers::where('national_id', $value)->exists();
                         $existsInJobseekers = Jobseekers::where('national_id', $value)->exists();

                         if ($existsInRecruiters || $existsInTrainers || $existsInJobseekers) {
                              $fail('The national ID has already been taken in another account.');
                         }
                    },
               ],
               'company_name' => 'required|string',
               'company_website' => 'required|url',
               'company_city' => 'required|string|max:255',
               'company_address' => 'required|string|max:500',
               'business_email' => 'required|email|unique:recruiters_company,business_email,' . $request->company_id,
               'phone_code' => 'required|string',
               'company_phone_number' => 'required|unique:recruiters_company,company_phone_number,' . $request->company_id,
               'no_of_employee' => 'required|string|max:255',
               'industry_type' => 'required|string|max:255',
               'registration_number' => 'required|string|max:255',
               'company_profile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
               'registration_documents' => 'required|array',
               'registration_documents.*' => 'file|mimes:pdf,doc,docx,jpeg,jpg,png|max:2048',
               ], [
               'company_id.required' => 'Company ID is required.',
               'company_id.exists' => 'Selected company does not exist.',
               'name.required' => 'Name is required.',
               'email.required' => 'Email is required.',
               'email.email' => 'Enter a valid email address.',
               'email.unique' => 'This email is already in use.',
               'national_id.required' => 'National ID is required.',
               'national_id.min' => 'National ID must be at least 10 characters.',
               'company_name.required' => 'Company name is required.',
               'company_website.required' => 'Company website is required.',
               'company_website.url' => 'Enter a valid URL.',
               'company_city.required' => 'Company city is required.',
               'company_address.required' => 'Company address is required.',
               'business_email.required' => 'Business email is required.',
               'business_email.email' => 'Enter a valid business email address.',
               'business_email.unique' => 'This business email is already used by another company.',
               'phone_code.required' => 'Phone code is required.',
               'company_phone_number.required' => 'Company phone number is required.',
               'company_phone_number.unique' => 'This phone number is already used.',
               'no_of_employee.required' => 'Number of employees is required.',
               'industry_type.required' => 'Industry type is required.',
               'registration_number.required' => 'Registration number is required.',
               'company_profile.required' => 'Company profile image is required.',
               'company_profile.image' => 'Company profile must be an image.',
               'company_profile.mimes' => 'Only jpg, jpeg, and png files are allowed for company profile.',
               'company_profile.max' => 'Company profile image must not exceed 2MB.',
               'registration_documents.required' => 'At least one registration document is required.',
               'registration_documents.*.mimes' => 'Only pdf, doc, docx, jpg, jpeg, and png files are allowed.',
               'registration_documents.*.max' => 'Each document must not exceed 2MB.',
               ]);


          DB::beginTransaction();

          try {
               // Step 1: Update company
               $company = RecruiterCompany::find($validated['company_id']);
               $company->update([
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
               ]);

               // Step 2: Create recruiter
               $recruiter = Recruiters::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'national_id' => $validated['national_id'],
               ]);

               // Step 3: Update company with recruiter_id
               $company->update([
                    'recruiter_id' => $recruiter->id,
               ]);

               // Step 4: Upload company profile
               if ($request->hasFile('company_profile')) {
                    $existingProfile = AdditionalInfo::where('user_id', $recruiter->id)
                         ->where('user_type', 'recruiter')
                         ->where('doc_type', 'company_profile')
                         ->first();

                    if (!$existingProfile) {
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

               DB::commit(); // ✅ All operations succeeded
               return redirect()->route('recruiter.login')->with('success', 'Company and Recruiter information saved successfully.');

          } catch (\Exception $e) {
               DB::rollBack(); // ❌ Something went wrong
               return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
          }
     }


     public function loginRecruiter(Request $request)
     {
          $this->validate($request, [
               'email'     => 'required|email',
               'password'  => 'required'
          ]);

          $recruiter = RecruiterCompany::where('business_email', $request->email)->first();

          if (!$recruiter) {
               session()->flash('error', 'Recruiter account not found.');
               return back()->withInput($request->only('email'));
          }

          if ($recruiter->status !== 'active') {
               session()->flash('error', 'Your account is inactive. Please contact support.');
               return back()->withInput($request->only('email'));
          }

          if (Auth::guard('recruiter')->attempt([
               'business_email' => $request->email,
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
          $recruiterId = auth()->user()->recruiter_id;

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
                                   ->select(
                                        'jobseekers.*',
                                        'shortlist.admin_status as shortlist_admin_status',
                                        'shortlist.interview_request'
                                   )
                                   ->get();

//     echo "<pre>";
//     print_r($shortlisted_jobseekers);die;

          return view('site.recruiter.recruiter-jobseekers', compact('jobseekers', 'shortlisted_jobseekers'));
     }

 


     public function shortlistSubmit(Request $request)
     {
          $recruiterCompany = auth()->user();
          $recruiterCompanyId = $recruiterCompany->id;
          $recruiterId = $recruiterCompany->recruiter_id; 
          $jobseekerId = $request->input('jobseeker_id');
         
         
          // Save to shortlist table
          RecruiterJobseekersShortlist::create([
               'company_id' => $recruiterCompanyId,
               'recruiter_id' => $recruiterId,
               'jobseeker_id' => $jobseekerId,
               'status' => 'yes',
               'admin_status' => 'pending',
               'interview_url' => null,
          ]);


          return redirect()->back()->with('success', 'Jobseeker shortlisted successfully');
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
          echo "</pre>";
          return view('site.recruiter.jobseeker-view-details', compact('jobseeker','skill','additional'));
     }

     public function showRecruitmentSettingForm()
     {
          $recruiterId = auth()->id();
          
          // $companyDetails = RecruiterCompany::where('id', $recruiterId)->first();

          // $recruiterDetails = Recruiters::where('company_id', $recruiterId)->first();

          $companyDetails = DB::table('recruiters_company as rc')
               ->leftJoin('recruiters as r', 'r.id', '=', 'rc.recruiter_id')
               ->where('rc.id', $recruiterId)
               ->select(
                    'rc.id',
                    'rc.*',
                    'r.id as recruiter_id',
                    'r.*'
               )
               ->first();

          $companyProfile = AdditionalInfo::where('user_id', auth()->id())->where('doc_type', 'company_profile')->first();
          $registrationDoc = AdditionalInfo::where('user_id', auth()->id())->where('doc_type', 'register_document')->first();
          
          

          // dd($companyDetails);exit;
          return view('site.recruiter.setting', compact('companyDetails','companyProfile', 'registrationDoc'));
     }

   
     public function updateCompanyProfile(Request $request)
     {
          $user = auth()->user();

          // Recruiter and company data
          $recruiter = Recruiters::find($user->recruiter_id);
          $company = RecruiterCompany::find($user->id);

          if (!$recruiter || !$company) {
               return response()->json(['message' => 'Profile not found.'], 404);
          }

          $validated = $request->validate([
               // Company fields
               'company_name' => 'required|string|max:255',
               'company_phone_number' => 'required|digits:10',
               'business_email' => [
                    'required',
                    'email',
                    Rule::unique('recruiters_company', 'business_email')->ignore($company->id),
               ],
               'industry_type' => 'required|string',
               'establishment_date' => 'required|date_format:d-m-Y',
               'company_website' => 'nullable|url',

               // Recruiter fields
               'name' => 'required|string|max:255',
               'email' => [
                    'required',
                    'email',
                    Rule::unique('recruiters', 'email')->ignore($recruiter->id),
               ],
               'national_id' => [
                    'required',
                    'min:10',
                    function ($attribute, $value, $fail) use ($recruiter) {
                         $duplicate = Recruiters::where('national_id', $value)
                              ->where('id', '!=', $recruiter->id)
                              ->exists() ||
                              Trainers::where('national_id', $value)->exists() ||
                              Jobseekers::where('national_id', $value)->exists();

                         if ($duplicate) {
                              $fail('The national ID has already been taken.');
                         }
                    },
               ],
          ]);

          // Update company
          $company->update([
               'company_name' => $validated['company_name'],
               'company_phone_number' => $validated['company_phone_number'],
               'business_email' => $validated['business_email'],
               'industry_type' => $validated['industry_type'],
               'establishment_date' => Carbon::createFromFormat('d-m-Y', $validated['establishment_date'])->format('Y-m-d'),
               'company_website' => $validated['company_website'],
          ]);

          // Update recruiter
          $recruiter->update([
               'name' => $validated['name'],
               'email' => $validated['email'],
               'national_id' => $validated['national_id'],
          ]);

          return response()->json([
               'status' => 'success',
               'message' => 'Company and recruiter profile updated successfully!',
          ]);
     }


     public function updateCompanyDocument(Request $request)
     {
          $userId = auth()->id();

          $validated = $request->validate([
               'company_profile' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
               'register_document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
          ]);

          foreach (['company_profile', 'register_document'] as $type) {
               if ($request->hasFile($type)) {
                    $file = $request->file($type);
                    $fileName = $type . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads'), $fileName);
                    $path = asset('uploads/' . $fileName);

                    \App\Models\AdditionalInfo::updateOrCreate(
                         ['user_id' => $userId, 'doc_type' => $type],
                         ['document_path' => $path, 'document_name' => $fileName]
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

          $record = \App\Models\AdditionalInfo::where('user_id', $userId)
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
          $recruiterId = auth()->id();
          // dd($recruiterId);exit;
          RecruiterCompany::where('id', $recruiterId)->delete();
          Recruiters::where('company_id', $recruiterId)->delete();
          auth()->logout();

          return redirect()->route('recruiter.login')->with('success', 'Your account has been deleted successfully.');
     }

}
