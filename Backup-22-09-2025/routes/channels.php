<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.jobseeker.{id}', function ($user, $id) {
    // Check if the user is authenticated and if the ID matches
    return auth('jobseeker')->check() && (int) auth('jobseeker')->id() === (int) $id;
});

Broadcast::channel('chat.trainer.{id}', function ($trainer, $id) {
    return auth()->guard('trainer')->check() && $trainer->id == $id;
});

Broadcast::channel('chat.mentor.{id}', function ($mentor, $id) {
    return auth()->guard('mentor')->check() && $mentor->id == $id;
});

Broadcast::channel('chat.coach.{id}', function ($coach, $id) {
    return auth()->guard('coach')->check() && $coach->id == $id;
});

Broadcast::channel('chat.assessor.{id}', function ($assessor, $id) {
    return auth()->guard('assessor')->check() && $assessor->id == $id;
});

Broadcast::channel('chat.admin.{id}', function ($admin, $id) {
    return auth()->guard('admin')->check() && $admin->id == $id;
    
});

Broadcast::channel('chat.recruiter.{id}', function ($recruiter, $id) {
    return auth()->guard('recruiter')->check() && $recruiter->id == $id;
    
});


// Broadcast::channel('chat.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// }, ['guards' => ['jobseeker', 'trainer', 'coach']]);




