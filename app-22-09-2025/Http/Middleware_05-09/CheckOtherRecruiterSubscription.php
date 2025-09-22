<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOtherRecruiterSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('recruiter')->user();

        if (!$user) {
            return redirect()->route('site.recruiter.login');
        }

        // Pass subscription status to views
        view()->share('otherRecruiterSubscription', $user->active_subscription_plan_id === null && $user->isSubscribtionBuy === 'yes');

        return $next($request);
    }
}
