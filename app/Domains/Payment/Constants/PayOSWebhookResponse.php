<?php

namespace App\Domains\Payment\Constants;

class PayOSWebhookResponse
{
    public const SUCCESS = [
        'code' => PayOSResponseCode::SUCCESS,
        'message' => 'Success',
    ];

    public const ORDER_NOT_FOUND = [
        'code' => PayOSResponseCode::ORDER_NOT_FOUND,
        'message' => 'Order not found',
    ];

    public const ORDER_ALREADY_PROCESSED = [
        'code' => PayOSResponseCode::ORDER_ALREADY_PROCESSED,
        'message' => 'Order already processed',
    ];

    public const INVALID_SIGNATURE = [
        'code' => PayOSResponseCode::INVALID_SIGNATURE,
        'message' => 'Invalid signature',
    ];

    public const UNKNOWN_ERROR = [
        'code' => PayOSResponseCode::UNKNOWN_ERROR,
        'message' => 'Unknown error',
    ];
}
