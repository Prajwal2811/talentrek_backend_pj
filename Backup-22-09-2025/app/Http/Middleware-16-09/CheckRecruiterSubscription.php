<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Recruiters;
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

        $companyData = Recruiters::select('recruiters.*', 'recruiters_company.*')
                                ->where('recruiters.id', $user->id)
                                ->join('recruiters_company', 'recruiters.company_id', '=', 'recruiters_company.id')
                                ->first();

        // If recruiter company not found -> needs subscription
        if (!$companyData) {
            view()->share('recruiterNeedsSubscription', true);
            return $next($request);
        }

        $activeSubscription = null;
        if ($companyData->active_subscription_plan_id) {
            $activeSubscription = PurchasedSubscription::find($companyData->active_subscription_plan_id);
        }

        $isExpired = true;
        if ($activeSubscription) {
            $endDate   = Carbon::parse($activeSubscription->end_date);
            $isExpired = $endDate->isPast(); // true if expired
        }

        // Recruiter needs subscription if:
        // - No purchase OR expired
        $needsSubscription = $companyData->isSubscribtionBuy === 'no' || $isExpired;

        view()->share('recruiterNeedsSubscription', $needsSubscription);

        return $next($request);
    }
}
