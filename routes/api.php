<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AppAuthenticationController;

use App\Http\Controllers\API\JobseekerController;
use App\Http\Controllers\API\TrainerController;
use App\Http\Controllers\API\CoachController;
use App\Http\Controllers\API\MentorController;
use App\Http\Controllers\API\AssessorController;

use App\Http\Controllers\API\Training\TrainerProfileController;
use App\Http\Controllers\API\Mentor\MentorProfileController;
use App\Http\Controllers\API\Coach\CoachProfileController;
use App\Http\Controllers\API\Assessor\AssessorProfileController;

//Junaid APi Controllers in Jobseeker folder
use App\Http\Controllers\API\Jobseeker\AssesssorController;
use App\Http\Controllers\API\Jobseeker\ExplorerController;
use App\Http\Controllers\API\Jobseeker\AppHomeController;
use App\Http\Controllers\API\Jobseeker\MyLearningController;
use App\Http\Controllers\API\Jobseeker\SeekerProfileController;
use App\Http\Controllers\API\Jobseeker\CartManagementController;

use App\Http\Controllers\API\Training\TrainingController;
use App\Http\Controllers\API\Training\TrainingDashboardController;

use App\Http\Controllers\API\SlotManagementController;
use App\Http\Controllers\API\SessionsManagementController;
use App\Http\Controllers\API\ReviewManagementController;

use App\Http\Controllers\API\LanguageController;



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

Route::prefix('authentication')->middleware('throttle:60,1')->group(function () {
    Route::post('/sign-in', [AppAuthenticationController::class, 'signIn']);
    Route::post('/sign-up', [AppAuthenticationController::class, 'signUp']);
    Route::post('/registration', [AppAuthenticationController::class, 'registration']);
    Route::post('/forget-password', [AppAuthenticationController::class, 'forgetPassword']);
    Route::post('/verify-otp', [AppAuthenticationController::class, 'verifyOtp']);
    Route::post('/reset-password', [AppAuthenticationController::class, 'resetPassword']);
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
    Route::get('/trainingMaterialById/{trainingId}/{jobSeekerId}', [ExplorerController::class, 'trainingMaterialDetailById']);
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

    Route::get('/myLearningTraining/{jobseekerId}', [MyLearningController::class, 'myLearningTrainingListing']);
    Route::get('/myLearningMentor/{jobseekerId}', [MyLearningController::class, 'myLearningMentorListing']);
    Route::get('/myLearningCoach/{jobseekerId}', [MyLearningController::class, 'myLearningCoachListing']);
    Route::get('/myLearningAssessor/{jobseekerId}', [MyLearningController::class, 'myLearningAssessorListing']);

    Route::post('/jobSeekerConsultantSession', [MyLearningController::class, 'jobSeekerConsultationSession']);
    Route::post('/jobSeekerBookASession', [MyLearningController::class, 'jobSeekerBookAConsultationSession']);
    
    Route::post('/addToCart', [CartManagementController::class, 'addToCartByJobseeker']);
    Route::post('/removeCartItem', [CartManagementController::class, 'removeCartItemByJobseeker']);
    Route::get('/viewCartItem/{jobseekerId}', [CartManagementController::class, 'viewCartItemByJobseeker']);
});

