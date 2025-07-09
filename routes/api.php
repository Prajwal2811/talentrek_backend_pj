<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\JobseekerController;
use App\Http\Controllers\API\Jobseeker\AssesssorController;
use App\Http\Controllers\API\Jobseeker\ExplorerController;
use App\Http\Controllers\API\Jobseeker\AppHomeController;
use App\Http\Controllers\API\Jobseeker\MyLearningController;
use App\Http\Controllers\API\Jobseeker\ProfileController;


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


Route::post('/jobseeker/sign-in', [JobseekerController::class, 'signIn']);
Route::post('/jobseeker/sign-up', [JobseekerController::class, 'signUp']);
Route::post('/jobseeker/registration', [JobseekerController::class, 'registration']);
Route::post('/jobseeker/forget-password', [JobseekerController::class, 'forgetPassword']);
Route::post('/jobseeker/verify-otp', [JobseekerController::class, 'verifyOtp']);
Route::post('/jobseeker/reset-password', [JobseekerController::class, 'resetPassword']);

Route::get('/jobseeker/banners', [AppHomeController::class, 'bannersList']);
Route::get('/jobseeker/training-programs', [AppHomeController::class, 'trainingPrograms']);
Route::get('/jobseeker/training-courses', [AppHomeController::class, 'trainingCourses']);
Route::get('/jobseeker/mentors', [AppHomeController::class, 'mentorsList']);
Route::get('/jobseeker/testimonials', [AppHomeController::class, 'testimonialsList']);

Route::get('/jobseeker/trainingAssesorCoachMentorList/{tags}', [ExplorerController::class, 'index']);
Route::get('/jobseeker/trainings', [ExplorerController::class, 'trainingList']);
Route::get('/jobseeker/mentorsExplorer', [ExplorerController::class, 'mentorsExplorerList']);
Route::get('/jobseeker/assessor', [ExplorerController::class, 'assesserList']);
Route::get('/jobseeker/coaches', [ExplorerController::class, 'coachList']);
Route::get('/jobseeker/trainingMaterialById/{trainingId}', [ExplorerController::class, 'trainingMaterialDetailById']);
Route::get('/jobseeker/mentorById/{mentorId}', [ExplorerController::class, 'mentorDetailById']);

Route::get('/jobseeker/quizByAssessorId/{assessorId}/{userId}', [AssesssorController::class, 'quizFaqList']);
Route::get('/jobseeker/quizNavigator/{assessorId}/{userId}', [AssesssorController::class, 'quizNavigatorList']);
Route::get('/jobseeker/quizScorecard/{assessorId}/{userId}', [AssesssorController::class, 'quizScorecardResult']);
Route::post('/jobseeker/submitQuiz', [AssesssorController::class, 'submitQuizAnswer']);