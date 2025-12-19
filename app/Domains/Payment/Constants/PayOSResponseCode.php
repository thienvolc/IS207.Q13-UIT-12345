<?php

namespace App\Domains\Payment\Constants;

class PayOSResponseCode
{
    public const SUCCESS = '00';
    public const INVALID_PARAM = '01';
    public const ORDER_ALREADY_PROCESSED = '02';
    public const INVALID_SIGNATURE = '03';
    public const ORDER_NOT_FOUND = '04';
    public const INVALID_API_KEY = '05';
    public const UNKNOWN_ERROR = '99';
}

