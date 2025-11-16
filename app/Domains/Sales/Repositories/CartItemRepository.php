<?php

namespace App\Domains\Sales\Repositories;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Sales\Constants\CartStatus;
use App\Domains\Sales\Entities\Cart;
use App\Domains\Sales\Entities\CartItem;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository
{
    public function create(array $data): CartItem
    {
        return CartItem::create($data);
    }

    public function findByCartIdAndProductId(int $cartId, int $productId): ?CartItem
    {
        return CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }

    public function findInUserCartOrFail(int $userId, int $cartItemId): CartItem
    {
        return CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', CartStatus::ACTIVE);})
            ->find($cartItemId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    public function findLockedByIdsWithProduct(int $cartId, array $itemIds): Collection
    {
        return CartItem::whereIn('cart_item_id', $itemIds)
            ->where('cart_id', $cartId)
            ->with('product')
            ->lockForUpdate()
            ->get();
    }

    // order
    public function deleteInActiveCartByUserIdAndProductIds(int $userId, array $productIds): void
    {
        CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', CartStatus::ACTIVE);})
            ->whereIn('product_id', $productIds)
            ->delete();
    }
}
