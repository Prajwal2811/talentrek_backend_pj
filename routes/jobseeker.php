<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobseekerController;


// Joobseeker Routes
Route::group(['prefix' => 'jobseeker'], function() {
	Route::group(['middleware' => 'jobseeker.guest'], function(){
		Route::view('/sign-in','site.jobseeker.sign-in')->name('jobseeker.sign-in');
		Route::view('/sign-up','site.jobseeker.sign-up')->name('jobseeker.sign-up');
		Route::view('/forget-password','site.jobseeker.forget-password')->name('jobseeker.forget-password');
		Route::view('/verify-otp','site.jobseeker.verify-otp')->name('jobseeker.verify-otp');
		Route::view('/reset-password','site.jobseeker.reset-password')->name('jobseeker.reset-password');
		Route::view('/registration','site.jobseeker.registration')->name('jobseeker.registration');
		

		Route::get('/registration', [JobseekerController::class, 'showRegistrationForm'])->name('jobseeker.registration');
		Route::post('/registration', [JobseekerController::class, 'postRegistration'])->name('jobseeker.register.post'); 
		Route::post('/registration/store', [JobseekerController::class, 'storeJobseekerInformation'])->name('jobseeker.registration.store');

		Route::get('/sign-in', [JobseekerController::class, 'showSignInForm'])->name('signin.form');
		Route::get('/sign-up', [JobseekerController::class, 'showSignUpForm'])->name('signup.form');
		Route::post('/jobseeker/login', [JobseekerController::class, 'loginJobseeker'])->name('jobseeker.login.submit');
		Route::post('/submit-forget-password', [JobseekerController::class, 'submitForgetPassword'])->name('submit.forget.password');
		Route::get('/verify-otp', [JobseekerController::class, 'showOtpForm'])->name('jobseeker.verify-otp');
		Route::post('/submit-verify-otp', [JobseekerController::class, 'verifyOtp'])->name('jobseeker.verify-otp.submit');
		Route::get('reset-password', [JobseekerController::class, 'showResetPasswordForm'])->name('jobseeker.reset-password');
		Route::post('/submit-reset-password', [JobseekerController::class, 'resetPassword'])->name('jobseeker.reset-password.submit');


		Route::get('auth/google', [JobseekerController::class, 'redirectToGoogle'])->name('jobseeker.google.redirect');
		Route::get('auth/google/callback', [JobseekerController::class, 'handleGoogleCallback']);

	});
	
	Route::group(['middleware' => 'jobseeker.auth'], function(){
		Route::get('/dashboard',[JobseekerController::class, 'dashboard'])->name('jobseeker.dashboard');

		Route::post('/login',[JobseekerController::class, 'authenticate'])->name('jobseeker.auth');
		Route::get('/profile', [JobseekerController::class, 'showProfilePage'])->name('jobseeker.profile');
		Route::get('/subscription-plan', [JobseekerController::class, 'showSubscriptionPlanPage'])->name('jobseeker.subscription.plan');
		Route::post('/subscription-payment', [JobseekerController::class, 'processSubscriptionPayment'])->name('jobseeker.subscription.payment');

		Route::get('/profile', [JobseekerController::class, 'getJobseekerAllDetails'])->name('jobseeker.profile');
		Route::post('/logout',[JobseekerController::class, 'logoutJobseeker'])->name('jobseeker.logout');
		Route::post('/profile/update-personal-info',[JobseekerController::class, 'updatePersonalInfo'])->name('jobseeker.profile.update');
		Route::post('/profile/update-education-info',[JobseekerController::class, 'updateEducationInfo'])->name('jobseeker.education.update');
		Route::post('/profile/update-work-exprience-info',[JobseekerController::class, 'updateWorkExprienceInfo'])->name('jobseeker.workexprience.update'); 
		Route::post('/profile/update-skills-info',[JobseekerController::class, 'updateSkillsInfo'])->name('jobseeker.skill.update'); 
		Route::post('/profile/additional-info',[JobseekerController::class, 'updateAdditionalInfo'])->name('jobseeker.additional.update'); 
		Route::delete('/jobseeker/additional/delete/{type}', [JobseekerController::class, 'deleteAdditionalFile'])->name('jobseeker.additional.delete');

		


	});

	
	Route::get('/mentorship-details/{id}', [JobseekerController::class, 'mentorshipDetails'])->name('mentorship-details');
	Route::get('/mentorship-details/{mentor_id}/mentorship-book-session/{slot_id}', [JobseekerController::class, 'bookingSession'])->name('mentorship-book-session');
	Route::get('/get-available-slots', [JobseekerController::class, 'getAvailableSlots'])->name('get-available-slots');
	Route::post('/mentorship-book-session', [JobseekerController::class, 'submitMentorshipBooking'])->name('mentorship-booking-submit');

	Route::get('/jobseeker/mentorship-booking-success', function () {
		return view('jobseeker.booking-success');
	})->name('mentorship-booking-success');


	Route::get('/course-details/{id}', [JobseekerController::class, 'courseDetails'])->name('course.details');

	Route::post('/submit-review', [JobseekerController::class, 'submitReview'])->name('submit.review');
	Route::get('/buy-course/{id}', [JobseekerController::class, 'buyCourseDetails'])->name('buy-course');


	// Zoom OAuth routes
	// Route::get('/zoom/authorize', [JobseekerController::class, 'redirectToZoom'])->name('redirectToZoom');
	// Route::get('/zoom/callback', [JobseekerController::class, 'handleZoomCallback']);
	Route::get('/zoom/authorize', [JobseekerController::class, 'redirectToZoom'])->name('zoom.redirect');
	Route::get('/zoom/callback', [JobseekerController::class, 'handleZoomCallback'])->name('zoom.callback');

	// Route::get('/zoom/create-meeting', [JobseekerController::class, 'createMeeting']);


});
