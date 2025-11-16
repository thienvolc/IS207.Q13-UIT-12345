<?php

namespace App\Domains\Sales\DTOs\Cart\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\Entities\CartItem;

readonly class AddItemResponseDTO implements BaseDTO
{

    public function __construct(
        public int $itemId,
        public int $productId,
        public int $quantity,
    ) {}

    public static function fromModel(CartItem $cartItem): self
    {
        return new self(
            itemId: $cartItem->cart_item_id,
            productId: $cartItem->product_id,
            quantity: $cartItem->quantity,
        );
    }

    public function toArray(): array
    {
        return [
            'item_id' => $this->itemId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
        ];
    }
}
