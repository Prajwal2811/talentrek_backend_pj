<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoachController;


// Coach Routes
Route::group(['prefix' => 'coach'], function() {
	Route::group(['middleware' => 'coach.guest'], function(){
		Route::view('/sign-in','sign-in')->name('coach.sign-in');
		Route::post('/login',[CoachController::class, 'authenticate'])->name('coach.auth');

		Route::get('/sign-in', [CoachController::class, 'showSignInForm'])->name('coach.login');
		Route::get('/sign-up', [CoachController::class, 'showSignUpForm'])->name('coach.signup');
		Route::get('/registration', [CoachController::class, 'showRegistrationForm'])->name('coach.registration');
		Route::get('/forget-password', [CoachController::class, 'showForgotPasswordForm'])->name('coach.forget.password');
		Route::get('/verify-otp', [CoachController::class, 'showOtpForm'])->name('coach.verify-otp');
		Route::get('reset-password', [CoachController::class, 'showResetPasswordForm'])->name('coach.reset-password');

		Route::post('/registration', [CoachController::class, 'postRegistration'])->name('coach.register.post');
		Route::post('/submit-forget-password',[CoachController::class, 'submitForgetPassword'])->name('coach.submit.forget.password');
		Route::post('/submit-verify-otp', [CoachController::class, 'verifyOtp'])->name('coach.verify-otp.submit');
		Route::post('/submit-reset-password', [CoachController::class, 'resetPassword'])->name('coach.reset-password.submit');
		Route::post('/registration/store', [CoachController::class, 'storeCoachInformation'])->name('coach.registration.store');
		Route::post('/coach/login', [CoachController::class, 'loginCoach'])->name('coach.login.submit');
	});
	
	Route::group(['middleware' => 'coach.auth'], function(){
		Route::get('/dashboard',[CoachController::class, 'dashboard'])->name('coach.dashboard');

		Route::get('/dashboard',[CoachController::class, 'showCoachDashboard'])->name('coach.dashboard');
		Route::post('/logout',[CoachController::class, 'logoutCoach'])->name('coach.logout');
	});
});