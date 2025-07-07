<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recruiters;
use App\Models\Jobseekers;
use App\Models\Skills;
use App\Models\AdditionalInfo;
use App\Models\RecruiterCompany;
use App\Models\RecruiterJobseekersShortlist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\RegistrationSuccess;
use Illuminate\Support\Facades\Mail;

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
          'registration_documents'   => 'required|array',
          'registration_documents.*' => 'file|mimes:pdf,doc,docx,jpeg,jpg,png|max:2048',
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



     
     


     // public function storeRecruiterInformation(Request $request)
     // {
     //      DB::beginTransaction();
          
     //      try {
               
     //           $validated = $request->validate([
     //                'company_id' => 'required',
     //                'name' => 'required|string|max:255',
     //                'email' => 'required|email|unique:recruiters,email',
     //                'company_name' => 'required|string',
     //                'company_website' => 'required|url',
     //                'company_city' => 'required|string|max:255',
     //                'company_address' => 'required|string|max:500',
     //                'business_email' => 'required|email|unique:recruiters_company,business_email,' . $request->company_id,
     //                'phone_code' => 'required|string',
     //                'company_phone_number' => 'required|unique:recruiters_company,company_phone_number,' . $request->company_id,
     //                'no_of_employee' => 'required|string|max:255',
     //                'industry_type' => 'required|string|max:255',
     //                'registration_number' => 'required|string|max:255',

     //                //file valiadtion
     //                'company_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
     //                'registration_documents.*' => 'nullable|mimes:pdf,doc,docx,jpeg,jpg,png|max:2048',
     //           ]);

               

     //           // Step 1: Update company using company_id
     //           $company = RecruiterCompany::find($validated['company_id']);
     //           // echo "ddddddddddddddd";exit;
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
     //           ]);

     //           // Upload Company Profile
     //           if ($request->hasFile('company_profile')) {
     //                $existingProfile = AdditionalInfo::where('user_id', $company->id)
     //                     ->where('user_type', 'recruiter')
     //                     ->where('doc_type', 'company_profile')
     //                     ->first();

     //                if (!$existingProfile) {
     //                     $originalName = $request->file('company_profile')->getClientOriginalName();
     //                     $storedName = 'company_profile_' . time() . '.' . $request->file('company_profile')->getClientOriginalExtension();
     //                     $request->file('company_profile')->move('uploads/', $storedName);

     //                     AdditionalInfo::create([
     //                          'user_id'       => $company->id,
     //                          'user_type'     => 'recruiter',
     //                          'doc_type'      => 'company_profile',
     //                          'document_name' => $originalName,
     //                          'document_path' => asset('uploads/' . $storedName),
     //                     ]);
     //                }
     //           }
     //           // echo "BBBBB";exit;
     //           // Upload Registration Documents
     //           if ($request->hasFile('registration_documents')) {
     //                // echo "AAAAAAA";exit;
     //                foreach ($request->file('registration_documents') as $file) {
     //                     $originalName = $file->getClientOriginalName();
     //                     $storedName = 'registration_documents_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
     //                     $file->move('uploads/', $storedName);

     //                     AdditionalInfo::create([
     //                          'user_id'       => $company->id,
     //                          'user_type'     => 'recruiter',
     //                          'doc_type'      => 'registration_documents',
     //                          'document_name' => $originalName,
     //                          'document_path' => asset('uploads/' . $storedName),
     //                     ]);
     //                }
     //           }

     //           // Step 2: Create recruiter
     //           $recruiter = Recruiters::create([
     //                'name'       => $validated['name'],
     //                'email'      => $validated['email'],
     //           ]);

     //           // Step 3: Update company with recruiter_id
     //           $company->update([
     //                'recruiter_id' => $recruiter->id,
     //           ]);
          
     //           // Mail::to($validated['business_email'])->send(new RegistrationSuccess($company, 'recruiter'));

     //           DB::commit();

     //           // return redirect()->route('recruiter.login')->with('success', 'Company and Recruiter information saved successfully.');
     //           return redirect()->route('recruiter.login')->with('success_popup', true);

     //      } catch (\Exception $e) {
     //           DB::rollBack();

     //           Log::error('Recruiter Registration Failed: ' . $e->getMessage(), [
     //                'line' => $e->getLine(),
     //                'file' => $e->getFile(),
     //           ]);

     //           return back()->with('error', 'An error occurred while saving the data. Please try again.');
     //      }
     // }
  
     // public function loginRecruiter(Request $request){
     //      $this->validate($request, [
     //        'email'     => 'required|email',
     //        'password'  => 'required'
     //    ]);


  
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

     // public function showJobseekerListForm(){
     //      $jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
     //      ->where('status', 'active')
     //      ->where('admin_status', 'approved','superadmin_approved')
     //      // ->where('shortlist', "!=", "yes")
     //      ->get();

     //      $shortlisted_jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
     //      ->where('status', 'active')
     //      ->where('shortlist', 'yes')
     //      ->where('admin_status', 'approved','superadmin_approved')
     //      ->get();
     //      //dd($shortlisted_obseekers);exit;

     //      return view('site.recruiter.recruiter-jobseekers', compact('jobseekers', 'shortlisted_jobseekers'));
     // }

     public function showJobseekerListForm()
     {
     $recruiterId = auth()->user()->recruiter_id;

     $shortlistedIds = RecruiterJobseekersShortlist::where('recruiter_id', $recruiterId)
                         ->pluck('jobseeker_id')
                         ->toArray();
       
     $jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
          ->where('status', 'active')
          ->where('admin_status', 'approved','superadmin_approved')
          ->whereNotIn('id', $shortlistedIds)
          ->get();
     
     $shortlisted_jobseekers = Jobseekers::with(['educations', 'experiences', 'skills'])
          ->where('status', 'active')
          ->where('admin_status', 'approved','superadmin_approved')
          ->whereIn('id', $shortlistedIds)
          ->get();

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
               ->leftJoin('recruiters as r', 'r.company_id', '=', 'rc.id')
               ->where('rc.id', $recruiterId)
               ->select(
                    'rc.id as company_id',
                    'rc.*',
                    'r.id as recruiter_id',
                    'r.*'
               )
               ->first();



          // dd($companyDetails);exit;
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
          Recruiters::where('company_id', $recruiterId)->delete();
          auth()->logout();

          return redirect()->route('recruiter.login')->with('success', 'Your account has been deleted successfully.');
     }

}
