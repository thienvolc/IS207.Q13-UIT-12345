<?php

namespace App\Http\Resources;

class OrderItemResource
{
    public static function transform($item): array
    {
        return [
            'order_item_id' => $item->order_item_id,
            'order_id' => $item->order_id,
            'product_id' => $item->product_id,
            'price' => (float) $item->price,
            'quantity' => $item->quantity,
            'discount' => (float) $item->discount,
            'note' => $item->note,
        ];
    }

    public static function collection($items): array
    {
        return $items->map(fn($item) => self::transform($item))->toArray();
    }
}

