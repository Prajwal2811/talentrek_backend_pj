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
    'iv' => env('NEOLEAP_IV', 'PGKEYENCDECIVSPC'),
];
