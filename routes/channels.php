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
    return (int) auth('jobseeker')->id() === (int) $id;
});

// Broadcast::channel('chat.trainer.{id}', function ($user, $id) {
//     return (int) auth('trainer')->id() === (int) $id;
// });

Broadcast::channel('chat.trainer.{id}', function ($trainer, $id) {
    return auth()->guard('trainer')->check() && $trainer->id == $id;
});





