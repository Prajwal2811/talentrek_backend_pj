<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('site/index');
});

Route::get('training', function () {
    return view('site.training');
})->name('training');


Route::get('training-detail', function () {
    return view('site.training-detail');
})->name('training-detail');


Route::get('buy-course', function () {
    return view('site.buy-course');
})->name('buy-course');


Route::get('buy-course-for-team', function () {
    return view('site.buy-course-for-team');
})->name('buy-course-for-team');


Route::get('mentorship', function () {
    return view('site.mentorship');
})->name('mentorship');


Route::get('mentorship-details', function () {
    return view('site.mentorship-details');
})->name('mentorship-details');


Route::get('mentorship-book-session', function () {
    return view('site.mentorship-book-session');
})->name('mentorship-book-session');


Route::get('mentorship-booking-success', function () {
    return view('site.mentorship-booking-success');
})->name('mentorship-booking-success');

Route::get('assessment', function () {
    return view('site.assessment');
})->name('assessment');

Route::get('assessment-details', function () {
    return view('site.assessment-details');
})->name('assessment-details');

Route::get('assessment-book-session', function () {
    return view('site.assessment-book-session');
})->name('assessment-book-session');


Route::get('assessment-booking-success', function () {
    return view('site.assessment-booking-success');
})->name('assessment-booking-success');


Route::get('coaching', function () {
    return view('site.coaching');
})->name('coaching');


Route::get('coach-details', function () {
    return view('site.coach-details');
})->name('coach-details');

Route::get('coach-book-session', function () {
    return view('site.coach-book-session');
})->name('coach-book-session');

Route::get('coach-booking-success', function () {
    return view('site.coach-booking-success');
})->name('coach-booking-success');





Auth::routes();

// Joobseeker Routes
Route::group(['prefix' => 'jobseeker'], function() {
	Route::group(['middleware' => 'jobseeker.guest'], function(){
		Route::view('/sign-in','site.jobseeker.sign-in')->name('jobseeker.sign-in');
		Route::view('/sign-up','site.jobseeker.sign-up')->name('jobseeker.sign-up');
		Route::view('/forget-password','site.jobseeker.forget-password')->name('jobseeker.forget-password');
		Route::view('/verify-otp','site.jobseeker.verify-otp')->name('jobseeker.verify-otp');
		Route::view('/reset-password','site.jobseeker.reset-password')->name('jobseeker.reset-password');
		Route::view('/registration','site.jobseeker.registration')->name('jobseeker.registration');
		

		Route::post('/registration', [App\Http\Controllers\JobseekerController::class, 'postRegistration'])->name('register.post'); 
		Route::post('/registration/store', [App\Http\Controllers\JobseekerController::class, 'storeJobseekerInformation'])->name('registration.store');
		Route::get('/jobseeker/sign-in', [App\Http\Controllers\JobseekerController::class, 'showSignInForm'])->name('signin.form');
		
		Route::post('/jobseeker/login', [App\Http\Controllers\JobseekerController::class, 'loginJobseeker'])->name('jobseeker.login.submit');
	});
	
	Route::group(['middleware' => 'jobseeker.auth'], function(){
		Route::get('/dashboard',[App\Http\Controllers\JobseekerController::class, 'dashboard'])->name('jobseeker.dashboard');

		Route::post('/login',[App\Http\Controllers\JobseekerController::class, 'authenticate'])->name('jobseeker.auth');
		Route::get('/profile', [App\Http\Controllers\JobseekerController::class, 'showProfilePage'])->name('jobseeker.profile');
		Route::get('/profile', [App\Http\Controllers\JobseekerController::class, 'getJobseekerAllDetails'])->name('jobseeker.profile');
		Route::post('/logout',[App\Http\Controllers\JobseekerController::class, 'logoutJobseeker'])->name('jobseeker.logout');
		Route::post('/profile/update-personal-info',[App\Http\Controllers\JobseekerController::class, 'updatePersonalInfo'])->name('jobseeker.profile.update');
		Route::post('/profile/update-education-info',[App\Http\Controllers\JobseekerController::class, 'updateEducationInfo'])->name('jobseeker.education.update');
		Route::post('/profile/update-work-exprience-info',[App\Http\Controllers\JobseekerController::class, 'updateWorkExprienceInfo'])->name('jobseeker.workexprience.update'); 

		Route::post('/profile/update-skills-info',[App\Http\Controllers\JobseekerController::class, 'updateSkillsInfo'])->name('jobseeker.skill.update'); 
	});
});

// Recruiter Routes
Route::group(['prefix' => 'recruiter'], function() {
	Route::group(['middleware' => 'recruiter.guest'], function(){
		Route::view('/sign-in','sign-in')->name('recruiter.sign-in');
		Route::post('/login',[App\Http\Controllers\RecruiterController::class, 'authenticate'])->name('recruiter.auth');
	});
	
	Route::group(['middleware' => 'recruiter.auth'], function(){
		Route::get('/dashboard',[App\Http\Controllers\RecruiterController::class, 'dashboard'])->name('recruiter.dashboard');
	});
});



