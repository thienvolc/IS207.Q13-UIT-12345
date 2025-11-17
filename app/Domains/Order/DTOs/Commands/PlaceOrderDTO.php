<?php

namespace App\Domains\Order\DTOs\Commands;

readonly class PlaceOrderDTO
{
    public function __construct(
        public int     $cartId,
        public ?string $promo,
    ) {}
}
