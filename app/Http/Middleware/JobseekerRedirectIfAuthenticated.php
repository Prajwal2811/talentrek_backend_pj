<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobseekerRedirectIfAuthenticated
{
    
    public function handle(Request $request, Closure $next, ...$guards)
    {

        if (Auth::guard('jobseeker')->check()) {
            return redirect()->route('jobseeker.dashboard');
        }

        return $next($request);
    }
}
