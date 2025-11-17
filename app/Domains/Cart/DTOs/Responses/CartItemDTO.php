<?php

namespace App\Domains\Cart\DTOs\Responses;

use App\Domains\Cart\Entities\CartItem;
use App\Domains\Common\DTOs\BaseDTO;

readonly class CartItemDTO implements BaseDTO
{
    public function __construct(
        public int $itemId,
        public int $productId,
        public int $quantity,
    ) {}

    public function toArray(): array
    {
        return [
            'item_id' => $this->itemId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
        ];
    }
}
