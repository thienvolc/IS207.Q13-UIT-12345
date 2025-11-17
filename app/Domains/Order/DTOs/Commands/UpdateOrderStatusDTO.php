<?php

namespace App\Domains\Order\DTOs\Commands;

readonly class UpdateOrderStatusDTO
{
    public function __construct(
        public int $orderId,
        public int $status,
    ) {}
}
