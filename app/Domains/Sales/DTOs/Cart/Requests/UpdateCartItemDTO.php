<?php

namespace App\Domains\Sales\DTOs\Cart\Requests;

readonly class UpdateCartItemDTO
{
    public function __construct(
        public int $cartItemId,
        public int $quantity,
    ) {}

    public static function fromArray(array $data, int $cartItemId): self
    {
        return new self(
            cartItemId: $cartItemId,
            quantity: $data['quantity'],
        );
    }
}
