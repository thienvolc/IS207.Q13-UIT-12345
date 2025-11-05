<?php

namespace App\Http\Resources;

class CartResource
{
    public static function transform($cart): array
    {
        return [
            'cart_id' => $cart->cart_id,
            'user_id' => $cart->user_id,
            'total_quantity' => $cart->items->sum('quantity'),
            'total_price' => (float) $cart->items->sum(function($item) {
                return $item->price * $item->quantity;
            }),
            'status' => $cart->status,
            'items' => CartItemResource::collection($cart->items),
        ];
    }
}

