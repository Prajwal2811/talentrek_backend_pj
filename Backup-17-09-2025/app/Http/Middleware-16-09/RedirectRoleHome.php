<?php

// app/Http/Middleware/RedirectRoleHome.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectRoleHome
{
    public function handle(Request $request, Closure $next)
    {
       
        if ($request->is('/')) {
            if (Auth::guard('assessor')->check()) {
                return redirect()->route('assessor.dashboard');
            }
            if (Auth::guard('mentor')->check()) {
                return redirect()->route('mentor.dashboard');
            }
            if (Auth::guard('coach')->check()) {
                return redirect()->route('coach.dashboard');
            }
            
        }

      
        return $next($request);
    }
}

