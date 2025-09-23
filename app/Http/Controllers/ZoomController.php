<?php 
namespace App\Http\Controllers;

use App\Mail\ZoomMeetingMail;
use App\Services\ZoomMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ZoomController extends Controller
{
    public function createAndSend(Request $request, ZoomService $zoomService)
    {
        // Validate input
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
            'topic' => 'required|string',
        ]);

        // Create Meeting
        $meeting = $zoomService->createMeeting($request->topic);

        // Send Email
        foreach ($request->emails as $email) {
            Mail::to($email)->send(new ZoomMeetingMail($meeting));
        }

        return response()->json([
            'status' => 'success',
            'meeting' => $meeting
        ]);
    }
}
