<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/', function () {
    return view('site.index');
})->name('home');



Route::get('training', function () {
    return view('site.training');
})->name('training');


Route::get('training-detail', function () {
    return view('site.training-detail');
})->name('training-detail');


Route::get('buy-course', function () {
    return view('site.buy-course');
})->name('buy-course');


Route::get('buy-course-for-team', function () {
    return view('site.buy-course-for-team');
})->name('buy-course-for-team');


Route::get('mentorship', function () {
    return view('site.mentorship');
})->name('mentorship');


Route::get('mentorship-details', function () {
    return view('site.mentorship-details');
})->name('mentorship-details');


Route::get('mentorship-book-session', function () {
    return view('site.mentorship-book-session');
})->name('mentorship-book-session');


Route::get('mentorship-booking-success', function () {
    return view('site.mentorship-booking-success');
})->name('mentorship-booking-success');

Route::get('assessment', function () {
    return view('site.assessment');
})->name('assessment');

Route::get('assessment-details', function () {
    return view('site.assessment-details');
})->name('assessment-details');

Route::get('assessment-book-session', function () {
    return view('site.assessment-book-session');
})->name('assessment-book-session');


Route::get('assessment-booking-success', function () {
    return view('site.assessment-booking-success');
})->name('assessment-booking-success');


Route::get('coaching', function () {
    return view('site.coaching');
})->name('coaching');


Route::get('coach-details', function () {
    return view('site.coach-details');
})->name('coach-details');

Route::get('coach-book-session', function () {
    return view('site.coach-book-session');
})->name('coach-book-session');

Route::get('coach-booking-success', function () {
    return view('site.coach-booking-success');
})->name('coach-booking-success');





Route::get('/zoom-users', function () {
    $accessToken = Http::asForm()
        ->withBasicAuth(config('services.zoom.client_id'), config('services.zoom.client_secret'))
        ->post('https://zoom.us/oauth/token', [
            'grant_type' => 'client_credentials',
        ])
        ->json()['access_token'] ?? null;

    if (!$accessToken) {
        return '❌ Failed to get access token';
    }

    // ✅ Get list of users allowed to create meetings
    $response = Http::withToken($accessToken)
        ->get('https://api.zoom.us/v2/users');

    return response()->json([
        'status' => $response->status(),
        'body' => $response->json(),
    ]);
});


Auth::routes();


// broadcasting auth route
Route::post('/broadcasting/auth', function () {
    // Check if any of the guards are authenticated
    if (auth('jobseeker')->check() || auth('trainer')->check() || auth('coach')->check()) {
        return Broadcast::auth(request());
    }

    // If not authenticated, return 401 Unauthorized error
    return response()->json(['error' => 'Unauthenticated'], 401);
})->middleware(['web']);



Route::group(['middleware' => 'jobseeker.auth'], function () {
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('jobseeker.chat.send');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('jobseeker.chat.fetch');
});

Route::group(['middleware' => 'trainer.auth'], function () {
    Route::post('/trainer/chat/send', [ChatController::class, 'sendMessage'])->name('trainer.chat.send');
    Route::get('/trainer/chat/messages', [ChatController::class, 'getMessages'])->name('trainer.chat.fetch');
});

Route::group(['middleware' => 'mentor.auth'], function () {
    Route::post('/mentor/chat/send', [ChatController::class, 'sendMessage'])->name('mentor.chat.send');
    Route::get('/mentor/chat/messages', [ChatController::class, 'getMessages'])->name('mentor.chat.fetch');
});

Route::group(['middleware' => 'coach.auth'], function () {
    Route::post('/coach/chat/send', [ChatController::class, 'sendMessage'])->name('coach.chat.send');
    Route::get('/coach/chat/messages', [ChatController::class, 'getMessages'])->name('coach.chat.fetch');
});













