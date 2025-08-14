<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class CheckAssessorSubscription
{
   public function handle($request, Closure $next)
    {
        $user = Auth::guard('assessor')->user();

        if (!$user) {
            return redirect()->route('site.assessor.login');
        }

        // Pass subscription status to views
        view()->share('assessorNeedsSubscription', $user->isSubscribtionBuy === 'no');

        return $next($request);
    }
}
