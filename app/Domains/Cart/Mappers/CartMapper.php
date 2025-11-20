<?php

namespace App\Domains\Cart\Mappers;

use App\Domains\Cart\DTOs\Responses\CartDTO;
use App\Domains\Cart\DTOs\Responses\CartItemDTO;
use App\Domains\Cart\Entities\Cart;
use App\Domains\Cart\Entities\CartItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CartMapper
{
    public function toDTO(Cart $cart): CartDTO
    {
        $items = $this->toItemDTOs($cart->items);
        $totalQuantity = $items->sum('quantity');
        $totalPrice = (float)$items->sum(fn($i) => $i->price * $i->quantity);

        return new CartDTO(
            cartId: $cart->cart_id,
            userId: $cart->user_id,
            totalQuantity: $totalQuantity,
            totalPrice: $totalPrice,
            status: $cart->status,
            items: $items->toArray(),
            updatedAt: $cart->updated_at?->toDateTimeString(),
        );
    }

    /**
     * @param EloquentCollection<CartItem, int> $cartItems
     * @return Collection
     */
    public function toItemDTOs(EloquentCollection $cartItems): Collection
    {
        return $cartItems->map(fn($i) => $this->toItemDTO($i));
    }

    public function toItemDTO(CartItem $cartItem): CartItemDTO
    {
        return new CartItemDTO(
            itemId: $cartItem->cart_item_id,
            productId: $cartItem->product_id,
            quantity: $cartItem->quantity,
            price: (float)$cartItem->product->price,
        );
    }
}
