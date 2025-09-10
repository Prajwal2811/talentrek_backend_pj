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
		

		// Route::get('/sign-in', [JobseekerController::class, 'showSignInForm'])->name('jobseeker.sign-in');
		// Route::get('/sign-up', [JobseekerController::class, 'showSignUpForm'])->name('jobseeker.sign-up');


		Route::get('/registration', [JobseekerController::class, 'showRegistrationForm'])->name('jobseeker.registration');
		Route::post('/registration', [JobseekerController::class, 'postRegistration'])->name('jobseeker.register.post'); 
		Route::post('/registration/store', [JobseekerController::class, 'storeJobseekerInformation'])->name('jobseeker.registration.store');

		Route::get('/sign-in', [JobseekerController::class, 'showSignInForm'])->name('signin.form');
		Route::get('/sign-up', [JobseekerController::class, 'showSignUpForm'])->name('signup.form');
		Route::post('/jobseeker/login', [JobseekerController::class, 'loginJobseeker'])->name('jobseeker.login.submit');
		Route::post('/submit-forget-password', [JobseekerController::class, 'submitForgetPassword'])->name('submit.forget.password');
		Route::post('/resend-otp', [JobseekerController::class, 'resendOtp'])->name('jobseeker.resend-otp');
		Route::get('/verify-otp', [JobseekerController::class, 'showOtpForm'])->name('jobseeker.verify-otp');
		Route::post('/submit-verify-otp', [JobseekerController::class, 'verifyOtp'])->name('jobseeker.verify-otp.submit');
		Route::get('reset-password', [JobseekerController::class, 'showResetPasswordForm'])->name('jobseeker.reset-password');
		Route::post('/submit-reset-password', [JobseekerController::class, 'resetPassword'])->name('jobseeker.reset-password.submit');
		


		Route::post('/check-promocode', [JobseekerController::class, 'check'])->name('jobseeker.check-promocode');
		Route::get('auth/google/redirect', [JobseekerController::class, 'redirectToGoogle'])->name('google.redirect');
		Route::get('auth/google/callback', [JobseekerController::class, 'handleGoogleCallback'])->name('google.callback');

		

	});
	
 	// Routes accessible after login but before subscription
    Route::middleware(['jobseeker.auth'])->group(function () {
        Route::get('/subscription', [JobseekerController::class, 'showSubscriptionPlans'])->name('jobseeker.subscription.index');
        Route::post('/subscription-payment', [JobseekerController::class, 'processSubscriptionPayment'])->name('jobseeker.subscription.payment');
    });



	Route::middleware(['jobseeker.auth', 'check.jobseeker.subscription'])->group(function () {
		Route::get('/dashboard',[JobseekerController::class, 'dashboard'])->name('jobseeker.dashboard');
		Route::post('/login',[JobseekerController::class, 'authenticate'])->name('jobseeker.auth');
		Route::get('/profile', [JobseekerController::class, 'showProfilePage'])->name('jobseeker.profile');
		Route::get('/profile', [JobseekerController::class, 'getJobseekerAllDetails'])->name('jobseeker.profile');
		Route::post('/logout',[JobseekerController::class, 'logoutJobseeker'])->name('jobseeker.logout');
		Route::post('/profile/update-personal-info',[JobseekerController::class, 'updatePersonalInfo'])->name('jobseeker.profile.update');
		Route::post('/profile/update-education-info',[JobseekerController::class, 'updateEducationInfo'])->name('jobseeker.education.update');
		Route::post('/profile/update-work-exprience-info',[JobseekerController::class, 'updateWorkExprienceInfo'])->name('jobseeker.workexprience.update'); 
		Route::post('/profile/update-skills-info',[JobseekerController::class, 'updateSkillsInfo'])->name('jobseeker.skill.update'); 
		Route::post('/profile/additional-info',[JobseekerController::class, 'updateAdditionalInfo'])->name('jobseeker.additional.update'); 
		Route::delete('/jobseeker/additional/delete/{type}', [JobseekerController::class, 'deleteAdditionalFile'])->name('jobseeker.additional.delete');


		Route::get('/mentorship-details/{mentor_id}/mentorship-book-session/{slot_id}', [JobseekerController::class, 'bookingSession'])->name('mentorship-book-session');
		Route::get('/assessor-details/{assessor_id}/assessor-book-session/{slot_id}', [JobseekerController::class, 'bookingAssessorSession'])->name('assessor-book-session');
		Route::get('/coach-details/{coach_id}/coach-book-session/{slot_id}', [JobseekerController::class, 'bookingCoachSession'])->name('coach-book-session');

		Route::post('/submit-review', [JobseekerController::class, 'submitReview'])->name('submit.review');
		Route::post('/submit-assessor-review', [JobseekerController::class, 'submitAssessorReview'])->name('submit.assessor.review');
		Route::post('/submit-coach-review', [JobseekerController::class, 'submitCoachReview'])->name('submit.coach.review');
		Route::post('/submit-mentor-review', [JobseekerController::class, 'submitMentorReview'])->name('submit.mentor.review');
			
		Route::post('/purchase-course', [JobseekerController::class, 'purchaseCourse'])->name('jobseeker.purchase-course');
		Route::post('/team-purchase-course', [JobseekerController::class, 'teamPurchaseCourse'])->name('jobseeker.team-purchase-course');

		Route::post('/jobseeker/save-answer', [JobseekerController::class, 'saveJobseekerAnswer'])->name('jobseeker.saveAnswer');
		Route::post('/jobseeker/submit-quiz', [JobseekerController::class, 'submitQuiz'])->name('jobseeker.submitQuiz');
		Route::post('/save-remaining-time', [JobseekerController::class, 'saveRemainingTime'])->name('jobseeker.saveRemainingTime');

		Route::get('/quiz/success', [JobseekerController::class, 'quizSuccess'])->name('jobseeker.quizSuccessPage');
		Route::get('/assessment/result/{id}', [JobseekerController::class, 'viewScore'])->name('jobseeker.assessment.result');

		Route::post('/add-to-cart/{id}', [JobseekerController::class, 'addToCart'])->name('jobseeker.addtocart');

		// Route::post('/cart/remove/{id}', [JobseekerController::class, 'remove'])->name('cart.remove');
		Route::post('/cart/remove/{id}', [JobseekerController::class, 'removeCartItem'])->name('cart.remove');


		Route::post('/chat/send', [JobseekerController::class, 'sendMessage'])->name('jobseeker.chat.send');
    	Route::get('/chat/messages', [JobseekerController::class, 'getMessages'])->name('jobseeker.chat.fetch');



	});

		Route::get('/mentorship-details/{id}', [JobseekerController::class, 'mentorshipDetails'])->name('mentorship-details');
		Route::get('/mentorship-details/{mentor_id}/mentorship-book-session/{slot_id}', [JobseekerController::class, 'bookingSession'])->name('mentorship-book-session');
		Route::get('/get-available-slots', [JobseekerController::class, 'getAvailableSlots'])->name('get-available-slots');
		Route::get('/get-assessor-available-slots', [JobseekerController::class, 'getAssesorAvailableSlots'])->name('get-assessor-available-slots');

		Route::get('/get-coach-available-slots', [JobseekerController::class, 'getCoachAvailableSlots'])->name('get-coach-available-slots');
		

		
		Route::post('/mentorship-book-session', [JobseekerController::class, 'submitMentorshipBooking'])->name('mentorship-booking-submit');
		Route::post('/assessor-book-session', [JobseekerController::class, 'submitAssessorBooking'])->name('assessor-booking-submit');
		Route::post('/coach-book-session', [JobseekerController::class, 'submitCoachBooking'])->name('coach-booking-submit');



		Route::get('/jobseeker/mentorship-booking-success', function () {
			return view('jobseeker.booking-success');
		})->name('mentorship-booking-success');


		Route::get('/course-details/{id}', [JobseekerController::class, 'courseDetails'])->name('course.details');
		Route::get('/take-assessment/{id}', [JobseekerController::class, 'viewAssessment'])->name('assessment.view');

		Route::get('/buy-course/{id}', [JobseekerController::class, 'buyCourseDetails'])->name('buy-course');
		Route::get('/buy-course-for-team/{id}', [JobseekerController::class, 'buyTeamCourseDetails'])->name('buy-course-for-team');
		Route::post('/purchase-course', [JobseekerController::class, 'purchaseCourse'])->name('jobseeker.purchase-course');
		Route::post('/team-purchase-course', [JobseekerController::class, 'teamPurchaseCourse'])->name('jobseeker.team-purchase-course');


		// Zoom OAuth routes
		// Route::get('/zoom/authorize', [JobseekerController::class, 'redirectToZoom'])->name('redirectToZoom');
		// Route::get('/zoom/callback', [JobseekerController::class, 'handleZoomCallback']);
		Route::get('/zoom/authorize', [JobseekerController::class, 'redirectToZoom'])->name('zoom.redirect');
		Route::get('/zoom/callback', [JobseekerController::class, 'handleZoomCallback'])->name('zoom.callback');

		// Route::get('/zoom/create-meeting', [JobseekerController::class, 'createMeeting']);
	
		// routes/web.php

		// Assessors
		Route::get('/assessor-details/{id}', [JobseekerController::class, 'assessorDetails'])->name('assessor-details');
		Route::get('/coach-details/{id}', [JobseekerController::class, 'coachDetails'])->name('coach-details');

		Route::post('/apply-coupon', [JobseekerController::class, 'applyCoupon'])->name('jobseeker.apply-coupon');


});
