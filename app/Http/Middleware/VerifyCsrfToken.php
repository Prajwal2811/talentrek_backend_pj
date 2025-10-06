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

        'subscriptionSuccessURL',
        'subscriptionsSuccess',


        
        'subscription/payment/success',
        'subscription/payment/failure',

        'course/payment/success',
        'course/payment/failure',


        'team/course/payment/success',
        'team/course/payment/failure',


        'cart/course/payment/success',
        'cart/course/payment/failure',


        'purchase-session/payment/success',
        'purchase-session/payment/failure'

    ];
}
