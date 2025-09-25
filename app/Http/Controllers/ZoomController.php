<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ZoomController extends Controller
{
    public function createZoomMeeting($topic, $startTime, $duration)
    {
        $token = getAccessToken();
        if (!$token) {
            return ['error' => 'Failed to fetch access token'];
        }
        $email = env('ZOOM_USER_EMAIL');
        $response = Http::withToken($token)->post("https://api.zoom.us/v2/users/{$email}/meetings", [
            'topic' => $topic,
            'type' => 2,
            'start_time' => $startTime,
            'duration' => $duration,
            'timezone' => 'Asia/Kolkata',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
            ],
        ]);

        return $response->json();
    }

    public function hostAndShare()
    {
        $meeting = $this->createZoomMeeting('Demo Meeting', '2025-09-25T15:00:00Z', 30);

        if (!isset($meeting['join_url'])) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create meeting',
                'error' => $meeting
            ]);
        }

        $hostUrl = $meeting['start_url']; // host link
        $joinUrl = $meeting['join_url']; // participant link

        $emails = ['dnyan@yopmail.com'];

        foreach ($emails as $email) {
            Mail::raw("Join Zoom Meeting: $joinUrl", function($message) use ($email) {
                $message->to($email)
                        ->subject('Zoom Meeting Invitation');
            });
        }

        return response()->json([
            'status' => true,
            'message' => 'Meeting created, invitations sent!',
            'host_url' => $hostUrl,
            'join_url' => $joinUrl
        ]);
    }
}
