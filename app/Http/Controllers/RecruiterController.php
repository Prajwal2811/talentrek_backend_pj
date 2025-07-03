<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recruiters;
use App\Models\Jobseekers;
use App\Models\Skills;
use App\Models\AdditionalInfo;
use App\Models\RecruiterCompany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DB;
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
               'recruiter_id' => null,
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

     // public function storeRecruiterInformation(Request $request)
     // {
     //      $recruiterId = session('recruiter_id');

     //      if (!$recruiterId) {
     //           return redirect()->route('recruiter.signup')->with('error', 'Session expired. Please sign up again.');
     //      }

     //      $recruiter = Recruiters::find($recruiterId);

     //      if (!$recruiter) {
     //           return redirect()->route('recruiter.signup')->with('error', 'Recruiter not found.');
     //      }

     //      $validated = $request->validate([
     //           'name' => 'required|string|max:255',
     //           'email' => 'required|email|unique:recruiters,email,' . $recruiter->id,
     //           'email' => 'required|string|max:255',
     //           'company_name' => 'required|string',
     //           'company_website' => 'required|url',
     //           'company_city' => 'required|string|max:255',
     //           'company_address' => 'required|string|max:500',
     //           'business_email' => 'required|email|unique:recruiters_company,business_email',
     //           'phone_code' => 'required|string',
     //           'company_phone_number' => 'required|unique:recruiters_company,company_phone_number',
     //           'no_of_employee' => 'required|string|max:255',
     //           'industry_type' => 'required|string|max:255',
     //           'registration_number' => 'required|string|max:255',
     //      ]);

     //      // Update recruiter basic info
     //      $recruiter->name = $validated['name'];
     //      $recruiter->email = $validated['email'];
     //      $recruiter->save();

     //      // Save company details
     //      RecruiterCompany::create([
     //           'recruiter_id' => $recruiter->id,
     //           'company_name' => $validated['company_name'],
     //           'company_website' => $validated['company_website'],
     //           'company_city' => $validated['company_city'],
     //           'company_address' => $validated['company_address'],
     //           'business_email' => $validated['business_email'],
     //           'phone_code' => $validated['phone_code'],
     //           'company_phone_number' => $validated['company_phone_number'],
     //           'no_of_employee' => $validated['no_of_employee'],
     //           'industry_type' => $validated['industry_type'],
     //           'registration_number' => $validated['registration_number'],
     //      ]);

     //      return redirect()->route('recruiter.login')->with('success', 'Company information saved successfully.');
     // }
     public function storeRecruiterInformation(Request $request)
     {
     $validated = $request->validate([
          'company_id' => 'required|exists:recruiters_company,id',
          'name' => 'required|string|max:255',
          'email' => 'required|email|unique:recruiters,email',
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
     ]);

     // Step 1: Update company using company_id
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
          'company_id' => $company->id, // If needed
     ]);

     // Step 3: Update company with recruiter_id
     $company->update([
          'recruiter_id' => $recruiter->id,
     ]);

     return redirect()->route('recruiter.login')->with('success', 'Company and Recruiter information saved successfully.');
     }


  
     public function loginRecruiter(Request $request){
          $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if (Auth::guard('recruiter')->attempt(['business_email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('recruiter.dashboard');
        } else {
            session()->flash('error', 'Either Email/Password is incorrect');
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
                    $column = $isEmail ? 'email' : 'phone_number';

                    $exists = DB::table('recruiters')->where($column, $value)->exists();

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
          DB::table('recruiters')->where($contactMethod, $contact)->update([
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
          $column = $isEmail ? 'email' : 'phone_number';

          $recruiter = DB::table('recruiters')
               ->where($column, $contact)
               ->where('otp', $request->otp)
               ->first();

          if (!$recruiter) {
               return back()
                    ->withErrors(['otp' => 'Invalid OTP or contact. Please enter the correct 6-digit OTP.'])
                    ->withInput();
          }

          // Save verified user ID in session
          session(['verified_recruiter' => $recruiter->id]);

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

        $updated = DB::table('recruiters')->where('id', $recruiterId)->update([
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

     public function showJobseekerListForm(){
          $jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
          ->where('status', 'active')
          ->where('admin_status', 'approved','superadmin_approved')
          ->where('shortlist', "!=", "yes")
          ->get();

          $shortlisted_jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
          ->where('status', 'active')
          ->where('shortlist', 'yes')
          ->where('admin_status', 'approved','superadmin_approved')
          ->get();
          //dd($shortlisted_obseekers);exit;

          return view('site.recruiter.recruiter-jobseekers', compact('jobseekers', 'shortlisted_jobseekers'));
     }

     
     public function shortlistSubmit(Request $request)
     {
          $jobseekerId = $request->input('jobseeker_id');

          $jobseeker = Jobseekers::find($jobseekerId);

          if (!$jobseeker) {
               return redirect()->back()->with('error', 'Jobseeker not found');
          }

          $jobseeker->shortlist = 'yes';
          $jobseeker->admin_recruiter_status = 'pending';
          $jobseeker->save();

          return redirect()->back()->with('success', 'Jobseeker shortlisted successfully');
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
          
          $companyDetails = RecruiterCompany::where('id', $recruiterId)->first();
          //dd($companyDetails);exit;
          return view('site.recruiter.setting', compact('companyDetails'));
     }

   
     public function updateCompanyProfile(Request $request)
     {
          $recruiterId = auth()->id(); 
          
          $validated = $request->validate([
               'company_name' => 'required|string|max:255',
               'company_phone_number' => 'required|digits:10',
               'business_email' => 'required|email|unique:recruiters_company,business_email,' . $recruiterId . ',id',
               'industry_type' => 'required|string',
               'establishment_date' => 'required|date_format:d-m-Y',
               'company_website' => 'nullable|url',
          ]);

          $company_profiles = RecruiterCompany::where('id', $recruiterId)->first();

          if (!$company_profiles) {
               return redirect()->back()->with('error', 'Company not found.');
          }

          $company_profiles->update([
               'company_name' => $validated['company_name'],
               'company_phone_number' => $validated['company_phone_number'],
               'business_email' => $validated['business_email'],
               'industry_type' => $validated['industry_type'],
               'establishment_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['establishment_date']),
               'company_website' => $validated['company_website'],
          ]);
          
          return redirect()->back()->with('success', 'Company profile updated successfully.');
     }

     public function updateCompanyDocument(Request $request)
     {
          $recruiterId = auth()->id();

          if ($request->hasFile('register_document')) {
               $file = $request->file('register_document');
               $registerDocumentName = $file->getClientOriginalName();
               $fileNameToStore = 'register_document_' . time() . '.' . $file->getClientOriginalExtension();
               $file->move(public_path('uploads'), $fileNameToStore);

               $documentPath = asset('uploads/' . $fileNameToStore);

               $existingDocument = AdditionalInfo::where('user_id', $recruiterId)
                    ->where('user_type', 'recruiter')
                    ->where('doc_type', 'register_document')
                    ->first();

               if ($existingDocument) {
                    $existingDocument->update([
                         'document_name' => $registerDocumentName,
                         'document_path' => $documentPath,
                    ]);
               } else {
                    AdditionalInfo::create([
                         'user_id'       => $recruiterId,
                         'user_type'     => 'recruiter',
                         'doc_type'      => 'register_document',
                         'document_name' => $registerDocumentName,
                         'document_path' => $documentPath,
                    ]);
               }

               return redirect()->back()->with('success', 'Document uploaded successfully.');
          }

          return redirect()->back()->with('error', 'Please select a document to upload.');
     }

     public function deleteAccount()
     {
          $recruiterId = auth()->id();
          // dd($recruiterId);exit;
          RecruiterCompany::where('id', $recruiterId)->delete();
          auth()->logout();

          return redirect()->route('recruiter.login')->with('success', 'Your account has been deleted successfully.');
     }

}
