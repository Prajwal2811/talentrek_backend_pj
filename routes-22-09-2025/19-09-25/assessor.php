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

		Route::post('/resend-otp', [AssessorController::class, 'resendOtp'])->name('assessor.resend-otp');



		Route::post('/submit-verify-otp', [AssessorController::class, 'verifyOtp'])->name('assessor.verify-otp.submit');

		Route::post('/submit-reset-password', [AssessorController::class, 'resetPassword'])->name('assessor.reset-password.submit');

		Route::post('/registration/store', [AssessorController::class, 'storeAssessorInformation'])->name('assessor.registration.store');

		Route::post('/assessor/login', [AssessorController::class, 'loginAssessor'])->name('assessor.login.submit');





		Route::get('auth/google/redirect', [AssessorController::class, 'redirectToGoogle'])->name('assessor.google.redirect');

		Route::get('auth/google/callback', [AssessorController::class, 'handleGoogleCallback'])->name('assessor.google.callback');



	});

	



	// Routes accessible after login but before subscription

    Route::middleware(['assessor.auth'])->group(function () {

        Route::get('/subscription', [AssessorController::class, 'showSubscriptionPlans'])->name('assessor.subscription.index');

        Route::post('/subscription-payment', [AssessorController::class, 'processSubscriptionPayment'])->name('assessor.subscription.payment');

    });





	Route::middleware(['assessor.auth', 'check.assessor.subscription'])->group(function () {

		Route::get('/dashboard',[AssessorController::class, 'dashboard'])->name('assessor.dashboard');



		Route::get('/dashboard',[AssessorController::class, 'showAssessorDashboard'])->name('assessor.dashboard');

		Route::post('/logout',[AssessorController::class, 'logoutAssessor'])->name('assessor.logout');

		Route::get('/setting-assessor', [AssessorController::class, 'showSettingsAssessor'])->name('setting.assessor');



		Route::post('/assessor/profile-update', [AssessorController::class, 'assessorProfileUpdate'])->name('assessor.profile.update');

		Route::post('/assessor/education-update', [AssessorController::class, 'updateAssessorEducationInfo'])->name('assessor.education.update');

		Route::post('/assessor/work-update', [AssessorController::class, 'updateAssessorWorkExperienceInfo'])->name('assessor.workexprience.update');

		Route::post('/assessor/skills-update', [AssessorController::class, 'updateAssessorSkillsInfo'])->name('assessor.skills.update');

		Route::post('/assessor/additional-info-update', [AssessorController::class, 'updateAssessorAdditionalInfo'])->name('assessor.additional.update');

		Route::delete('assessor/delete-document/{type}', [AssessorController::class, 'deleteAssessorDocument'])->name('assessor.additional.delete');



		Route::delete('/delete', [AssessorController::class, 'deleteAccount'])->name('assessor.destroy');



		// Chat with Jobseeker

		Route::get('/chat-with-jobseeker', [AssessorController::class, 'chatWithJobseekerAssessor'])->name('chat.with.jobseeker.assessor');



		// Admin Support

		Route::get('/admin-support-assessor', [AssessorController::class, 'adminSupportAssessor'])->name('admin-support-assessor');



		// Reviews

		Route::get('/reviews', [AssessorController::class, 'assessorReviews'])->name('assessor.reviews');

		Route::delete('/delete-assessor-review/{id}', [AssessorController::class, 'deleteAssessorReview'])->name('assessor.review.delete');



		// manage-booking-slots-assessor

		Route::get('manage-bookings', [AssessorController::class, 'manageBooking'])->name('assessor.manage-bookings');

		Route::get('create-bookings', [AssessorController::class, 'createBooking'])->name('assessor.create-bookings');

		Route::post('submit-bookings', [AssessorController::class, 'submitBooking'])->name('assessor.submit-bookings');



		Route::post('/dashboard-action',[AssessorController::class, 'dashboardAction'])->name('assessor.dashboard-action');

		Route::post('update-slot-status', [AssessorController::class, 'updateStatus'])->name('assessor.update-slot-status');

		Route::post('/assessor/update-slot-time', [AssessorController::class, 'updateSlotTime'])->name('assessor.update-slot-time');

		Route::post('/assessor/delete-slot', [AssessorController::class, 'deleteSlot'])->name('assessor.delete-slot');

	});

});

