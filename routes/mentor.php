<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MentorController;


// Mentor Routes
Route::group(['prefix' => 'mentor'], function() {
	Route::group(['middleware' => 'mentor.guest'], function(){
	    Route::view('/sign-in','sign-in')->name('mentor.sign-in');
		Route::post('/login',[MentorController::class, 'authenticate'])->name('mentor.auth');

		Route::get('/sign-in', [MentorController::class, 'showSignInForm'])->name('mentor.login');
		Route::get('/sign-up', [MentorController::class, 'showSignUpForm'])->name('mentor.signup');
		Route::get('/registration', [MentorController::class, 'showRegistrationForm'])->name('mentor.registration');
		Route::get('/forget-password', [MentorController::class, 'showForgotPasswordForm'])->name('mentor.forget.password');
		Route::get('/verify-otp', [MentorController::class, 'showOtpForm'])->name('mentor.verify-otp');
		Route::get('reset-password', [MentorController::class, 'showResetPasswordForm'])->name('mentor.reset-password');

		Route::post('/registration', [MentorController::class, 'postRegistration'])->name('mentor.register.post');
		Route::post('/submit-forget-password',[MentorController::class, 'submitForgetPassword'])->name('mentor.submit.forget.password');
		Route::post('/submit-verify-otp', [MentorController::class, 'verifyOtp'])->name('mentor.verify-otp.submit');
		Route::post('/submit-reset-password', [MentorController::class, 'resetPassword'])->name('mentor.reset-password.submit');
		Route::post('/registration/store', [MentorController::class, 'storeMentorInformation'])->name('mentor.registration.store');
		Route::post('/mentor/login', [MentorController::class, 'loginMentor'])->name('mentor.login.submit');
	});
	
	Route::group(['middleware' => 'mentor.auth'], function(){
		Route::get('/dashboard',[MentorController::class, 'dashboard'])->name('mentor.dashboard');

		Route::get('/dashboard',[MentorController::class, 'showMentorDashboard'])->name('mentor.dashboard');
		Route::post('/logout',[MentorController::class, 'logoutMentor'])->name('mentor.logout');


		// About Coach
		Route::get('/about-coach', [App\Http\Controllers\MentorController::class, 'aboutCoach'])->name('about.coach');
	
		// manage-booking-slots-mentor
		Route::get('bookingslot/manage-booking-slots-mentor', [MentorController::class, 'manageBookingSlotsMentor'])->name('manage.booking.slots.mentor');
		Route::get('bookingslot/create-booking-slots-mentor', [MentorController::class, 'createBookingSlotsMentor'])->name('create.booking.slots.mentor');

		// Chat with Jobseeker
		Route::get('/chat-with-jobseeker', [MentorController::class, 'chatWithJobseekerMentor'])->name('chat.with.jobseeker.mentor');

		// Reviews
		Route::get('/mentor/reviews', [MentorController::class, 'mentorReviews'])->name('mentor.reviews');

		// Admin Support
		Route::get('/admin-support-mentor', [MentorController::class, 'adminSupportMentor'])->name('admin-support-mentor');

		// mentor Settings
		Route::get('/settings-mentor', [MentorController::class, 'settingsMentor'])->name('setting.mentor');

		// Route::delete('/delete', [MentorController::class, 'deleteAccount'])->name('trainer.destroy');
		// Route::get('/trainer-settings', [MentorController::class, 'getTrainerAllDetails'])->name('trainer.settings');

	});
});
