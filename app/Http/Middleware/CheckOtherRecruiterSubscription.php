<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterCompany;

class CheckOtherRecruiterSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('recruiter')->user();
        if (!$user) {
            return redirect()->route('site.recruiter.login');
        }

        $companyData = RecruiterCompany::where('recruiter_id', $user->id)->first();

        // Show "Add Other Recruiter" modal only if subscription is bought
        // but no recruiters have been added yet
        $otherRecruiterSubscription = $companyData 
            && $companyData->isSubscribtionBuy === 'yes'
            && $companyData->recruiter_count === null;

        view()->share('otherRecruiterSubscription', $otherRecruiterSubscription);

        return $next($request);
    }
}
