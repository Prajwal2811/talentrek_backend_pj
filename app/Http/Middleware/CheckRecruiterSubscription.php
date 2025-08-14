<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRecruiterSubscription
{
    
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('recruiter')->user();

        if (!$user) {
            return redirect()->route('site.recruiter.login');
        }

        // Pass subscription status to views
        view()->share('recruiterNeedsSubscription', $user->isSubscribtionBuy === 'no');

        return $next($request);
    }
}
