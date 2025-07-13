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


<<<<<<< HEAD
		// About Mentor
		Route::get('/about-coach', [App\Http\Controllers\MentorController::class, 'aboutCoach'])->name('about.coach');
=======
		// About Coach
		Route::get('/about-mentor', [App\Http\Controllers\MentorController::class, 'aboutMentor'])->name('about.mentor');
>>>>>>> 5d14cb5a3e592e2ce6e2b9e37d7a0e76204097b6
	
		// manage-booking-slots-mentor
		Route::get('manage-bookings', [MentorController::class, 'manageBooking'])->name('mentor.manage-bookings');
		Route::get('create-bookings', [MentorController::class, 'createBooking'])->name('mentor.create-bookings');
		Route::post('submit-bookings', [MentorController::class, 'submitBooking'])->name('mentor.submit-bookings');



		// Chat with Jobseeker
		Route::get('/chat-with-jobseeker', [MentorController::class, 'chatWithJobseekerMentor'])->name('chat.with.jobseeker.mentor');

		// Reviews
		Route::get('/mentor/reviews', [MentorController::class, 'mentorReviews'])->name('mentor.reviews');
		Route::delete('/delete-mentor-review/{id}', [MentorController::class, 'deleteMentorReview'])->name('mentor.review.delete');



		// Admin Support
		Route::get('/admin-support-mentor', [MentorController::class, 'adminSupportMentor'])->name('admin-support-mentor');

		// mentor Settings
		Route::get('/settings-mentor', [MentorController::class, 'settingsMentor'])->name('setting.mentor');
		Route::get('/settings-mentor/details', [MentorController::class, 'getMentorAllDetails'])->name('setting.mentor.details');
		// Mentor Routes - routes/web.php or routes/mentor.php
		Route::post('/mentor/profile-update', [MentorController::class, 'mentorProfileUpdate'])->name('mentor.profile.update');
		Route::post('/mentor/education-update', [MentorController::class, 'updateMentorEducationInfo'])->name('mentor.education.update');
		Route::post('/mentor/work-update', [MentorController::class, 'updateMentorWorkExperienceInfo'])->name('mentor.workexprience.update');
		Route::post('/mentor/skills-update', [MentorController::class, 'updateMentorSkillsInfo'])->name('mentor.skills.update');
		Route::post('/mentor/additional-info-update', [MentorController::class, 'updateMentorAdditionalInfo'])->name('mentor.additional.update');
		Route::delete('mentor/delete-document/{type}', [MentorController::class, 'deleteMentorDocument'])->name('mentor.additional.delete');


			

		Route::delete('/delete', [MentorController::class, 'deleteAccount'])->name('mentor.destroy');
		// Route::get('/trainer-settings', [MentorController::class, 'getTrainerAllDetails'])->name('trainer.settings');

	});
});
