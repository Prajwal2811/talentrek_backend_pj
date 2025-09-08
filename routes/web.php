<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});


	// Route::middleware(['jobseeker.auth', 'check.jobseeker.subscription'])->group(function () {
    
        // Route::get('/', function () {
        //     return view('site.index');
        // })->name('home');


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



        Route::get('terms-and-conditions', function () {
            return view('site.terms-and-conditions');
        })->name('terms-and-conditions');


        Route::get('privacy-policy', function () {
            return view('site.privacy-policy');
        })->name('privacy-policy');
        
	// });


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
    if (auth('jobseeker')->check() || auth('trainer')->check() || auth('coach')->check() || auth('mentor')->check() || auth('assessor')->check()) {
        return Broadcast::auth(request());
    }

    // If not authenticated, return 401 Unauthorized error
    return response()->json(['error' => 'Unauthenticated'], 401);
})->middleware(['web']);



Route::group(['middleware' => 'jobseeker.auth'], function () {
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('jobseeker.chat.send');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('jobseeker.chat.fetch');

    Route::post('/jobseeker/admin/chat/send', [ChatController::class, 'sendGroupMessage'])->name('jobseeker.group.chat.send');
	Route::get('/jobseeker/admin/chat/messages', [ChatController::class, 'fetchGroupMessages'])->name('jobseeker.group.chat.fetch');

    Route::get('/chat/unread-counts', [ChatController::class, 'getUnreadCounts']);
    Route::post('/chat/mark-as-read', [ChatController::class, 'markAsRead']);

    Route::post('/jobseeker/admin/chat/mark-as-read', [ChatController::class,'markMessagesAsRead']);
    Route::get('/chat/unread-counts', [ChatController::class, 'getUnreadCounts']);
    Route::post('/chat/mark-as-read', [ChatController::class, 'markAsRead']);
    Route::post('/chat/unread-jobseeker-counts', [ChatController::class, '   getUnreadCountsForJobseeker']);
    Route::post('/chat/unread-combined-counts', [ChatController::class, 'getCombinedUnreadCountsForJobseeker']);

 

});
// Route::group(['middleware' => 'jobseeker.auth'], function () {
//     Route::get('/chat/unread-counts', [ChatController::class, 'getUnreadCounts']);
//     Route::post('/chat/mark-as-read', [ChatController::class, 'markAsRead']);
//     Route::post('/chat/send', [ChatController::class, 'sendMessage']);
//     Route::get('/chat/messages', [ChatController::class, 'getMessages']);
//     Route::post('/jobseeker/admin/chat/send', [ChatController::class, 'sendMessage']);
//     Route::get('/jobseeker/admin/chat/messages', [ChatController::class, 'getMessages']);
// });

Route::group(['middleware' => 'trainer.auth'], function () {
    Route::post('/trainer/chat/send', [ChatController::class, 'sendMessage'])->name('trainer.chat.send');
    Route::get('/trainer/chat/messages', [ChatController::class, 'getMessages'])->name('trainer.chat.fetch');
});


Route::group(['middleware' => 'mentor.auth'], function() {
	Route::post('/mentor/chat/send', [ChatController::class, 'sendMessage'])->name('mentor.chat.send');
	Route::get('/mentor/chat/messages', [ChatController::class, 'getMessages'])->name('mentor.chat.fetch');

    Route::post('/mentor/admin/chat/send', [ChatController::class, 'sendGroupMessage'])->name('mentor.group.chat.send');
	Route::get('/mentor/admin/chat/messages', [ChatController::class, 'fetchGroupMessages'])->name('mentor.group.chat.fetch');

   
    Route::get('/mentor/unread-count', [\App\Http\Controllers\MentorController::class, 'getUnreadCount'])->name('mentor.getUnreadCount');
    Route::post('/mentor/mark-messages-read', [\App\Http\Controllers\MentorController::class, 'markMessagesAsRead'])->name('mentor.markMessagesRead');
    Route::post('mentor/mark-seen', [\App\Http\Controllers\MentorController::class, 'markMessagesSeen'])->name('mentor.markMessagesSeen');


});

