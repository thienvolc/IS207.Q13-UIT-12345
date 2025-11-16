<?php

namespace App\Domains\Sales\DTOs\Order\Requests;

readonly class UpdateOrderStatusDTO
{
    public function __construct(
        public int $orderId,
        public int $status,
    ) {}

    public static function fromArray(array $data, int $orderId): self
    {
        return new self(
            orderId: $orderId,
            status: $data['status'],
        );
    }
}
