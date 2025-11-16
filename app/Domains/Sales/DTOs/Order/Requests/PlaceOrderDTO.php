<?php

namespace App\Domains\Sales\DTOs\Order\Requests;

readonly class PlaceOrderDTO
{
    public function __construct(
        public int     $cartId,
        public ?string $promo,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            cartId: $data['cart_id'],
            promo: $data['promo'] ?? null,
        );
    }
}
