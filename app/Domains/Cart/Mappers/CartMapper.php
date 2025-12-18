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
        // Bỏ qua các CartItem không có product (sản phẩm đã xóa hoặc không active)
        return $cartItems->filter(fn($i) => $i->product !== null)
            ->map(fn($i) => $this->toItemDTO($i));
    }

    public function toItemDTO(CartItem $cartItem): CartItemDTO
    {
        $product = $cartItem->product;
        if (!$product) {
            // Trả về null, hoặc có thể throw exception, nhưng tốt nhất là filter ở trên
            return null;
        }
        return new CartItemDTO(
            itemId: $cartItem->cart_item_id,
            productId: $cartItem->product_id,
            quantity: $cartItem->quantity,
            price: (float)$product->price,
            productName: $product->title ?? null,
            productSlug: $product->slug ?? null,
            productImage: $product->thumb ?? null,
            discount: (float)($product->discount ?? 0),
        );
    }
}
