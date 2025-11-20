<?php

namespace App\Domains\Transaction\DTOs\Queries;

class TransactionFilter
{
    public function __construct(
        public ?int    $userId,
        public ?int    $orderId,
        public ?int    $status,
        public ?int    $type,
        public ?string $start,
        public ?string $end,
        public ?float  $min,
        public ?float  $max
    ) {}
}
