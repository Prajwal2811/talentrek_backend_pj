<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainerController;


// Trainer Routes
// Trainer Routes
Route::group(['prefix' => 'trainer'], function() {
	Route::group(['middleware' => 'trainer.guest'], function(){
		Route::view('/sign-in','sign-in')->name('trainer.sign-in');
		Route::post('/login',[TrainerController::class, 'authenticate'])->name('trainer.auth');
		Route::get('/sign-in', [TrainerController::class, 'showSignInForm'])->name('trainer.login');
		Route::get('/sign-up', [TrainerController::class, 'showSignUpForm'])->name('trainer.signup');
		Route::get('/registration', [TrainerController::class, 'showRegistrationForm'])->name('trainer.registration');
		Route::get('/forget-password', [TrainerController::class, 'showForgotPasswordForm'])->name('trainer.forget.password');
		Route::get('/verify-otp', [TrainerController::class, 'showOtpForm'])->name('trainer.verify-otp');
		Route::get('reset-password', [TrainerController::class, 'showResetPasswordForm'])->name('trainer.reset-password');

		Route::post('/registration', [TrainerController::class, 'postRegistration'])->name('trainer.register.post');
		Route::post('/submit-forget-password',[TrainerController::class, 'submitForgetPassword'])->name('trainer.submit.forget.password');
		Route::post('/submit-verify-otp', [TrainerController::class, 'verifyOtp'])->name('trainer.verify-otp.submit');
		Route::post('/submit-reset-password', [TrainerController::class, 'resetPassword'])->name('trainer.reset-password.submit');
		Route::post('/registration/store', [TrainerController::class, 'storeTrainerInformation'])->name('trainer.registration.store');
		Route::post('/trainer/login', [TrainerController::class, 'loginTrainer'])->name('trainer.login.submit');
	});
	
	Route::group(['middleware' => 'trainer.auth'], function(){
		Route::get('/dashboard',[TrainerController::class, 'showTrainerDashboard'])->name('trainer.dashboard');

		// Logout
		Route::post('/logout',[TrainerController::class, 'logoutTrainer'])->name('trainer.logout');

		// Training
		Route::get('/training/list', [TrainerController::class, 'trainingList'])->name('training.list');
		Route::get('/training/add', [TrainerController::class, 'addTraining'])->name('training.add');
		Route::get('/training/online/add', [TrainerController::class, 'addOnlineTraining'])->name('training.online.add');
		Route::get('/training/recorded/add', [TrainerController::class, 'addRecordedTraining'])->name('training.recorded.add');
		
		Route::post('training/recorded/save-recorded-data', [TrainerController::class, 'saveTrainingRecorededData'])->name('trainer.training.recorded.save.data');
		Route::post('training/online/save-online-data', [TrainerController::class, 'saveTrainingOnlineData'])->name('trainer.training.online.save.data');

		Route::get('/training/recorded/edit/{id}', [TrainerController::class, 'editRecordedTraining'])->name('trainer.training.recorded.edit');
		Route::post('/training/recorded/update/{id}', [TrainerController::class, 'updateRecordedTraining'])->name('trainer.training.recorded.update.data');

		Route::get('/training/online/edit/{id}', [TrainerController::class, 'editOnlineTraining'])->name('trainer.training.online.edit');
		Route::post('/training/online/update/{id}', [TrainerController::class, 'updateOnlineTraining'])->name('trainer.training.online.update.data');

		// Assessment
		Route::get('/assessment/list', [TrainerController::class, 'assessmentList'])->name('assessment.list');
		Route::get('/assessment/add', [TrainerController::class, 'addAssessment'])->name('assessment.add');
		Route::post('/assessment/store', [TrainerController::class, 'assessmentStore'])->name('trainer.assessment.store');
		Route::post('/assessment/assign/course', [TrainerController::class, 'assignCourse'])->name('trainer.assessment.assign.course');

		// Batch
		Route::get('/batch', [TrainerController::class, 'batch'])->name('batch');

		// Trainees / Jobseekers
		Route::get('/trainees-jobseekers', [TrainerController::class, 'traineesJobseekers'])->name('trainees.jobseekers');

		// Chat with Jobseeker
		Route::get('/chat-with-jobseeker', [TrainerController::class, 'chatWithJobseeker'])->name('chat.with.jobseeker');

		// Reviews
		Route::get('/reviews', [TrainerController::class, 'reviews'])->name('reviews');
		// Reviews
		Route::get('/reviews', [TrainerController::class, 'trainerReviews'])->name('trainer.reviews');
		Route::delete('/delete-trainer-review/{id}', [TrainerController::class, 'deleteTrainerReview'])->name('trainer.review.delete');

		// Trainer Settings
		Route::get('/trainer-settings', [TrainerController::class, 'trainerSettings'])->name('trainer.settings');
		Route::delete('/delete', [TrainerController::class, 'deleteAccount'])->name('trainer.destroy');
		Route::get('/trainer-settings', [TrainerController::class, 'getTrainerAllDetails'])->name('trainer.settings');

		Route::post('/profile/update-personal-info',[TrainerController::class, 'updatePersonalInfo'])->name('trainer.profile.update');
		Route::post('/profile/update-education-info',[TrainerController::class, 'updateEducationInfo'])->name('trainer.education.update');
		Route::post('/profile/update-work-exprience-info',[TrainerController::class, 'updateWorkExprienceInfo'])->name('trainer.workexprience.update'); 
		Route::post('/profile/update-skills-info',[TrainerController::class, 'updateTrainerSkillsInfo'])->name('trainer.skill.update'); 
		Route::post('/profile/additional-info',[TrainerController::class, 'updateAdditionalInfo'])->name('trainer.additional.update'); 
		Route::delete('/profile/additional/delete/{type}', [TrainerController::class, 'deleteAdditionalFile'])->name('trainer.additional.delete');

		

	});

});

