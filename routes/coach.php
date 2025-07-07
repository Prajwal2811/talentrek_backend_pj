<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoachController;


// Coach Routes
Route::group(['prefix' => 'coach'], function() {
	Route::group(['middleware' => 'coach.guest'], function(){
		Route::view('/sign-in','sign-in')->name('coach.sign-in');
		Route::post('/login',[CoachController::class, 'authenticate'])->name('coach.auth');
	});
	
	Route::group(['middleware' => 'coach.auth'], function(){
		Route::get('/dashboard',[CoachController::class, 'dashboard'])->name('coach.dashboard');
	});
});