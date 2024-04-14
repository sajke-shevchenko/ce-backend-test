<?php

return [
    'merchant' => [
        'id' => env('MERCHANT_ID'),
        'key' => env('MERCHANT_KEY'),
        'callback_url' => env('MERCHANT_CALLBACK_URL'),
        'limit' => env('MERCHANT_LIMIT'),
    ],
    'app' => [
        'id' => env('APP_ID'),
        'key' => env('APP_KEY'),
        'callback_url' => env('APP_CALLBACK_URL'),
        'limit' => env('APP_LIMIT'),
    ],
];