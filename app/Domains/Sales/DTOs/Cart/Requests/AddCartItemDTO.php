<?php

namespace App\Domains\Sales\DTOs\Cart\Requests;

readonly class AddCartItemDTO
{
    public function __construct(
        public int $productId,
        public int $quantity
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['product_id'],
            quantity: $data['quantity']
        );
    }
}
