<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class ZoomService
{
    private function getAccessToken()
    {
        $accountId = config('services.zoom.account_id');
        $clientId = config('services.zoom.client_id');
        $clientSecret = config('services.zoom.client_secret');

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => $accountId,
            ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        Log::error('Zoom access token failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;
    }

    public function createMeeting($topic, $startTime)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error('Zoom Access Token retrieval failed.');
            return null;
        }

        $userEmail = config('services.zoom.user_email');

        $response = Http::withToken($accessToken)->post("https://api.zoom.us/v2/users/{$userEmail}/meetings", [
            'topic' => $topic,
            'type' => 2, // Scheduled meeting
            'start_time' => Carbon::parse($startTime)->toIso8601String(),
            'duration' => 30,
            'timezone' => 'Asia/Kolkata',
            'settings' => [
                'join_before_host' => true,
                'waiting_room' => false,
            ],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Zoom meeting creation failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;
    }
}
