<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckTrainerSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('trainer')->user();

        if (!$user) {
            return redirect()->route('site.trainer.login');
        }

        // Pass subscription status to views
        view()->share('trainerNeedsSubscription', $user->isSubscribtionBuy === 'no');

        return $next($request);
    }

}
