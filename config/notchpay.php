<?php

return [
    'public_key' => env('NOTCHPAY_PUBLIC_KEY'),
    'private_key' => env('NOTCHPAY_PRIVATE_KEY'),
    'webhook_secret' => env('NOTCHPAY_WEBHOOK_SECRET'),
    'currency' => env('NOTCHPAY_CURRENCY', 'XAF'),
    'sandbox' => env('APP_ENV') === 'production' ? false : true, // Sandbox auto en local
];