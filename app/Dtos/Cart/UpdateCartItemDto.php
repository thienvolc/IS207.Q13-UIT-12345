<?php

namespace App\Dtos\Cart;

class UpdateCartItemDto
{
    public function __construct(
        public readonly int $cartItemId,
        public readonly int $quantity,
    ) {}

    public static function fromArray(array $data, int $cartItemId): self
    {
        return new self(
            cartItemId: $cartItemId,
            quantity: $data['quantity'],
        );
    }
}
