<?php

namespace App\Http\Controllers\API\Training;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Api\Trainers;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\JobseekerTrainingMaterialPurchase;
use App\Models\Api\TrainingBatch;

use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;
class TrainingDashboardController extends Controller
{
    public function dashboardCourseJobSeekerCounts($trainerId)
    {        
        try {                       
            // Courses count
            $totalCourses = TrainingMaterial::where('trainer_id', $trainerId)->count();

            // Jobseeker count who purchased any course of that trainer
            $totalJobseekers = JobseekerTrainingMaterialPurchase::where('trainer_id', $trainerId)->distinct('jobseeker_id')->count('jobseeker_id');

            // Return response
            return response()->json([
                'success' => true,
                'data' => [
                    'total_courses' => $totalCourses,
                    'total_jobseekers' => $totalJobseekers,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving training.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function upcomingSessionsCounts($trainerId)
    {        
        try {                       
           // Jobseeker count who purchased any course of that trainer
            $totalSessions = JobseekerTrainingMaterialPurchase::where('trainer_id', $trainerId)->count();
            $completedSession = $pendingSession = 1 ;
            // Return response
            return response()->json([
                'success' => true,
                'data' => [
                    'totalSessions' => $totalSessions,
                    'completedSession' => $completedSession,
                    'pendingSession' => $pendingSession,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving training.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enrolledJobSeekerListing($trainerId)
    {        
        try {                       
           $enrolledJobseekers = JobseekerTrainingMaterialPurchase::select('id','enrolmentId',)->where('trainer_id', $trainerId)->distinct('jobseeker_id')
                ->with('WorkExperience','jobseeker')
                ->get()
                ->map(function ($item) {
                    // Get the most recent job_role based on nearest end_to (null means current)
                    $mostRecentExp = $item->WorkExperience->sortByDesc(function ($exp) {
                        return \Carbon\Carbon::parse($exp->end_to ?? now())->timestamp;
                    })->first();
                    $item->recent_job_role = $mostRecentExp ? $mostRecentExp->job_role : null;
                    $item->jobseekerName = $item->jobseeker?->name ;
                    unset( $item->WorkExperience,$item->jobseeker);
                    return $item;
                });
            // Return response
            return response()->json([
                'success' => true,
                'data' => [
                    'enrolledJobseekers' => $enrolledJobseekers,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving training.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function todaysSessionBatchesMaterials($trainerId)
    {        
        try { 
            $today = Carbon::today();                      
           // Jobseeker count who purchased any course of that trainer
           $sessions = TrainingBatch::select('id', 'trainer_id', 'training_material_id', 'batch_no', 'duration')
            ->with('trainingMaterial:id,id,training_title') // Load only required fields
            ->where('trainer_id', $trainerId)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get()
            ->map(function ($session) {
                $session->training_title = $session->trainingMaterial ? $session->trainingMaterial->training_title : null;
                unset($session->trainingMaterial); // Optional: remove the full relation if only title needed
                return $session;
            });

           
            // Return response
            return response()->json([
                'success' => true,
                'data' => $sessions                
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving training.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}