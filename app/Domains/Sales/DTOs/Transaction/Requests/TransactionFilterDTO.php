<?php

namespace App\Domains\Sales\DTOs\Transaction\Requests;

class TransactionFilterDTO
{
    public function __construct(
        public ?int $userId,
        public ?int $orderId,
        public ?int $status,
        public ?int $type,
    ) {}
}
