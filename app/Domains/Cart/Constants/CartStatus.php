<?php

namespace App\Domains\Cart\Constants;

class CartStatus
{
    public const ACTIVE               = 1;
    public const CHECKOUT_IN_PROGRESS = 2;
    public const CHECKED_OUT          = 3;
    public const COMPLETED            = 4;
    public const CANCELLED            = 5;
}
