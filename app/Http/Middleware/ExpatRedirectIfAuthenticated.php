<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpatRedirectIfAuthenticated
{
    
    public function handle(Request $request, Closure $next, ...$guards)
    {

        if (Auth::guard('expat')->check()) {
            return redirect()->route('expat.profile');
        }

        return $next($request);
    }
}
