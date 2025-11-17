<?php

namespace App\Domains\Cart\DTOs\Commands;

readonly class AddCartItemDTO
{
    public function __construct(
        public int $productId,
        public int $quantity
    ) {}
}
