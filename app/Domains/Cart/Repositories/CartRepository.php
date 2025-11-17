<?php

namespace App\Domains\Cart\Repositories;

use App\Domains\Cart\Constants\CartStatus;
use App\Domains\Cart\Entities\Cart;
use App\Domains\Cart\Entities\CartItem;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Collection;

class CartRepository
{
    public function getActiveOrCreateForUser(int $userId): Cart
    {
        return $this->findActiveForUser($userId)
            ?? $this->createActiveForUser($userId);
    }

    public function findActiveForUser(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with(['items.product'])
            ->first();
    }

    public function createActiveForUser(int $userId): Cart
    {
        return Cart::create([
            'user_id' => $userId,
            'status'  => CartStatus::ACTIVE,
        ]);
    }

    public function clearCartForUser(int $userId): Cart
    {
        return CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', CartStatus::ACTIVE);})
            ->delete();
    }

    public function getActiveCartForUserOrFail(int $userId): Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with('items.product')
            ->lockForUpdate()
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function createCheckoutCartForUser(int $userId, Collection $items): Cart
    {
        $cart = Cart::create([
            'user_id' => $userId,
            'status' => CartStatus::CHECKED_OUT,
        ]);
        $cartId = $cart->cart_id;

        $copyItems = $items->map(function (CartItem $item) use ($cartId) {
            $replica = $item->replicate();
            $replica->cart_id = $cartId;
            return $replica;
        });

        CartItem::insert($copyItems->toArray());

        $cart->load("items");

        return $cart;
    }

    // order
    public function getCheckoutCartByIdAndUserOrFail(int $cartId, int $userId): Cart
    {
        return Cart::where('cart_id', $cartId)
            ->where('user_id', $userId)
            ->where('status', CartStatus::CHECKED_OUT)
            ->with('items.product')
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }
}
