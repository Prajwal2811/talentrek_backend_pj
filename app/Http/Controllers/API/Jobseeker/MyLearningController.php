<?php

namespace App\Http\Controllers\API\Jobseeker;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Api\CMS;
use App\Models\Api\Assessors;
use App\Models\Api\Mentors;
use App\Models\Api\Coach;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\TrainingBatch;
use App\Models\Api\Trainers;
use App\Models\Api\Review;
use App\Models\Api\JobseekerTrainingMaterialPurchase;
use Carbon\Carbon;


class MyLearningController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function myLearningTrainingListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerTrainingMaterialPurchase::select('id', 'trainer_id', 'jobseeker_id', 'material_id', 'batch_id')
                ->where('jobseeker_id', $jobseekerId)
                ->whereNotNull('batch_id')
                ->with([
                    'batch', // load full batch info
                    'material' => function ($query) {
                        $query->select('id', 'trainer_id', 'training_title', 'training_price', 'training_offer_price', 'thumbnail_file_path as image')
                            ->with(['trainer:id,name', 'latestWorkExperience'])
                            ->withAvg('trainerReviews', 'ratings');
                    }
                ])
                ->get()
                ->map(function ($purchase) use ($today) {
                    $batch = $purchase->batch;

                    // Assign batch_status
                    if ($batch) {
                        $start = Carbon::parse($batch->start_date);
                        $end = Carbon::parse($batch->end_date);

                        if ($today->lt($start)) {
                            $purchase->batch_status = 'upcoming';
                        } elseif ($today->between($start, $end)) {
                            $purchase->batch_status = 'ongoing';
                        } else {
                            $purchase->batch_status = 'completed';
                        }
                    }

                    // Add material details if present
                    if ($purchase->material) {
                        $materials = $purchase->material;
                        $purchase->trainerName = $materials->trainer->name ?? null;
                        $purchase->training_title = $materials->training_title;
                        $purchase->image = $materials->image;
                        $purchase->job_role = $materials->latestWorkExperience->job_role ?? 'N/A';

                        $avg = $materials->trainer_reviews_avg_ratings;
                        $materials->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                        $purchase->average_rating = $materials->average_rating;

                        unset($purchase->material,$purchase->batch); // if you don’t want to expose all material
                    }

                    return $purchase;
                });
            $myTraining = $allPurchases->filter(function ($item) {
                return in_array($item->batch_status, ['ongoing', 'upcoming']);
            })->values();

            $myCompletedTraining = $allPurchases->filter(function ($item) {
                return $item->batch_status === 'completed';
            })->values();

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => 'My learning training Courses list fetched successfully.',
                'data' => [
                    'myTraining' => $myTraining,
                    'myCompletedTraining' => $myCompletedTraining
                ]
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }

    public function myLearningMentorListing($jobseekerId)
    {
        try {   
            // Fetch training materials with relationships and average ratings
            $today = Carbon::today();

            $allPurchases = JobseekerTrainingMaterialPurchase::select('id', 'trainer_id', 'jobseeker_id', 'material_id', 'batch_id')
                ->where('jobseeker_id', $jobseekerId)
                ->whereNotNull('batch_id')
                ->with([
                    'batch', // load full batch info
                    'material' => function ($query) {
                        $query->select('id', 'trainer_id', 'training_title', 'training_price', 'training_offer_price', 'thumbnail_file_path as image')
                            ->with(['trainer:id,name', 'latestWorkExperience'])
                            ->withAvg('trainerReviews', 'ratings');
                    }
                ])
                ->get()
                ->map(function ($purchase) use ($today) {
                    $batch = $purchase->batch;

                    // Assign batch_status
                    if ($batch) {
                        $start = Carbon::parse($batch->start_date);
                        $end = Carbon::parse($batch->end_date);

                        if ($today->lt($start)) {
                            $purchase->batch_status = 'upcoming';
                        } elseif ($today->between($start, $end)) {
                            $purchase->batch_status = 'ongoing';
                        } else {
                            $purchase->batch_status = 'completed';
                        }
                    }

                    // Add material details if present
                    if ($purchase->material) {
                        $materials = $purchase->material;
                        $purchase->trainerName = $materials->trainer->name ?? null;
                        $purchase->training_title = $materials->training_title;
                        $purchase->image = $materials->image;
                        $purchase->job_role = $materials->latestWorkExperience->job_role ?? 'N/A';

                        $avg = $materials->trainer_reviews_avg_ratings;
                        $materials->average_rating = $avg ? rtrim(rtrim(number_format($avg, 1, '.', ''), '0'), '.') : 0;
                        $purchase->average_rating = $materials->average_rating;

                        unset($purchase->material,$purchase->batch); // if you don’t want to expose all material
                    }

                    return $purchase;
                });
            $myTraining = $allPurchases->filter(function ($item) {
                return in_array($item->batch_status, ['ongoing', 'upcoming']);
            })->values();

            $myCompletedTraining = $allPurchases->filter(function ($item) {
                return $item->batch_status === 'completed';
            })->values();

            // Return success with data
            return response()->json([
                'success' => true,
                'message' => 'My learning training Courses list fetched successfully.',
                'data' => [
                    'myTraining' => $myTraining,
                    'myCompletedTraining' => $myCompletedTraining
                ]
            ]);

        } catch (\Exception $e) {
            // Log error if needed: Log::error($e);
            return $this->errorResponse('An error occurred while fetching training courses.', 500,[]);
        }
    }
}
