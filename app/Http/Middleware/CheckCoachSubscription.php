<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCoachSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('coach')->user();

        if (!$user) {
            return redirect()->route('site.coach.login');
        }

        // Pass subscription status to views
        view()->share('coachNeedsSubscription', $user->isSubscribtionBuy === 'no');

        return $next($request);
    }
}
