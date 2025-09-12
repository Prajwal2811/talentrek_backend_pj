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

        // Get latest paid subscription for this jobseeker
        $subscription = \App\Models\PurchasedSubscription::where('user_id', $user->id)
            ->where('user_type', 'jobseeker')
            ->where('payment_status', 'paid')
            ->orderBy('end_date', 'desc')
            ->first();

            
        $isExpired = true;

        if ($subscription) {
            $endDate = \Carbon\Carbon::parse($subscription->end_date);
            $isExpired = $endDate->isPast(); // expired if past
        }

        // Share with all views
        // Needs subscription if (no subscription at all OR expired)
        view()->share('jobseekerNeedsSubscription', !$subscription || $isExpired);

    return $next($request);
}


}
