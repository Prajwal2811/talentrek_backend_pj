<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class TrainerAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('trainer.login');
        }
    }


    protected function authenticate($request, array $guards)
    {
        if ($this->auth->guard('trainer')->check()) {
            return $this->auth->shouldUse('trainer');
        }

        $this->unauthenticated($request, ['trainer']);
    }

}
