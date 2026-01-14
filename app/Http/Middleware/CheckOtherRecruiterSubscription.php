<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterCompany;
use App\Models\SubscriptionPlan;

class CheckOtherRecruiterSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('recruiter')->user();
        if (!$user) {
            return redirect()->route('site.recruiter.login');
        }

        $companyData = RecruiterCompany::where('recruiter_id', $user->id)->first();

        $otherRecruiterSubscription = false;

        if ($companyData && $companyData->isSubscribtionBuy === 'yes') {
            $slug = $companyData->active_subscription_plan_slug ?? null;

            // Determine allowed total recruiters based on subscription
            $totalRecruiters = match($slug) {
                'corporate_3_recruiters' => 3,
                'corporate_4_to_6_recruiters' => 6,
                default => 0,
            };

            // Only show modal if more than 1 recruiter allowed
            // and no other recruiters have been added yet
            if ($totalRecruiters > 1 && ($companyData->recruiter_count ?? 0) < ($totalRecruiters - 1)) {
                $otherRecruiterSubscription = true;
            }
        }

        // Share with all views
        view()->share('otherRecruiterSubscription', $otherRecruiterSubscription);

        return $next($request);
    }
}
