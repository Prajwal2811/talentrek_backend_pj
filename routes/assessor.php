<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessorController;



// Assessor Routes
Route::group(['prefix' => 'assessor'], function() {
	Route::group(['middleware' => 'assessor.guest'], function(){
		Route::view('/sign-in','sign-in')->name('assessor.sign-in');
		Route::post('/login',[AssessorController::class, 'authenticate'])->name('assessor.auth');
	});
	
	Route::group(['middleware' => 'assessor.auth'], function(){
		Route::get('/dashboard',[AssessorController::class, 'dashboard'])->name('assessor.dashboard');
	});
});
