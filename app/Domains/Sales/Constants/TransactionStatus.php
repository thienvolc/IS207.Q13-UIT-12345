<?php

namespace App\Domains\Sales\Constants;

class TransactionStatus
{
    public const INITIATED = 1;
    public const PENDING   = 2;
    public const SUCCESS   = 3;
    public const FAILED    = 4;
    public const CANCELLED = 5;
    public const EXPIRED   = 6;
}

