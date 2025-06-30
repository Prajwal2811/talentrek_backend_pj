<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recruiters;
use Illuminate\Support\Facades\Hash;

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
     public function postRegistration(Request $request)
     {
          $validated = $request->validate([
               'email' => 'required|email|unique:recruiters,email',
               'phone_number' => 'required|unique:recruiters,phone_number',
               'password' => 'required|min:6|same:confirm_password',
               'confirm_password' => 'required|min:6',
          ]);
          

          $recruiters = Recruiters::create([
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

     public function storeRecruiterInformation(Request $request)
     {
          $recruiterId = session('recruiter_id');

          if (!$recruiterId) {
               return redirect()->route('recruiter.signup')->with('error', 'Session expired. Please sign up again.');
          }

          $recruiter = Recruiters::find($recruiterId);

          if (!$recruiter) {
               return redirect()->route('recruiter.signup')->with('error', 'Recruiter not found.');
          }
     }
}
