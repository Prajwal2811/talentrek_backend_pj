<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckJobseekerSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('jobseeker')->user();

        if (!$user) {
            return redirect()->route('site.jobseeker.login');
        }

        // Pass subscription status to views
        view()->share('jobseekerNeedsSubscription', $user->isSubscribtionBuy === 'no');

        return $next($request);
    }
}
