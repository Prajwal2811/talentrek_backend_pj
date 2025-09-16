<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachRedirectIfAuthenticated
{
    
    public function handle(Request $request, Closure $next, ...$guards)
    {

        if (Auth::guard('coach')->check()) {
            return redirect()->route('coach.dashboard');
        }

        return $next($request);
    }
}
