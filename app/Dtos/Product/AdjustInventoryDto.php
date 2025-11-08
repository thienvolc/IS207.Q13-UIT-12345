<?php

namespace App\Dtos\Product;

readonly class AdjustInventoryDto
{
    public function __construct(
        public int $productId,
        public int $amount,
        public string $operationType,
        public ?string $reason
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            amount: $data['amount'],
            operationType: $data['operationType'],
            reason: $data['reason'] ?? null
        );
    }
}