Route::prefix('trainer')->middleware('throttle:60,1')->group(function () {
    Route::post('/sign-in-trainer', [TrainerController::class, 'signIn']);

    Route::post('/sign-up-trainer', [TrainerController::class, 'signUp']);
    Route::post('/registration-trainer', [TrainerController::class, 'registration']);
    Route::post('/forget-password-trainer', [TrainerController::class, 'forgetPassword']);
    Route::post('/verify-otp-trainer', [TrainerController::class, 'verifyOtpTrainer']);
    Route::post('/reset-password-trainer', [TrainerController::class, 'resetPassword']);
    
    Route::get('/dashboardCounts/{trainerId}', [TrainingDashboardController::class, 'dashboardCourseJobSeekerCounts']);
    Route::get('/upcomingSessionCount/{trainerId}', [TrainingDashboardController::class, 'upcomingSessionsCounts']);
    Route::get('/enrolledJobSeekerList/{trainerId}', [TrainingDashboardController::class, 'enrolledJobSeekerListing']);
    Route::get('/todaysSessionMaterials/{trainerId}', [TrainingDashboardController::class, 'todaysSessionBatchesMaterials']);

    Route::post('/recordedCoursesAddEdit', [TrainingController::class, 'saveTrainingRecordedData']);
    Route::get('/recordedCoursesEdit/{trainerMaterialId}', [TrainingController::class, 'editTrainingRecordedData']);

    Route::post('/onlineOfflineCoursesAddEdit', [TrainingController::class, 'saveTrainingOnlineData']);
    Route::get('/onlineOfflineCoursesEdit/{trainerMaterialId}', [TrainingController::class, 'editTrainingOnlineData']);

    Route::post('/assessmentSave', [TrainingController::class, 'assessmentStore']);
    Route::get('/trainingList/{trainerId}', [TrainingController::class, 'trainingAllList']);
    Route::get('/recordedTrainingList/{trainerId}', [TrainingController::class, 'recordedTrainingAllList']);
    Route::get('/assessmentList/{trainerId}', [TrainingController::class, 'assessmentLists']);
    Route::post('/assignCourse', [TrainingController::class, 'assignTrainingMaterialToCourse']);
    Route::get('/batchList/{trainerId}', [TrainingController::class, 'trainingBatchList']);
    Route::get('/deleteAdditionalFile/{type}/{trainerId}', [TrainingController::class, 'deleteAdditionalFile']);
    
    Route::get('/trainerProfile/{trainerId}', [TrainerProfileController::class, 'trainersProfileById']);
    Route::post('/updatePersonalDetails', [TrainerProfileController::class, 'updatePersonalInfoDetails']);
    Route::post('/updateEducationDetails', [TrainerProfileController::class, 'updateEducationInfoDetails']);
    Route::post('/updateWorkExperienceDetails', [TrainerProfileController::class, 'updateWorkExperienceInfoDetails']);
    Route::post('/updateSkillsDetails', [TrainerProfileController::class, 'updateSkillsInfoDetails']);
    Route::post('/updateAdditionalDetails', [TrainerProfileController::class, 'updateAdditionalInfoDetails']);    

});

Route::prefix('mentor')->middleware('throttle:60,1')->group(function () {
    Route::post('/sign-in-mentor', [MentorController::class, 'signIn']);
    Route::post('/sign-up-mentor', [MentorController::class, 'signUp']);
    Route::post('/registration-mentor', [MentorController::class, 'registration']);
    Route::post('/forget-password-mentor', [MentorController::class, 'forgetPassword']);
    Route::post('/verify-otp-mentor', [MentorController::class, 'verifyOtp']);
    Route::post('/reset-password-mentor', [MentorController::class, 'resetPassword']);

    Route::get('/mentorProfile/{trainerId}', [MentorProfileController::class, 'mentorProfileById']);
    Route::post('/updatePersonalDetails', [MentorProfileController::class, 'updatePersonalInfoDetails']);
    Route::post('/updateEducationDetails', [MentorProfileController::class, 'updateEducationInfoDetails']);
    Route::post('/updateWorkExperienceDetails', [MentorProfileController::class, 'updateWorkExperienceInfoDetails']);
    Route::post('/updateSkillsDetails', [MentorProfileController::class, 'updateSkillsInfoDetails']);
    Route::post('/updateAdditionalDetails', [MentorProfileController::class, 'updateAdditionalInfoDetails']);
});

