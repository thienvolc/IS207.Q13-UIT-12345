<?php

namespace App\Http\Resources;

class OrderResource
{
    public static function transform($order): array
    {
        $data = [
            'order_id' => $order->order_id,
            'user_id' => $order->user_id,
            'subtotal' => (float) $order->subtotal,
            'tax' => (float) $order->tax,
            'shipping' => (float) $order->shipping,
            'total' => (float) $order->total,
            'discount_total' => (float) $order->discount_total,
            'promo' => $order->promo,
            'discount' => (float) $order->discount,
            'grand_total' => (float) $order->grand_total,
            'first_name' => $order->first_name,
            'middle_name' => $order->middle_name,
            'last_name' => $order->last_name,
            'phone' => $order->phone,
            'email' => $order->email,
            'line1' => $order->line1,
            'line2' => $order->line2,
            'city' => $order->city,
            'province' => $order->province,
            'country' => $order->country,
            'orders_at' => $order->orders_at?->toIso8601String(),
            'status' => $order->status,
            'note' => $order->note,
            'created_at' => $order->created_at?->toIso8601String(),
            'updated_at' => $order->updated_at?->toIso8601String(),
        ];

        if ($order->relationLoaded('items')) {
            $data['items'] = OrderItemResource::collection($order->items);
        }

        return $data;
    }

    public static function collection($orders): array
    {
        return $orders->map(fn($order) => self::transform($order))->toArray();
    }
}

