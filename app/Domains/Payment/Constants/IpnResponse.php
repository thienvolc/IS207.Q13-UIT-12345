<?php

namespace App\Domains\Payment\Constants;

class IpnResponse
{
    public const SUCCESS = ['RspCode' => '00', 'Message' => 'Confirm Success'];
    public const SIGNATURE_FAILED = ['RspCode' => '97', 'Message' => 'Invalid Checksum'];
    public const ORDER_NOT_FOUND = ['RspCode' => '01', 'Message' => 'Order not found'];
    public const ORDER_ALREADY_PAID = ['RspCode' => '02', 'Message' => 'Order already confirmed'];
    public const INVALID_AMOUNT = ['RspCode' => '04', 'Message' => 'Invalid Amount'];
    public const UNKNOWN_ERROR = ['RspCode' => '99', 'Message' => 'Unknown error'];
}
