<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainerController;


// Trainer Routes
Route::group(['prefix' => 'trainer'], function() {
	Route::group(['middleware' => 'trainer.guest'], function(){
		Route::view('/sign-in','sign-in')->name('trainer.sign-in');
		Route::post('/login',[TrainerController::class, 'authenticate'])->name('trainer.auth');
	});
	
	Route::group(['middleware' => 'trainer.auth'], function(){
		Route::get('/dashboard',[TrainerController::class, 'dashboard'])->name('trainer.dashboard');
	});
});
