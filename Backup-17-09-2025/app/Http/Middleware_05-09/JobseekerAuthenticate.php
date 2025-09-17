<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class JobseekerAuthenticate extends Middleware
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
            return route('signin.form');
        }
    }


    protected function authenticate($request, array $guards)
    {
        if ($this->auth->guard('jobseeker')->check()) {
            return $this->auth->shouldUse('jobseeker');
        }

        $this->unauthenticated($request, ['admin']);
    }

}
