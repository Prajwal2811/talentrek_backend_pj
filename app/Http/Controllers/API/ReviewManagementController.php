<?php

namespace App\Http\Controllers\API;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

use App\Models\Api\TrainingExperience;
use App\Models\Api\Mentors;
use App\Models\Api\EducationDetails;
use App\Models\Api\WorkExperience;
use App\Models\Api\TrainingMaterialsDocument;
use App\Models\Api\AdditionalInfo;
use App\Models\Api\BookingSlotUnavailableDate;
use App\Models\Api\Review;
use DB;
use Carbon\Carbon;
class ReviewManagementController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function reviewsDetailsByMCAIds(Request $request)
    {
        try {
            // Fetch Trainers personal information
            $reviews = Review::select(
                '*',
                DB::raw("
                    CASE
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'Last 1 Week'
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 'Last 1 Month'
                        ELSE 'Older'
                    END AS review_period
                ")
            )->with('jobSeekerInfo:document_path','jobSeekerInfoName:id,name')
            ->orderBy('created_at', 'desc')
            ->get();      
                
            // Step 1: Get latest 10 reviews
            $current10 = Review::select(
                'id',
                'jobseeker_id',
                'user_id',
                'reviews',
                'ratings',
                'user_type',
                'created_at',
                DB::raw("
                    CASE
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'Last 1 Week'
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 'Last 1 Month'
                        ELSE 'Older'
                    END AS review_period
                ")
            )->with('jobSeekerInfoName','jobSeekerInfo:id,user_id,document_path')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($review) {
                return [
                    'id'             => $review->id,
                    'jobseeker_id'   => $review->jobseeker_id,
                    'user_id'        => $review->user_id,
                    'reviews'        => $review->reviews,
                    'ratings'        => $review->ratings,
                    'user_type'      => $review->user_type,
                    'created_at'     => $review->created_at,
                    'review_period'  => $review->review_period,
                    'job_seeker_name' => optional($review->jobSeekerInfoName)->name, // Adjust as needed
                    'image'  => optional($review->jobSeekerInfo)->document_path,
                ];
            });

            // Step 2: Get reviews from last 1 week excluding those in top 10
            $lastWeek = Review::select(
                '*',
                DB::raw("
                    CASE
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'Last 1 Week'
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 'Last 1 Month'
                        ELSE 'Older'
                    END AS review_period
                ")
            )->with('jobSeekerInfoName:id,name','jobSeekerInfo:document_path')
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 7 DAY)'))
            ->orderBy('created_at', 'desc')
            ->skip(10)
            ->take(50) // adjust as needed
            ->get()
            ->map(function ($review) {
                return [
                    'id'             => $review->id,
                    'jobseeker_id'   => $review->jobseeker_id,
                    'user_id'        => $review->user_id,
                    'reviews'        => $review->reviews,
                    'ratings'        => $review->ratings,
                    'user_type'      => $review->user_type,
                    'created_at'     => $review->created_at,
                    'review_period'  => $review->review_period,
                    'job_seeker_name' => optional($review->jobSeekerInfoName)->name, // Adjust as needed
                    'image'  => optional($review->jobSeekerInfo)->document_path,
                ];
            });

            // Step 3: Get older reviews (more than 7 days old)\
            $older = Review::select(
                '*',
                DB::raw("
                    CASE
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'Last 1 Week'
                        WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 'Last 1 Month'
                        ELSE 'Older'
                    END AS review_period
                ")
            )->with('jobSeekerInfoName:id,name','jobSeekerInfo:document_path')
            ->where('created_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 7 DAY)'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($review) {
                return [
                    'id'             => $review->id,
                    'jobseeker_id'   => $review->jobseeker_id,
                    'user_id'        => $review->user_id,
                    'reviews'        => $review->reviews,
                    'ratings'        => $review->ratings,
                    'user_type'      => $review->user_type,
                    'created_at'     => $review->created_at,
                    'review_period'  => $review->review_period,
                    'job_seeker_name' => optional($review->jobSeekerInfoName)->name, // Adjust as needed
                    'image'  => optional($review->jobSeekerInfo)->document_path,
                ];
            });       

            return response()->json([
                'status' => true,
                'message' => 'Review list for.',
                //'data' => $reviews,
                'recent' => $current10,
                'last_1_week' => $lastWeek,
                'older' => $older,
            ]);
            

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function cancelledBookedSessionsForMCA(Request $request)
    {
        try {
            $cancelledSessions = BookingSession::where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'cancelled')
                ->orderBy('slot_date', 'asc')
                ->get();            

            // Return combined response
            return response()->json([
                    'status' => true,
                    'message' => 'Cancelled session list for '.$request->type,
                    'data' => $cancelledSessions
                ]);   

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }
    public function completedBookedSessionsForMCA(Request $request)
    {
        try {
            $confirmedSessions = BookingSession::where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'confirmed')
                ->whereDate('slot_date', '<', Carbon::today())                
                ->orderBy('slot_date', 'asc')
                ->get();            

            // Return combined response
            return response()->json([
                    'status' => true,
                    'message' => 'Completed session list for '.$request->type,
                    'data' => $confirmedSessions
                ]);  

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch Trainer profile.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

}
