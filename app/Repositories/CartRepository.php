<?php

namespace App\Repositories;

use App\Constants\CartStatus;
use App\Models\Cart;

class CartRepository
{
    public function findOrCreateActiveByUserId(int $userId): Cart
    {
        $cart = Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with(['items.product'])
            ->first();

        return $cart ?? $this->createActive($userId);
    }

    public function createActive(int $userId): Cart
    {
        return Cart::create([
            'user_id' => $userId,
            'status' => CartStatus::ACTIVE,
        ]);
    }

    public function createCheckedOut(int $userId): Cart
    {
        return Cart::create([
            'user_id' => $userId,
            'status' => CartStatus::CHECKED_OUT,
        ]);
    }

    public function findActiveByUserId(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->first();
    }

    public function findAndLockActiveByUserId(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with('items.product')
            ->lockForUpdate()
            ->first();
    }

    public function findCheckedOutWithItemsByUserIdAndCartId(int $userId, int $cartId): ?Cart
    {
        return Cart::where('cart_id', $cartId)
            ->where('user_id', $userId)
            ->where('status', CartStatus::CHECKED_OUT)
            ->with('items.product')
            ->first();
    }
}
