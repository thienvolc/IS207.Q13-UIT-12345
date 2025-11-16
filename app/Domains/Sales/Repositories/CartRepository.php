<?php

namespace App\Domains\Sales\Repositories;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Sales\Constants\CartStatus;
use App\Domains\Sales\Entities\Cart;
use App\Domains\Sales\Entities\CartItem;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Collection;

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

    public function clearCartByUserId(int $userId): Cart
    {
        return CartItem::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('status', CartStatus::ACTIVE);
        })->delete();
    }

    public function findAndLockActiveByUserIdOrFail(int $userId): Cart
    {
        return Cart::where('user_id', $userId)
            ->where('status', CartStatus::ACTIVE)
            ->with('items.product')
            ->lockForUpdate()
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function createCheckoutFromItems(int $userId, Collection $items): Cart
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
    public function findCheckoutWithItemsOrFail(int $userId, int $cartId): Cart
    {
        return Cart::where('cart_id', $cartId)
            ->where('user_id', $userId)
            ->where('status', CartStatus::CHECKED_OUT)
            ->with('items.product')
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }
}
