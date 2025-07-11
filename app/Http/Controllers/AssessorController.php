<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Assessors;
use DB;
use Auth;

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
    return view('site.assessor.registration');
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
            'email' => 'required|email|unique:assessors,company_email',
            'phone_number' => 'required|unique:assessors,company_phone_number',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ]);

        $assessors = Assessors::create([
            'company_email' => $request->email,
            'company_phone_number' => $request->phone_number,
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
                $column = $isEmail ? 'company_email' : 'company_phone_number';

                $exists = DB::table('assessors')->where($column, $value)->exists();

                if (!$exists) {
                    $fail("This " . ($isEmail ? 'email' : 'mobile number') . " is not registered.");
                }
            }],
        ]);

        $otp = rand(100000, 999999);
        $contact = $request->contact;
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
        $contactMethod = $isEmail ? 'company_email' : 'company_phone_number';

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

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'contact' => 'required',
            'otp' => ['required', 'digits:6'],
        ]);

        $contact = $request->contact;
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);
        $column = $isEmail ? 'company_email' : 'company_phone_number';

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

        $assessor = Assessors::where('company_email', $request->email)->first();

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

        // Now attempt login only if status is active
        if (Auth::guard('assessor')->attempt(['company_email' => $request->email, 'password' => $request->password])) {
            // return view('site.trainer.trainer-dashboard');
            return redirect()->route('assessor.dashboard');
        } else {
            session()->flash('error', 'Invalid email or password.');
            return back()->withInput($request->only('email'));
        }
    }

    public function showAssessorDashboard()
    {
        return view('site.assessor.assessor-dashboard');    
    }

    public function logoutAssessor(Request $request)
    {
        Auth::guard('assessor')->logout();
        
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect()->route('assessor.login')->with('success', 'Logged out successfully');
    }
}
