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
		Route::post('/resend-otp', [CoachController::class, 'resendOtp'])->name('coach.resend-otp');
		Route::get('/verify-otp', [CoachController::class, 'showOtpForm'])->name('coach.verify-otp');
		Route::get('reset-password', [CoachController::class, 'showResetPasswordForm'])->name('coach.reset-password');

		Route::post('/registration', [CoachController::class, 'postRegistration'])->name('coach.register.post');
		Route::post('/submit-forget-password',[CoachController::class, 'submitForgetPassword'])->name('coach.submit.forget.password');
		Route::post('/submit-verify-otp', [CoachController::class, 'verifyOtp'])->name('coach.verify-otp.submit');
		Route::post('/submit-reset-password', [CoachController::class, 'resetPassword'])->name('coach.reset-password.submit');
		Route::post('/registration/store', [CoachController::class, 'storeCoachInformation'])->name('coach.registration.store');
		Route::post('/coach/login', [CoachController::class, 'loginCoach'])->name('coach.login.submit');
	});
	
	// Routes accessible after login but before subscription
    Route::middleware(['coach.auth'])->group(function () {
        Route::get('/subscription', [CoachController::class, 'showSubscriptionPlans'])->name('coach.subscription.index');
        Route::post('/subscription-payment', [CoachController::class, 'processSubscriptionPayment'])->name('coach.subscription.payment');
    });


	Route::middleware(['coach.auth', 'check.coach.subscription'])->group(function () {
		Route::get('/dashboard',[CoachController::class, 'dashboard'])->name('coach.dashboard');

		Route::get('/dashboard',[CoachController::class, 'showCoachDashboard'])->name('coach.dashboard');
		Route::post('/logout',[CoachController::class, 'logoutCoach'])->name('coach.logout');

		Route::get('/setting-coach', [CoachController::class, 'showSettingscoach'])->name('setting.coach');

		Route::post('/coach/profile-update', [CoachController::class, 'coachProfileUpdate'])->name('coach.profile.update');
		Route::post('/coach/education-update', [CoachController::class, 'updateCoachEducationInfo'])->name('coach.education.update');
		Route::post('/coach/work-update', [CoachController::class, 'updateCoachWorkExperienceInfo'])->name('coach.workexprience.update');
		Route::post('/coach/skills-update', [CoachController::class, 'updateCoachSkillsInfo'])->name('coach.skills.update');
		Route::post('/coach/additional-info-update', [CoachController::class, 'updateCoachAdditionalInfo'])->name('coach.additional.update');
		Route::delete('coach/delete-document/{type}', [CoachController::class, 'deleteCoachDocument'])->name('coach.additional.delete');
		Route::delete('/delete', [CoachController::class, 'deleteAccount'])->name('coach.destroy');

		// Reviews
		Route::get('/reviews', [CoachController::class, 'coachReviews'])->name('coach.reviews');
		Route::delete('/delete-coach-review/{id}', [CoachController::class, 'deleteCoachReview'])->name('coach.review.delete');

		// manage-booking-slots-coach
		Route::get('manage-bookings', [CoachController::class, 'manageBooking'])->name('coach.manage-bookings');
		Route::get('create-bookings', [CoachController::class, 'createBooking'])->name('coach.create-bookings');
		Route::post('submit-bookings', [CoachController::class, 'submitBooking'])->name('coach.submit-bookings');

		Route::post('/dashboard-action',[CoachController::class, 'dashboardAction'])->name('coach.dashboard-action');
		Route::post('update-slot-status', [CoachController::class, 'updateStatus'])->name('coach.update-slot-status');
		Route::post('/coach/update-slot-time', [CoachController::class, 'updateSlotTime'])->name('coach.update-slot-time');
		Route::post('/coach/delete-slot', [CoachController::class, 'deleteSlot'])->name('coach.delete-slot');

	});
});