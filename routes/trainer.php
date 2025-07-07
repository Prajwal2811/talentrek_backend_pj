<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainerController;


// Trainer Routes
// Trainer Routes
Route::group(['prefix' => 'trainer'], function() {
	Route::group(['middleware' => 'trainer.guest'], function(){
		Route::view('/sign-in','sign-in')->name('trainer.sign-in');
		Route::post('/login',[App\Http\Controllers\TrainerController::class, 'authenticate'])->name('trainer.auth');
		Route::get('/sign-in', [App\Http\Controllers\TrainerController::class, 'showSignInForm'])->name('trainer.login');
		Route::get('/sign-up', [App\Http\Controllers\TrainerController::class, 'showSignUpForm'])->name('trainer.signup');
		Route::get('/registration', [App\Http\Controllers\TrainerController::class, 'showRegistrationForm'])->name('trainer.registration');
		Route::get('/forget-password', [App\Http\Controllers\TrainerController::class, 'showForgotPasswordForm'])->name('trainer.forget.password');
		Route::get('/verify-otp', [App\Http\Controllers\TrainerController::class, 'showOtpForm'])->name('trainer.verify-otp');
		Route::get('reset-password', [App\Http\Controllers\TrainerController::class, 'showResetPasswordForm'])->name('trainer.reset-password');

		Route::post('/registration', [App\Http\Controllers\TrainerController::class, 'postRegistration'])->name('trainer.register.post');
		Route::post('/submit-forget-password',[App\Http\Controllers\TrainerController::class, 'submitForgetPassword'])->name('trainer.submit.forget.password');
		Route::post('/submit-verify-otp', [App\Http\Controllers\TrainerController::class, 'verifyOtp'])->name('trainer.verify-otp.submit');
		Route::post('/submit-reset-password', [App\Http\Controllers\TrainerController::class, 'resetPassword'])->name('trainer.reset-password.submit');
		Route::post('/registration/store', [App\Http\Controllers\TrainerController::class, 'storeTrainerInformation'])->name('trainer.registration.store');
		Route::post('/trainer/login', [App\Http\Controllers\TrainerController::class, 'loginTrainer'])->name('trainer.login.submit');
	});
	
	Route::group(['middleware' => 'trainer.auth'], function(){
		Route::get('/dashboard',[App\Http\Controllers\TrainerController::class, 'showTrainerDashboard'])->name('trainer.dashboard');

		// Logout
		Route::post('/logout',[App\Http\Controllers\TrainerController::class, 'logoutTrainer'])->name('trainer.logout');

		// Training
		Route::get('/training/list', [App\Http\Controllers\TrainerController::class, 'trainingList'])->name('training.list');
		Route::get('/training/add', [App\Http\Controllers\TrainerController::class, 'addTraining'])->name('training.add');
		Route::get('/training/online/add', [App\Http\Controllers\TrainerController::class, 'addOnlineTraining'])->name('training.online.add');
		Route::get('/training/recorded/add', [App\Http\Controllers\TrainerController::class, 'addRecordedTraining'])->name('training.recorded.add');
		
		Route::post('training/recorded/save-recorded-data', [App\Http\Controllers\TrainerController::class, 'saveTrainingRecorededData'])->name('trainer.training.recorded.save.data');
		Route::post('training/online/save-online-data', [App\Http\Controllers\TrainerController::class, 'saveTrainingOnlineData'])->name('trainer.training.online.save.data');

		Route::get('/training/recorded/edit/{id}', [TrainerController::class, 'editRecordedTraining'])->name('trainer.training.recorded.edit');

		Route::post('/training/recorded/update/{id}', [TrainerController::class, 'updateRecordedTraining'])->name('trainer.training.recorded.update.data');


		// Assessment
		Route::get('/assessment/list', [App\Http\Controllers\TrainerController::class, 'assessmentList'])->name('assessment.list');
		Route::get('/assessment/add', [App\Http\Controllers\TrainerController::class, 'addAssessment'])->name('assessment.add');

		// Batch
		Route::get('/batch', [App\Http\Controllers\TrainerController::class, 'batch'])->name('batch');

		// Trainees / Jobseekers
		Route::get('/trainees-jobseekers', [App\Http\Controllers\TrainerController::class, 'traineesJobseekers'])->name('trainees.jobseekers');

		// Chat with Jobseeker
		Route::get('/chat-with-jobseeker', [App\Http\Controllers\TrainerController::class, 'chatWithJobseeker'])->name('chat.with.jobseeker');

		// Reviews
		Route::get('/reviews', [App\Http\Controllers\TrainerController::class, 'reviews'])->name('reviews');

		// Trainer Settings
		Route::get('/trainer-settings', [App\Http\Controllers\TrainerController::class, 'trainerSettings'])->name('trainer.settings');
		


	});
});
