<?php

namespace App\Repositories;

use App\Constants\CartStatus;
use App\Models\Cart;

class CartRepository
{
    public function findOrCreateActive(int $userId): Cart
    {
        $cart = Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with(['items.product'])
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'status' => CartStatus::ACTIVE,
            ]);
            $cart->load(['items.product']);
        }

        return $cart;
    }

    public function createCheckoutCart(int $userId): Cart
    {
        return Cart::create([
            'user_id' => $userId,
            'status' => CartStatus::CHECKED_OUT,
        ]);
    }

    public function findActive(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->first();
    }

    public function findAndLockActive(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with('items.product')
            ->lockForUpdate()
            ->first();
    }

    public function findCheckedOutCart(int $userId, int $cartId): ?Cart
    {
        return Cart::where('cart_id', $cartId)
            ->where('user_id', $userId)
            ->where('status', CartStatus::CHECKED_OUT)
            ->with('items.product')
            ->first();
    }
}
