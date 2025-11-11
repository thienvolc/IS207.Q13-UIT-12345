<?php

namespace App\Dtos\Order;

class UpdateOrderStatusDto
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $status,
    ) {}

    public static function fromArray(array $data, int $orderId): self
    {
        return new self(
            orderId: $orderId,
            status: $data['status'],
        );
    }
}
