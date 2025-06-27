<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JobseekerController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/jobseeker/sign-in', [JobseekerController::class, 'signIn']);
Route::post('/jobseeker/sign-up', [JobseekerController::class, 'signUp']);
Route::post('/jobseeker/registration', [JobseekerController::class, 'registration']);
Route::post('/jobseeker/forget-password', [JobseekerController::class, 'forgetPassword']);
Route::get('/jobseeker/verify-otp', [JobseekerController::class, 'verifyOtp']);
Route::post('/jobseeker/reset-password', [JobseekerController::class, 'resetPassword']);

