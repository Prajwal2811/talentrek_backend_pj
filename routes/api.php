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

Route::prefix('jobseeker')->middleware('throttle:60,1')->group(function () {
    Route::post('/sign-in', [JobseekerController::class, 'signIn']);

    Route::post('/sign-up', [JobseekerController::class, 'signUp']);
    Route::post('/registration', [JobseekerController::class, 'registration']);
    Route::post('/forget-password', [JobseekerController::class, 'forgetPassword']);
    Route::post('/verify-otp', [JobseekerController::class, 'verifyOtp']);
    Route::post('/reset-password', [JobseekerController::class, 'resetPassword']);

    //App home page API
    Route::get('/banners', [AppHomeController::class, 'bannersList']);
    Route::get('/training-programs', [AppHomeController::class, 'trainingPrograms']);
    Route::get('/training-courses', [AppHomeController::class, 'trainingCourses']);
    Route::get('/mentors', [AppHomeController::class, 'mentorsList']);
    Route::get('/testimonials', [AppHomeController::class, 'testimonialsList']);

    //Mentor Training Coach Assessor Review tags ['trainer','coach','assessor','mentor']
    Route::get('/trainingAssesorCoachMentorList/{tags}', [ExplorerController::class, 'index']);

    //Mentor Training Coach Assessor Listing
    Route::get('/trainings', [ExplorerController::class, 'trainingList']);
    Route::get('/mentorsExplorer', [ExplorerController::class, 'mentorsExplorerList']);
    Route::get('/assessor', [ExplorerController::class, 'assesserList']);
    Route::get('/coaches', [ExplorerController::class, 'coachList']);

    //Mentor Training Coach Assessor Details By Id
    Route::get('/trainingMaterialById/{trainingId}', [ExplorerController::class, 'trainingMaterialDetailById']);
    Route::get('/mentorById/{mentorId}', [ExplorerController::class, 'mentorDetailById']);
    Route::get('/trainingMaterialById/{trainingId}', [ExplorerController::class, 'trainingMaterialDetailById']);
    Route::get('/mentorById/{mentorId}', [ExplorerController::class, 'mentorDetailById']);
    Route::get('/assesserById/{assessorId}', [ExplorerController::class, 'assesserDetailById']);
    Route::get('/coachById/{coachId}', [ExplorerController::class, 'coachDetailById']);

    //Mentor Training Coach Assessor Review By Id and tags ['trainer','coach','assessor','mentor']
    Route::get('/reviewsById/{mentorId}/{tags}', [ExplorerController::class, 'reviewsDetailById']);
    Route::post('/submitReview', [ExplorerController::class, 'submitReviewByJobSeeker']);

    Route::get('/quizByTrainerId/{trainerId}', [AssesssorController::class, 'quizDetailsByTrainerId']);
    Route::post('/trainingAssesmentQuizFaq', [AssesssorController::class, 'quizFaqList']);
    Route::post('/quizNavigator', [AssesssorController::class, 'quizNavigatorList']);
    Route::post('/quizScorecard', [AssesssorController::class, 'quizScorecardResult']);
    Route::post('/submitQuiz', [AssesssorController::class, 'submitQuizAnswer']);

    Route::get('/jobSeekerProfile/{jobSeekerId}', [SeekerProfileController::class, 'jobSeekerProfileById']);
    Route::post('/updatePersonalDetails', [SeekerProfileController::class, 'updatePersonalInfoDetails']);
    Route::post('/updateEducationDetails', [SeekerProfileController::class, 'updateEducationInfoDetails']);
    Route::post('/updateWorkExperienceDetails', [SeekerProfileController::class, 'updateWorkExperienceInfoDetails']);
    Route::post('/updateSkillsDetails', [SeekerProfileController::class, 'updateSkillsInfoDetails']);
    Route::post('/updateAdditionalDetails', [SeekerProfileController::class, 'updateAdditionalInfoDetails']);
});