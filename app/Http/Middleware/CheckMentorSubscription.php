<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckMentorSubscription
{
public function handle($request, Closure $next)
    {
        $user = Auth::guard('mentor')->user();

        if (!$user) {
            return redirect()->route('site.mentor.login');
        }

        // Pass subscription status to views
        view()->share('mentorNeedsSubscription', $user->isSubscribtionBuy === 'no');

        return $next($request);
    }
}
