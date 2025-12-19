<?php

namespace App\Domains\Payment\Constants;

class VNPayResponseCode
{
    public const SUCCESS = '00';
    public const SUSPECT = '07';
    public const NOT_REGISTERED = '09';
    public const WRONG_TIMES = '10';
    public const EXPIRED = '11';
    public const LOCKED = '12';
    public const WRONG_OTP = '13';
    public const CANCELLED = '24';
    public const INSUFFICIENT_BALANCE = '51';
    public const LIMIT_EXCEEDED = '65';
    public const MAINTENANCE = '75';
    public const WRONG_PASSWORD = '79';
    public const OTHER_ERROR = '99';
}
