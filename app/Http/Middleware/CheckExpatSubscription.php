<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class CheckExpatSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('expat')->user();

        if (!$user) {
            return redirect()->route('site.expat.login');
        }

        // Pass subscription status to views
        view()->share('expatNeedsSubscription', $user->isSubscribtionBuy === 'no');

        return $next($request);
    }
}
