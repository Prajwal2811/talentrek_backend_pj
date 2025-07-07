<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MentorController;


// Mentor Routes
Route::group(['prefix' => 'mentor'], function() {
	Route::group(['middleware' => 'mentor.guest'], function(){
	    Route::view('/sign-in','sign-in')->name('mentor.sign-in');
		Route::post('/login',[MentorController::class, 'authenticate'])->name('mentor.auth');
	});
	
	Route::group(['middleware' => 'mentor.auth'], function(){
		Route::get('/dashboard',[MentorController::class, 'dashboard'])->name('mentor.dashboard');
	});
});
