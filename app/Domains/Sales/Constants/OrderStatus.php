<?php

namespace App\Domains\Sales\Constants;

class OrderStatus
{
    public const PENDING_PAYMENT = 1;
    public const PAID            = 2;
    public const PROCESSING      = 3;
    public const SHIPPED         = 4;
    public const DELIVERED       = 5;
    public const REFUNDED        = 6;
    public const RETURNED        = 7;
    public const CANCELLED       = 8;
}

