<?php

return [
    'client_id' => env('PAYOS_CLIENT_ID'),
    'api_key' => env('PAYOS_API_KEY'),
    'checksum_key' => env('PAYOS_CHECKSUM_KEY'),
    'return_url' => env('APP_URL', 'http://localhost:8000') . '/checkout/result',
    'cancel_url' => env('APP_URL', 'http://localhost:8000') . '/checkout/cancel',
];
