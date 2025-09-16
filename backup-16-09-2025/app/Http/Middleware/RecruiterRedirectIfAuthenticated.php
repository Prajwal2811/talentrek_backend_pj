<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruiterRedirectIfAuthenticated
{
    
    public function handle(Request $request, Closure $next, ...$guards)
    {

        if (Auth::guard('recruiter')->check()) {
            return redirect()->route('recruiter.dashboard');
        }

        return $next($request);
    }
}
