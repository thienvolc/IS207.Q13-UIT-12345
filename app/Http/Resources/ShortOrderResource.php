<?php

namespace App\Http\Resources;

class ShortOrderResource
{
    public static function transform($order): array
    {
        return [
            'order_id' => $order->order_id,
            'total' => (float) $order->total,
            'grand_total' => (float) $order->grand_total,
            'status' => $order->status,
            'orders_at' => $order->orders_at?->toIso8601String(),
        ];
    }

    public static function collection($orders): array
    {
        return $orders->map(fn($order) => self::transform($order))->toArray();
    }
}
