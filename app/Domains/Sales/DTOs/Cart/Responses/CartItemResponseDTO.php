<?php

namespace App\Domains\Sales\DTOs\Cart\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\DTOs\ProductResponseDTO;
use App\Domains\Sales\Entities\CartItem;

readonly class CartItemResponseDTO implements BaseDTO
{
    public function __construct(
        public int                 $cartItemId,
        public int                 $productId,
        public float               $price,
        public int                 $quantity,
        public float               $discount,
        public ?string             $note = null,
        public ?ProductResponseDTO $product = null
    ) {}

    public static function fromModel(CartItem $cartItem): self
    {
        return new self(
            cartItemId: $cartItem->cart_item_id,
            productId: $cartItem->product_id,
            price: (float)$cartItem->price,
            quantity: $cartItem->quantity,
            discount: (float)$cartItem->discount,
            note: $cartItem->note,
            product: self::buildProduct($cartItem)
        );

    }

    private static function buildProduct(CartItem $cartItem): ?ProductResponseDTO
    {
        if (!$cartItem->relationLoaded('product')) {
            return null;
        }
        return ProductResponseDTO::fromModel($cartItem->product);
    }

    public function toArray(): array
    {
        return [
            'cart_item_id' => $this->cartItemId,
            'product_id' => $this->productId,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'note' => $this->note,
            'product' => $this->product?->toArray(),
        ];
    }
}