Route::group(['middleware' => 'coach.auth'], function() {
	Route::post('/coach/chat/send', [ChatController::class, 'sendMessage'])->name('coach.chat.send');
	Route::get('/coach/chat/messages', [ChatController::class, 'getMessages'])->name('coach.chat.fetch');

    Route::post('/coach/admin/chat/send', [ChatController::class, 'sendGroupMessage'])->name('coach.group.chat.send');
	Route::get('/coach/admin/chat/messages', [ChatController::class, 'fetchGroupMessages'])->name('coach.group.chat.fetch');

    Route::get('/coach/unread-count', [\App\Http\Controllers\CoachController::class, 'getUnreadCount'])->name('coach.getUnreadCount');
    Route::post('/coach/mark-messages-read', [\App\Http\Controllers\CoachController::class, 'markMessagesAsRead'])->name('coach.markMessagesRead');
    Route::post('coach/mark-seen', [\App\Http\Controllers\CoachController::class, 'markMessagesSeen'])->name('coach.markMessagesSeen');


});

Route::group(['middleware' => 'assessor.auth'], function() {
	Route::post('/assessor/chat/send', [ChatController::class, 'sendMessage'])->name('assessor.chat.send');
	Route::get('/assessor/chat/messages', [ChatController::class, 'getMessages'])->name('assessor.chat.fetch');

    Route::post('/assessor/admin/chat/send', [ChatController::class, 'sendGroupMessage'])->name('assessor.group.chat.send');
	Route::get('/assessor/admin/chat/messages', [ChatController::class, 'fetchGroupMessages'])->name('assessor.group.chat.fetch');

    Route::get('/assessor/unread-count', [\App\Http\Controllers\AssessorController::class, 'getUnreadCount'])->name('assessor.getUnreadCount');
    Route::post('/assessor/mark-messages-read', [\App\Http\Controllers\AssessorController::class, 'markMessagesAsRead'])->name('assessor.markMessagesRead');
    Route::post('assessor/mark-seen', [\App\Http\Controllers\AssessorController::class, 'markMessagesSeen'])->name('assessor.markMessagesSeen');


});



Route::group(['middleware' => 'admin.auth'], function() {
	// Route::post('/admin/chat/send', [ChatController::class, 'sendGroupMessage'])->name('admin.group.chat.send');
	// Route::get('/admin/chat/messages', [ChatController::class, 'fetchGroupMessages'])->name('admin.group.chat.fetch');
    // Route::post('admin/chat/messages/markSeen', [ChatController::class, 'markMessagesAsRead'])->name('admin.group.chat.seen');

    // // Jobseekers list with unread counts
    // Route::get('/admin/jobseekers/list', [App\Http\Controllers\ChatController::class, 'getJobseekersList'])
    //     ->name('admin.jobseekers.list');

    Route::get('/admin/jobseekers/list', [ChatController::class, 'getJobseekersList'])->name('admin.jobseekers.list');
    Route::get('/admin/mentors/list', [ChatController::class, 'getMentorsList'])->name('admin.mentors.list');
    Route::get('/admin/recruiters/list', [ChatController::class, 'getRecruitersList'])->name('admin.recruiters.list');
    Route::get('/admin/coaches/list', [ChatController::class, 'getCoachesList'])->name('admin.coaches.list');
    Route::get('/admin/assessors/list', [ChatController::class, 'getAssessorsList'])->name('admin.assessors.list');
    Route::get('/admin/group/chat/fetch', [ChatController::class, 'fetchGroupMessages'])->name('admin.group.chat.fetch');
    Route::post('/admin/group/chat/send', [ChatController::class, 'sendGroupMessage'])->name('admin.group.chat.send');
    Route::post('/admin/group/chat/seen', [ChatController::class, 'markMessagesAsRead'])->name('admin.group.chat.seen');
    


});



// routes/web.php
// Route::get('/', function () {
//     return view('site.index');
// })->middleware('redirect.role.home');

Route::get('/', function () {
    return view('site.index');
})->name('home')->middleware('redirect.role.home');









