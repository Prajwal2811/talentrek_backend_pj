<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'success',   // your responseURL path
        'successBookingSlot',   // your errorURL path
        'successBookingSession',
        'processSubscriptionPayment',
        'successSubscription',
        'failureSubscription',
        'subscription/payment/success',
        'subscription/payment/failure',

        'course/payment/success',
        'course/payment/failure',


        'subscriptionSuccessURL',
        'subscriptionsSuccess',

        'session/payment/success',
        'session/payment/failure'

    ];
}
