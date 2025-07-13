<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessorController;



// Assessor Routes
Route::group(['prefix' => 'assessor'], function() {
	Route::group(['middleware' => 'assessor.guest'], function(){
		Route::view('/sign-in','sign-in')->name('assessor.sign-in');
		Route::post('/login',[AssessorController::class, 'authenticate'])->name('assessor.auth');

		Route::get('/sign-in', [AssessorController::class, 'showSignInForm'])->name('assessor.login');
		Route::get('/sign-up', [AssessorController::class, 'showSignUpForm'])->name('assessor.signup');
		Route::get('/registration', [AssessorController::class, 'showRegistrationForm'])->name('assessor.registration');
		Route::get('/forget-password', [AssessorController::class, 'showForgotPasswordForm'])->name('assessor.forget.password');
		Route::get('/verify-otp', [AssessorController::class, 'showOtpForm'])->name('assessor.verify-otp');
		Route::get('reset-password', [AssessorController::class, 'showResetPasswordForm'])->name('assessor.reset-password');

		Route::post('/registration', [AssessorController::class, 'postRegistration'])->name('assessor.register.post');
		Route::post('/submit-forget-password',[AssessorController::class, 'submitForgetPassword'])->name('assessor.submit.forget.password');
		Route::post('/submit-verify-otp', [AssessorController::class, 'verifyOtp'])->name('assessor.verify-otp.submit');
		Route::post('/submit-reset-password', [AssessorController::class, 'resetPassword'])->name('assessor.reset-password.submit');
		Route::post('/registration/store', [AssessorController::class, 'storeAssessorInformation'])->name('assessor.registration.store');
		Route::post('/assessor/login', [AssessorController::class, 'loginAssessor'])->name('assessor.login.submit');
	});
	
	Route::group(['middleware' => 'assessor.auth'], function(){
		Route::get('/dashboard',[AssessorController::class, 'dashboard'])->name('assessor.dashboard');

		Route::get('/dashboard',[AssessorController::class, 'showAssessorDashboard'])->name('assessor.dashboard');
		Route::post('/logout',[AssessorController::class, 'logoutAssessor'])->name('assessor.logout');
	});
});
