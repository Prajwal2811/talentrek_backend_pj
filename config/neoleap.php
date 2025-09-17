<?php

return [
    'merchant_id'   => env('NEOLEAP_MERCHANT_ID'),
    'tranportal_id'   => env('NEOLEAP_TRANSPORTAL_ID'),
    'tranportal_password'   => env('NEOLEAP_TRANSPORTAL_PASSWORD'),
    'terminal_id'   => env('NEOLEAP_TERMINAL_ID'),
    'secret_key'    => env('NEOLEAP_SECRET_KEY'), // usually 3DES/AES key
    'payment_url'   => 'https://securepayments.neoleap.com.sa/pg/payment/hosted.htm',
    'callback_url'  => env('APP_URL').env('NEOLEAP_SUCCESS_URL '),
    'return_url'    => env('APP_URL').env('NEOLEAP_SUCCESS_URL'),
    'success_booking_session_url'    => env('APP_URL').env('NEOLEAP_BOOKING_SESSION_SUCCESS_URL'),
    'success_subscription_mobile_url'    => env('APP_URL').env('NEOLEAP_SUBSCRIPTION_MOBILE_SUCCESS_URL'),
    'success_material_purchase_url'    => env('APP_URL').env('NEOLEAP_MATERIAL_PURCHASE_SUCCESS_URL'),



    
     // Subscription-specific
    'subscription_success_url' => env('APP_URL') . env('NEOLEAP_SUBSCRIPTION_SUCCESS_URL'),
    'subscription_failure_url' => env('APP_URL') . env('NEOLEAP_SUBSCRIPTION_FAILURE_URL'),
];
