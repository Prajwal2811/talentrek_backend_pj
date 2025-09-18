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

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


// Broadcast::channel('chat.jobseeker.{id}', function ($user, $id) {
//     // Check if the user is authenticated and if the ID matches
//     return auth('jobseeker')->check() && (int) auth('jobseeker')->id() === (int) $id;
// });

// Broadcast::channel('chat.trainer.{id}', function ($trainer, $id) {
//     return auth()->guard('trainer')->check() && $trainer->id == $id;
// });

// Broadcast::channel('chat.mentor.{id}', function ($mentor, $id) {
//     return auth()->guard('mentor')->check() && $mentor->id == $id;
// });

// Broadcast::channel('chat.coach.{id}', function ($coach, $id) {
//     return auth()->guard('coach')->check() && $coach->id == $id;
// });

// Broadcast::channel('chat.assessor.{id}', function ($assessor, $id) {
//     return auth()->guard('assessor')->check() && $assessor->id == $id;
// });

// Broadcast::channel('chat.admin.{id}', function ($admin, $id) {
//     return auth()->guard('admin')->check() && $admin->id == $id;
    
// });

// Broadcast::channel('chat.recruiter.{id}', function ($recruiter, $id) {
//     return auth()->guard('recruiter')->check() && $recruiter->id == $id;
    
// });




Broadcast::channel('chat.{role}.{id}', function ($user, $role, $id) {
    // 🔹 Web: Laravel guards ka check
    if ($role === 'jobseeker' && auth('jobseeker')->check()) {
        return (int) auth('jobseeker')->id() === (int) $id;
    }

    if ($role === 'trainer' && auth('trainer')->check()) {
        return (int) auth('trainer')->id() === (int) $id;
    }

    if ($role === 'mentor' && auth('mentor')->check()) {
        return (int) auth('mentor')->id() === (int) $id;
    }

    if ($role === 'coach' && auth('coach')->check()) {
        return (int) auth('coach')->id() === (int) $id;
    }

    if ($role === 'assessor' && auth('assessor')->check()) {
        return (int) auth('assessor')->id() === (int) $id;
    }

    if ($role === 'admin' && auth('admin')->check()) {
        return (int) auth('admin')->id() === (int) $id;
    }

    if ($role === 'recruiter' && auth('recruiter')->check()) {
        return (int) auth('recruiter')->id() === (int) $id;
    }

    // 🔹 App: auth guard nahi hota (API se sidha listen karte ho)
    // isliye direct allow kar dena
    if (request()->header('X-Login-Type') === 'app') {
        return true; // koi guard check nahi
    }

    return false;
});


// Broadcast::channel('chat.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// }, ['guards' => ['jobseeker', 'trainer', 'coach']]);




