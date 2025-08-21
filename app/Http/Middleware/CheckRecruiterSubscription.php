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

        $activePlanId = $user->active_subscription_plan_id;

        $subscription = \App\Models\PurchasedSubscription::where('id', $activePlanId)
            ->where('user_id', $user->id)
            ->where('user_type', 'recruiter')
            ->first();

        $isExpired = true;

        if ($subscription) {
            $endDate = \Carbon\Carbon::parse($subscription->end_date);
            $isExpired = $endDate->isPast(); // true if expired
        }

        // Share to all views → user either didn’t buy OR subscription expired
        view()->share('recruiterNeedsSubscription', $user->isSubscribtionBuy === 'no' || $isExpired);

        return $next($request);
    }
}
