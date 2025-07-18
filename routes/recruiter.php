<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecruiterController;


Route::group(['prefix' => 'recruiter'], function() {
	Route::group(['middleware' => 'recruiter.guest'], function(){
		Route::view('/sign-in','sign-in')->name('recruiter.sign-in');
		Route::post('/login',[RecruiterController::class, 'authenticate'])->name('recruiter.auth');
		Route::get('/sign-in', [RecruiterController::class, 'showSignInForm'])->name('recruiter.login');
		Route::get('/sign-up', [RecruiterController::class, 'showSignUpForm'])->name('recruiter.signup');
		Route::get('/registration', [RecruiterController::class, 'showRegistrationForm'])->name('recruiter.registration');
		Route::get('/forget-password', [RecruiterController::class, 'showForgotPasswordForm'])->name('recruiter.forget.password');
		Route::post('/resend-otp', [RecruiterController::class, 'resendOtp'])->name('recruiter.resend-otp');
		Route::get('/verify-otp', [RecruiterController::class, 'showOtpForm'])->name('recruiter.verify-otp');
		Route::get('reset-password', [RecruiterController::class, 'showResetPasswordForm'])->name('recruiter.reset-password');


		Route::post('/registration', [RecruiterController::class, 'postRegistration'])->name('recruiter.register.post'); 
		Route::post('/registration/store', [RecruiterController::class, 'storeRecruiterInformation'])->name('recruitment.registration.store');
		Route::post('/recruiter/login', [RecruiterController::class, 'loginRecruiter'])->name('recruiter.login.submit');
		Route::post('/submit-forget-password', [RecruiterController::class, 'submitForgetPassword'])->name('recruiter.submit.forget.password');

		Route::post('/submit-verify-otp', [RecruiterController::class, 'verifyOtp'])->name('recruiter.verify-otp.submit');
		Route::post('/submit-reset-password', [RecruiterController::class, 'resetPassword'])->name('recruiter.reset-password.submit');
	});
	
	Route::group(['middleware' => 'recruiter.auth'], function(){
		Route::get('/dashboard',[RecruiterController::class, 'showRecruiterDashboard'])->name('recruiter.dashboard');
		Route::post('/logout',[RecruiterController::class, 'logoutrecruiter'])->name('recruiter.logout');
		Route::get('/jobseeker',[RecruiterController::class, 'showJobseekerListForm'])->name('recruiter.jobseeker');
		Route::get('/jobseeker/list',[RecruiterController::class, 'getAllJobseekerList'])->name('recruiter.dashboard.jobseeker.list');
		Route::post('/recruiter/shortlist/submit',[RecruiterController::class, 'shortlistSubmit'])->name('recruiter.shortlist.submit');
		Route::post('/recruiter/interview/submit',[RecruiterController::class, 'interviewRequestSubmit'])->name('recruiter.interview.request.submit');
		Route::get('/jobseeker/{jobseeker_id}/details', [RecruiterController::class, 'getJobseekerDetails'])
		->name('recruiter.jobseeker.details');
		Route::get('/settings', [RecruiterController::class, 'showRecruitmentSettingForm'])
		->name('recruiter.settings');

		Route::post('/profile/update',[RecruiterController::class, 'updateCompanyProfile'])->name('recruiter.company.profile.update');
		Route::post('/profile/document/update',[RecruiterController::class, 'updateCompanyDocument'])->name('recruiter.company.document.update');
		Route::delete('/profile/documents/delete/{type}', [RecruiterController::class, 'deleteCompanyDocument'])->name('recruiter.company.document.delete');
		
		Route::delete('/delete', [RecruiterController::class, 'deleteAccount'])->name('recruiter.destroy');


	});
});
