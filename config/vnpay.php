<?php

return [
    'tmn_code' => env('VNPAY_TMN_CODE', ''),
    'hash_secret' => env('VNPAY_HASH_SECRET', ''),
    'pay_url' => env('VNPAY_PAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'refund_url' => env('VNPAY_REFUND_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
    'return_url' => env('APP_URL', 'http://localhost:8000') . '/checkout/result',
    'payment_timeout' => env('VNPAY_PAYMENT_TIMEOUT', 15),
    'version' => '2.1.0',
    'command' => 'pay',
    'currency' => 'VND',
    'locale' => 'vn',
    'order_type' => 'other',
];
