<?php

namespace App\Dtos\Order;

class PlaceOrderDto
{
    public function __construct(
        public readonly int $cartId,
        public readonly ?string $promo,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            cartId: $data['cart_id'],
            promo: $data['promo'] ?? null,
        );
    }
}
