<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchasedSubscription;
use Carbon\Carbon;

class CheckRecruiterSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('recruiter')->user();

        if (!$user) {
            return redirect()->route('site.recruiter.login');
        }

        // Get latest paid subscription for this recruiter
        $subscription = PurchasedSubscription::where('user_id', $user->id)
            ->where('user_type', 'recruiter')
            ->where('payment_status', 'paid')
            ->orderBy('end_date', 'desc')
            ->first();

        $isExpired = true;

        if ($subscription) {
            $endDate   = Carbon::parse($subscription->end_date);
            $isExpired = $endDate->isPast(); // true if expired
        }

        // Needs subscription if: no subscription OR expired
        $needsSubscription = !$subscription || $isExpired;

        // Share with all recruiter views
        view()->share('recruiterNeedsSubscription', $needsSubscription);

        return $next($request);
    }
}
