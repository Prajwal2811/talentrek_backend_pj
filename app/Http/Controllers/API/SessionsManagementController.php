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
use App\Models\Api\BookingSession;
use DB;
use Carbon\Carbon;
class SessionsManagementController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }

    public function upcomingBookedSessionsForMCA(Request $request)
    {
        try {
            // Fetch Trainers personal information
           

            $upcomingSessions = BookingSession::where('user_id', $request->user_id)->where('user_type', $request->type)->where('status', 'confirmed')
                ->whereDate('slot_date', '>=', Carbon::today())
                ->orderBy('slot_date', 'asc')
                ->get();            

            // Return combined response
            return response()->json([
                    'status' => true,
                    'message' => 'Upcoming session list for.'.$request->type,
                    'data' => $upcomingSessions
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
