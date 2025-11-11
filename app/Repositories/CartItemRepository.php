<?php

namespace App\Repositories;

use App\Constants\CartStatus;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository
{
    public function findByCartAndProduct(int $cartId, int $productId): ?CartItem
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }

    public function findUserCartItem(int $userId, int $cartItemId): ?CartItem
    {
        return CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('status', CartStatus::ACTIVE);
        })->find($cartItemId);
    }

    public function findAndLockByIds(int $cartId, array $itemIds): Collection
    {
        return CartItem::whereIn('cart_item_id', $itemIds)
            ->where('cart_id', $cartId)
            ->with('product')
            ->lockForUpdate()
            ->get();
    }

    public function create(array $data): CartItem
    {
        return CartItem::create($data);
    }

    public function deleteByCartId(int $cartId): int
    {
        return CartItem::where('cart_id', $cartId)->delete();
    }

    public function deleteByCartAndProductIds(int $cart_id, array $productIds): void
    {
        CartItem::where('cart_id', $cart_id)
            ->whereIn('product_id', $productIds)
            ->delete();
    }
}
