<?php

namespace App\Repositories;

use App\Constants\CartStatus;
use App\Models\Cart;

class CartRepository
{
    public function findOrCreateActiveCart(int $userId): Cart
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

    public function findActiveCart(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->first();
    }

    public function findAndLockActiveCart(int $userId): ?Cart
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

    public function create(array $data): Cart
    {
        return Cart::create($data);
    }

    public function update(Cart $cart, array $data): bool
    {
        return $cart->update($data);
    }
}
