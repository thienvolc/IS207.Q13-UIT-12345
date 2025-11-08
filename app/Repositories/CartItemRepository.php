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

    public function update(CartItem $cartItem, array $data): bool
    {
        return $cartItem->update($data);
    }

    public function delete(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }

    public function deleteByCartId(int $cartId): int
    {
        return CartItem::where('cart_id', $cartId)->delete();
    }

    public function deleteNonSelected(int $cartId, array $selectedItemIds): int
    {
        return CartItem::where('cart_id', $cartId)
            ->whereNotIn('cart_item_id', $selectedItemIds)
            ->delete();
    }
}
