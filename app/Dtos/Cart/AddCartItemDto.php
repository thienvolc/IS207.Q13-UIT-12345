<?php

namespace App\Dtos\Cart;

class AddCartItemDto
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly ?string $note,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['product_id'],
            quantity: $data['quantity'],
            note: $data['note'] ?? null,
        );
    }
}
