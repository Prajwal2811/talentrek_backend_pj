<?php 
// app/Services/ZoomService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomService
{
    protected $accountId;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->accountId = config('services.zoom.account_id');
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
    }

    public function getAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => $this->accountId,
            ]);

        return $response->json()['access_token'] ?? null;
    }

    public function createMeeting($topic = 'New Meeting', $startTime = null, $duration = 30)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)->post("https://api.zoom.us/v2/users/" . config('services.zoom.user_id') . "/meetings", [
            "topic" => $topic,
            "type" => 2,
            "start_time" => $startTime ?? now()->addMinutes(10)->toIso8601String(),
            "duration" => $duration,
            "timezone" => "Asia/Kolkata",
            "settings" => [
                "join_before_host" => true,
                "waiting_room" => false,
            ]
        ]);

        return $response->json();
    }
}