// Trainer Routes
Route::group(['prefix' => 'trainer'], function() {
	Route::group(['middleware' => 'trainer.guest'], function(){
		Route::view('/sign-in','sign-in')->name('trainer.sign-in');
		Route::post('/login',[App\Http\Controllers\TrainerController::class, 'authenticate'])->name('trainer.auth');
	});
	
	Route::group(['middleware' => 'trainer.auth'], function(){
		Route::get('/dashboard',[App\Http\Controllers\TrainerController::class, 'dashboard'])->name('trainer.dashboard');
	});
});





// Mentor Routes
Route::group(['prefix' => 'mentor'], function() {
	Route::group(['middleware' => 'mentor.guest'], function(){
	    Route::view('/sign-in','sign-in')->name('mentor.sign-in');
		Route::post('/login',[App\Http\Controllers\MentorController::class, 'authenticate'])->name('mentor.auth');
	});
	
	Route::group(['middleware' => 'mentor.auth'], function(){
		Route::get('/dashboard',[App\Http\Controllers\MentorController::class, 'dashboard'])->name('mentor.dashboard');
	});
});


// Coach Routes
Route::group(['prefix' => 'coach'], function() {
	Route::group(['middleware' => 'coach.guest'], function(){
		Route::view('/sign-in','sign-in')->name('coach.sign-in');
		Route::post('/login',[App\Http\Controllers\CoachController::class, 'authenticate'])->name('coach.auth');
	});
	
	Route::group(['middleware' => 'coach.auth'], function(){
		Route::get('/dashboard',[App\Http\Controllers\CoachController::class, 'dashboard'])->name('coach.dashboard');
	});
});



// Assessor Routes
Route::group(['prefix' => 'assessor'], function() {
	Route::group(['middleware' => 'assessor.guest'], function(){
		Route::view('/sign-in','sign-in')->name('assessor.sign-in');
		Route::post('/login',[App\Http\Controllers\AssessorController::class, 'authenticate'])->name('assessor.auth');
	});
	
	Route::group(['middleware' => 'assessor.auth'], function(){
		Route::get('/dashboard',[App\Http\Controllers\AssessorController::class, 'dashboard'])->name('assessor.dashboard');
	});
});




// Admin Routes
Route::group(['prefix' => 'admin'], function() {
	Route::group(['middleware' => 'admin.guest'], function(){
		Route::view('/', 'admin.login')->name('admin.login');
		Route::post('/login',[App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.auth');
	});
	
	Route::group(['middleware' => 'admin.auth'], function () {
    
    	// Dashboard
		Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

		// Profile, Messages, Settings
		Route::get('/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
		Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');

		// Admin Management
		Route::get('/admins/create', [App\Http\Controllers\AdminController::class, 'create'])->name('admin.create');
		Route::post('/admins/store', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.store');
		Route::get('/admins', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');

		Route::get('/admins/{id}/edit', [App\Http\Controllers\AdminController::class, 'edit'])->name('admin.edit');
		Route::post('/admins/{id}', [App\Http\Controllers\AdminController::class, 'update'])->name('admin.update');
		Route::delete('/admins/{id}/delete', [App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.destroy');
		Route::post('/admins/changeStatus', [App\Http\Controllers\AdminController::class, 'changeStatus'])->name('admin.changeStatus');


		// JObseekers
		Route::get('/jobseekers', [App\Http\Controllers\AdminController::class, 'jobseekers'])->name('admin.jobseekers');
		Route::get('/jobseekers/{id}/view', [App\Http\Controllers\AdminController::class, 'jobseekerView'])->name('admin.jobseeker.view');
		Route::post('/jobseekers/{id}', [App\Http\Controllers\AdminController::class, 'jobseekerUpdate'])->name('admin.jobseeker.update');
		Route::delete('/jobseekers/{id}/delete', [App\Http\Controllers\AdminController::class, 'jobseekerDestroy'])->name('admin.jobseeker.destroy');
		Route::post('/jobseekers/changeStatus', [App\Http\Controllers\AdminController::class, 'jobseekerChangeStatus'])->name('admin.jobseeker.changeStatus');	



		// User Roles
		Route::get('/expat', [App\Http\Controllers\AdminController::class, 'expat'])->name('admin.expat');
		Route::get('/recruiters', [App\Http\Controllers\AdminController::class, 'recruiters'])->name('admin.recruiters');
		Route::get('/trainers', [App\Http\Controllers\AdminController::class, 'trainers'])->name('admin.trainers');
		Route::get('/assessors', [App\Http\Controllers\AdminController::class, 'assessors'])->name('admin.assessors');
		Route::get('/coach', [App\Http\Controllers\AdminController::class, 'coach'])->name('admin.coach');
		Route::get('/mentors', [App\Http\Controllers\AdminController::class, 'mentors'])->name('admin.mentors');

		// Logout
		Route::get('/logout', [App\Http\Controllers\AdminController::class, 'signOut'])->name('admin.signOut');
	});
});
