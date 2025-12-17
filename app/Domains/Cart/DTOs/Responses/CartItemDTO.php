<?php

namespace App\Domains\Cart\DTOs\Responses;

use App\Domains\Cart\Entities\CartItem;
use App\Domains\Common\DTOs\BaseDTO;

readonly class CartItemDTO implements BaseDTO
{
    public function __construct(
        public int      $itemId,
        public int      $productId,
        public int      $quantity,
        public float    $price,
        public ?string  $productName = null,
        public ?string  $productSlug = null,
        public ?string  $productImage = null,
        public float    $discount = 0,
    ) {}

    public function toArray(): array
    {
        return [
            'item_id' => $this->itemId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'product_name' => $this->productName,
            'product_slug' => $this->productSlug,
            'product_image' => $this->productImage,
            'discount' => $this->discount,
            'subtotal' => $this->price * $this->quantity,
        ];
    }
}
