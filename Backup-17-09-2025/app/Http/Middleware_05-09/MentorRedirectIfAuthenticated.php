<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorRedirectIfAuthenticated
{
    
    public function handle(Request $request, Closure $next, ...$guards)
    {

        if (Auth::guard('mentor')->check()) {
            return redirect()->route('mentor.dashboard');
        }

        return $next($request);
    }
}
