<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterCompany;
class CheckRecruiterSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('recruiter')->user();
        $companyData = RecruiterCompany::where('recruiter_id', $user->id)->first();

        if (!$user) {
            return redirect()->route('site.recruiter.login');
        }

        $activePlanId = $companyData->active_subscription_plan_id;
        $subscription = \App\Models\PurchasedSubscription::where('subscription_plan_id', $activePlanId)
                                                        ->where('company_id', $companyData->id)
                                                        ->where('user_type', 'recruiter')
                                                        ->first();
      
        $isExpired = true;
        
        if ($subscription) {
            $endDate = \Carbon\Carbon::parse($subscription->end_date);
            $isExpired = $endDate->isPast(); // true if expired
        }
        // Share to all views → user either didn’t buy OR subscription expired
        view()->share('recruiterNeedsSubscription', $companyData->isSubscribtionBuy === 'no' || $isExpired);
        return $next($request);
    }
}
