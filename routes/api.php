<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\JobseekerController;


//Junaid APi Controllers in Jobseeker folder
use App\Http\Controllers\API\Jobseeker\AssesssorController;
use App\Http\Controllers\API\Jobseeker\ExplorerController;
use App\Http\Controllers\API\Jobseeker\AppHomeController;
use App\Http\Controllers\API\Jobseeker\MyLearningController;
use App\Http\Controllers\API\Jobseeker\SeekerProfileController;

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

//App home page API
Route::get('/jobseeker/banners', [AppHomeController::class, 'bannersList']);
Route::get('/jobseeker/training-programs', [AppHomeController::class, 'trainingPrograms']);
Route::get('/jobseeker/training-courses', [AppHomeController::class, 'trainingCourses']);
Route::get('/jobseeker/mentors', [AppHomeController::class, 'mentorsList']);
Route::get('/jobseeker/testimonials', [AppHomeController::class, 'testimonialsList']);

//Mentor Training Coach Assessor Review tags ['trainer','coach','assessor','mentor']
Route::get('/jobseeker/trainingAssesorCoachMentorList/{tags}', [ExplorerController::class, 'index']);

//Mentor Training Coach Assessor Listing
Route::get('/jobseeker/trainings', [ExplorerController::class, 'trainingList']);
Route::get('/jobseeker/mentorsExplorer', [ExplorerController::class, 'mentorsExplorerList']);
Route::get('/jobseeker/assessor', [ExplorerController::class, 'assesserList']);
Route::get('/jobseeker/coaches', [ExplorerController::class, 'coachList']);

//Mentor Training Coach Assessor Details By Id
Route::get('/jobseeker/trainingMaterialById/{trainingId}', [ExplorerController::class, 'trainingMaterialDetailById']);
Route::get('/jobseeker/mentorById/{mentorId}', [ExplorerController::class, 'mentorDetailById']);
Route::get('/jobseeker/trainingMaterialById/{trainingId}', [ExplorerController::class, 'trainingMaterialDetailById']);
Route::get('/jobseeker/mentorById/{mentorId}', [ExplorerController::class, 'mentorDetailById']);
Route::get('/jobseeker/assesserById/{assessorId}', [ExplorerController::class, 'assesserDetailById']);
Route::get('/jobseeker/coachById/{coachId}', [ExplorerController::class, 'coachDetailById']);

//Mentor Training Coach Assessor Review By Id and tags ['trainer','coach','assessor','mentor']
Route::get('/jobseeker/reviewsById/{mentorId}/{tags}', [ExplorerController::class, 'reviewsDetailById']);

Route::get('/jobseeker/quizByTrainerId/{trainerId}', [AssesssorController::class, 'quizDetailsByTrainerId']);
Route::post('/jobseeker/trainingAssesmentQuizFaq', [AssesssorController::class, 'quizFaqList']);
Route::post('/jobseeker/quizNavigator', [AssesssorController::class, 'quizNavigatorList']);
Route::post('/jobseeker/quizScorecard', [AssesssorController::class, 'quizScorecardResult']);
Route::post('/jobseeker/submitQuiz', [AssesssorController::class, 'submitQuizAnswer']);

Route::get('/jobseeker/jobSeekerProfile/{jobSeekerId}', [SeekerProfileController::class, 'jobSeekerProfileById']);
Route::post('/jobseeker/updatePersonalDetails', [SeekerProfileController::class, 'updatePersonalInfoDetails']);
Route::post('/jobseeker/updateEducationDetails', [SeekerProfileController::class, 'updateEducationInfoDetails']);
Route::post('/jobseeker/updateWorkExperienceDetails', [SeekerProfileController::class, 'updateWorkExperienceInfoDetails']);
Route::post('/jobseeker/updateSkillsDetails', [SeekerProfileController::class, 'updateSkillsInfoDetails']);
Route::post('/jobseeker/updateAdditionalDetails', [SeekerProfileController::class, 'updateAdditionalInfoDetails']);