Route::prefix('coach')->middleware('throttle:60,1')->group(function () {
    Route::post('/sign-in-coach', [CoachController::class, 'signIn']);
    Route::post('/sign-up-coach', [CoachController::class, 'signUp']);
    Route::post('/registration-coach', [CoachController::class, 'registration']);
    Route::post('/forget-password-coach', [CoachController::class, 'forgetPassword']);
    Route::post('/verify-otp-coach', [CoachController::class, 'verifyOtp']);
    Route::post('/reset-password-coach', [CoachController::class, 'resetPassword']);

    Route::get('/coachProfile/{trainerId}', [CoachProfileController::class, 'coachProfileById']);
    Route::post('/updatePersonalDetails', [CoachProfileController::class, 'updatePersonalInfoDetails']);
    Route::post('/updateEducationDetails', [CoachProfileController::class, 'updateEducationInfoDetails']);
    Route::post('/updateWorkExperienceDetails', [CoachProfileController::class, 'updateWorkExperienceInfoDetails']);
    Route::post('/updateSkillsDetails', [CoachProfileController::class, 'updateSkillsInfoDetails']);
    Route::post('/updateAdditionalDetails', [CoachProfileController::class, 'updateAdditionalInfoDetails']);
});

Route::prefix('assessor')->middleware('throttle:60,1')->group(function () {
    Route::post('/sign-in-assessor', [AssessorController::class, 'signIn']);
    Route::post('/sign-up-assessor', [AssessorController::class, 'signUp']);
    Route::post('/registration-assessor', [AssessorController::class, 'registration']);
    Route::post('/forget-password-assessor', [AssessorController::class, 'forgetPassword']);
    Route::post('/verify-otp-assessor', [AssessorController::class, 'verifyOtp']);
    Route::post('/reset-password-assessor', [AssessorController::class, 'resetPassword']);

    Route::get('/assessorProfile/{trainerId}', [AssessorProfileController::class, 'assessorProfileById']);
    Route::post('/updatePersonalDetails', [AssessorProfileController::class, 'updatePersonalInfoDetails']);
    Route::post('/updateEducationDetails', [AssessorProfileController::class, 'updateEducationInfoDetails']);
    Route::post('/updateWorkExperienceDetails', [AssessorProfileController::class, 'updateWorkExperienceInfoDetails']);
    Route::post('/updateSkillsDetails', [AssessorProfileController::class, 'updateSkillsInfoDetails']);
    Route::post('/updateAdditionalDetails', [AssessorProfileController::class, 'updateAdditionalInfoDetails']);
});

Route::post('/createBookingSlot', [SlotManagementController::class, 'createBookingSlotForMentorAssessorCoach']);
Route::post('/markBookingSlotDateUnavailable', [SlotManagementController::class, 'markBookingSlotDateUnavailableForMCA']);
Route::post('/showBookingSlotDetailsByDate', [SlotManagementController::class, 'showBookingSlotDetailsByDateForMCA']);
Route::get('/deleteBookingSlotById/{bookingSlotId}', [SlotManagementController::class, 'deleteBookingSlotDetailsByIdForMCA']);
Route::post('/updateBookingSlotById', [SlotManagementController::class, 'updateBookingSlotDetailsByIdForMCA']);
Route::post('/markSlotUnavailableByDate', [SlotManagementController::class, 'markSlotUnavailableByDateForMCA']);
Route::post('/cancelSessionByRole', [SlotManagementController::class, 'cancelSessionByRoleForMCA']);
Route::post('/rescheduleSessionByRole', [SlotManagementController::class, 'rescheduleSessionByRoleForMCA']);
Route::post('/calenderUnavailableDates', [SlotManagementController::class, 'calenderUnavailableDatesForMCA']);

Route::post('/upcomingBookedSessions', [SessionsManagementController::class, 'upcomingBookedSessionsForMCA']);
Route::post('/cancelledBookedSessions', [SessionsManagementController::class, 'cancelledBookedSessionsForMCA']);
Route::post('/completedBookedSessions', [SessionsManagementController::class, 'completedBookedSessionsForMCA']);
Route::post('/totalBookedSessions', [SessionsManagementController::class, 'totalBookedSessionsCountsForMCA']);

Route::post('/reviewsDetailsById', [ReviewManagementController::class, 'reviewsDetailsByMCAIds']);

Route::get('/translateText', [LanguageController::class, 'index']);
