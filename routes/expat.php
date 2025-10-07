<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpatController;
use App\Http\Controllers\CoursePurchaseController;
use App\Http\Controllers\SessionBookingController;

// Joobseeker Routes
Route::group(['prefix' => 'expat'], function() {
	Route::group(['middleware' => 'expat.guest'], function(){
		// Route::view('/sign-in','site.expat.sign-in')->name('expat.sign-in');
		// Route::view('/sign-up','site.expat.sign-up')->name('expat.sign-up');
		Route::view('/forget-password','site.expat.forget-password')->name('expat.forget-password');
		Route::view('/verify-otp','site.expat.verify-otp')->name('expat.verify-otp');
		Route::view('/reset-password','site.expat.reset-password')->name('expat.reset-password');
		Route::view('/registration','site.expat.registration')->name('expat.registration');
		
		

		Route::get('/registration', [ExpatController::class, 'showRegistrationForm'])->name('expat.registration');
		Route::post('/registration', [ExpatController::class, 'postRegistration'])->name('expat.register.post'); 
		Route::post('/registration/store', [ExpatController::class, 'storeExpatInformation'])->name('expat.registration.store');
		Route::get('/cv-template/download/{id}', [ExpatController::class, 'downloadCvTemplate'])->name('cv.template.download');
		Route::get('/sign-in', [ExpatController::class, 'showSignInForm'])->name('signin.form');
		Route::get('/sign-up', [ExpatController::class, 'showSignUpForm'])->name('signup.form');
		Route::post('/expat/login', [ExpatController::class, 'loginExpat'])->name('expat.login.submit');
		Route::post('/submit-forget-password', [ExpatController::class, 'submitForgetPassword'])->name('submit.forget.password');
		Route::post('/resend-otp', [ExpatController::class, 'resendOtp'])->name('expat.resend-otp');
		Route::get('/verify-otp', [ExpatController::class, 'showOtpForm'])->name('expat.verify-otp');
		Route::post('/submit-verify-otp', [ExpatController::class, 'verifyOtp'])->name('expat.verify-otp.submit');
		Route::get('reset-password', [ExpatController::class, 'showResetPasswordForm'])->name('expat.reset-password');
		Route::post('/submit-reset-password', [ExpatController::class, 'resetPassword'])->name('expat.reset-password.submit');
		

		Route::post('/check-promocode', [ExpatController::class, 'check'])->name('expat.check-promocode');
		Route::get('auth/google/redirect', [ExpatController::class, 'redirectToGoogle'])->name('expat.google.redirect');
		Route::get('auth/google/callback', [ExpatController::class, 'handleGoogleCallback'])->name('expat.google.callback');

	});
	
 	// Routes accessible after login but before subscription
    Route::middleware(['expat.auth'])->group(function () {
        Route::get('/subscription', [ExpatController::class, 'showSubscriptionPlans'])->name('expat.subscription.index');
        // Route::post('/subscription-payment', [ExpatController::class, 'processSubscriptionPayment'])->name('expat.subscription.payment');
    });



	Route::middleware(['expat.auth', 'check.expat.subscription'])->group(function () {
		Route::get('/dashboard',[ExpatController::class, 'dashboard'])->name('expat.dashboard');
		Route::post('/login',[ExpatController::class, 'authenticate'])->name('expat.auth');
		Route::get('/profile', [ExpatController::class, 'showProfilePage'])->name('expat.profile');
		Route::get('/profile', [ExpatController::class, 'getExpatAllDetails'])->name('expat.profile');
		Route::post('/logout',[ExpatController::class, 'logoutExpat'])->name('expat.logout');
		Route::post('/profile/update-personal-info',[ExpatController::class, 'updatePersonalInfo'])->name('expat.profile.update');
		Route::post('/profile/update-education-info',[ExpatController::class, 'updateEducationInfo'])->name('expat.education.update');
		Route::post('/profile/update-work-exprience-info',[ExpatController::class, 'updateWorkExprienceInfo'])->name('expat.workexprience.update'); 
		Route::post('/profile/update-skills-info',[ExpatController::class, 'updateSkillsInfo'])->name('expat.skill.update'); 
		Route::post('/profile/additional-info',[ExpatController::class, 'updateAdditionalInfo'])->name('expat.additional.update'); 
		Route::delete('/expat/additional/delete/{type}', [ExpatController::class, 'deleteAdditionalFile'])->name('expat.additional.delete');


		Route::get('/mentorship-details/{mentor_id}/mentorship-book-session/{slot_id}', [ExpatController::class, 'bookingSession'])->name('mentorship-book-session');
		Route::get('/assessor-details/{assessor_id}/assessor-book-session/{slot_id}', [ExpatController::class, 'bookingAssessorSession'])->name('assessor-book-session');
		Route::get('/coach-details/{coach_id}/coach-book-session/{slot_id}', [ExpatController::class, 'bookingCoachSession'])->name('coach-book-session');

		Route::post('/submit-review', [ExpatController::class, 'submitReview'])->name('submit.review');
		Route::post('/submit-assessor-review', [ExpatController::class, 'submitAssessorReview'])->name('submit.assessor.review');
		Route::post('/submit-coach-review', [ExpatController::class, 'submitCoachReview'])->name('submit.coach.review');
		Route::post('/submit-mentor-review', [ExpatController::class, 'submitMentorReview'])->name('submit.mentor.review');
			


		// Purchase request stays POST
		




		Route::post('/team-purchase-course', [ExpatController::class, 'teamPurchaseCourse'])->name('expat.team-purchase-course');

		Route::post('/expat/save-answer', [ExpatController::class, 'saveExpatAnswer'])->name('expat.saveAnswer');
		Route::post('/expat/submit-quiz', [ExpatController::class, 'submitQuiz'])->name('expat.submitQuiz');
		Route::post('/save-remaining-time', [ExpatController::class, 'saveRemainingTime'])->name('expat.saveRemainingTime');

		Route::get('/quiz/success', [ExpatController::class, 'quizSuccess'])->name('expat.quizSuccessPage');
		Route::get('/assessment/result/{id}', [ExpatController::class, 'viewScore'])->name('expat.assessment.result');

		Route::post('/add-to-cart/{id}', [ExpatController::class, 'addToCart'])->name('expat.addtocart');

		// Route::post('/cart/remove/{id}', [ExpatController::class, 'remove'])->name('cart.remove');
		Route::post('/cart/remove/{id}', [ExpatController::class, 'removeCartItem'])->name('cart.remove');


		Route::post('/chat/send', [ExpatController::class, 'sendMessage'])->name('expat.chat.send');
    	Route::get('/chat/messages', [ExpatController::class, 'getMessages'])->name('expat.chat.fetch');

		Route::get('/download-certificate/{material_id}', [ExpatController::class, 'downloadCertificate'])
    	->name('download.certificate');


		
	});

		Route::get('/mentorship-details/{id}', [ExpatController::class, 'mentorshipDetails'])->name('mentorship-details');
		Route::get('/mentorship-details/{mentor_id}/mentorship-book-session/{slot_id}', [ExpatController::class, 'bookingSession'])->name('mentorship-book-session');
		Route::get('/get-available-slots', [ExpatController::class, 'getAvailableSlots'])->name('get-available-slots');
		Route::get('/get-assessor-available-slots', [ExpatController::class, 'getAssesorAvailableSlots'])->name('get-assessor-available-slots');

		Route::get('/get-coach-available-slots', [ExpatController::class, 'getCoachAvailableSlots'])->name('get-coach-available-slots');
		

		
		// Route::post('/mentorship-book-session', [ExpatController::class, 'submitMentorshipBooking'])->name('mentorship-booking-submit');
		Route::post('/assessor-book-session', [ExpatController::class, 'submitAssessorBooking'])->name('assessor-booking-submit');
		Route::post('/coach-book-session', [ExpatController::class, 'submitCoachBooking'])->name('coach-booking-submit');



		Route::get('/expat/mentorship-booking-success', function () {
			return view('expat.booking-success');
		})->name('mentorship-booking-success');


		Route::get('/course-details/{id}', [ExpatController::class, 'courseDetails'])->name('course.details');
		Route::get('/take-assessment/{id}', [ExpatController::class, 'viewAssessment'])->name('assessment.view');
		Route::post('/expat/update-remaining-time', [App\Http\Controllers\ExpatController::class, 'updateRemainingTime'])
    	->name('expat.updateRemainingTime');


		Route::get('/buy-course/{id}', [ExpatController::class, 'buyCourseDetails'])->name('buy-course');
		Route::get('/buy-course-for-team/{id}', [ExpatController::class, 'buyTeamCourseDetails'])->name('buy-course-for-team');
		// Route::post('/purchase-course', [ExpatController::class, 'purchaseCourse'])->name('expat.purchase-course');
		Route::post('/team-purchase-course', [ExpatController::class, 'teamPurchaseCourse'])->name('expat.team-purchase-course');


		// Zoom OAuth routes
		// Route::get('/zoom/authorize', [ExpatController::class, 'redirectToZoom'])->name('redirectToZoom');
		// Route::get('/zoom/callback', [ExpatController::class, 'handleZoomCallback']);
		Route::get('/zoom/authorize', [ExpatController::class, 'redirectToZoom'])->name('zoom.redirect');
		Route::get('/zoom/callback', [ExpatController::class, 'handleZoomCallback'])->name('zoom.callback');

		// Route::get('/zoom/create-meeting', [ExpatController::class, 'createMeeting']);
	
		// routes/web.php

		// Assessors
		Route::get('/assessor-details/{id}', [ExpatController::class, 'assessorDetails'])->name('assessor-details');
		Route::get('/coach-details/{id}', [ExpatController::class, 'coachDetails'])->name('coach-details');

		Route::post('/apply-coupon', [ExpatController::class, 'applyCoupon'])->name('apply.coupon');
		
});
