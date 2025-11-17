<?php

namespace App\Domains\Cart\Repositories;

use App\Domains\Cart\Constants\CartStatus;
use App\Domains\Cart\Entities\CartItem;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository
{
    public function create(array $data): CartItem
    {
        return CartItem::create($data)->load('product');
    }

    public function findOneByCartIdAndProductId(int $cartId, int $productId): ?CartItem
    {
        return CartItem::where('cart_id', $cartId)
            ->with('product')
            ->where('product_id', $productId)
            ->first();
    }

    public function getByIdAndUserOrFail(int $cartItemId, int $userId): CartItem
    {
        return CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', CartStatus::ACTIVE);})
            ->with('product')
            ->find($cartItemId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    // checkout
    public function listByCartIdAndItemIds(int $cartId, array $itemIds): Collection
    {
        return CartItem::whereIn('cart_item_id', $itemIds)
            ->where('cart_id', $cartId)
            ->with('product')
            ->lockForUpdate()
            ->get();
    }

    // order
    public function deleteAllInActiveCartByUserIdAndProductIds(int $userId, array $productIds): void
    {
        CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', CartStatus::ACTIVE);})
            ->whereIn('product_id', $productIds)
            ->delete();
    }
}
