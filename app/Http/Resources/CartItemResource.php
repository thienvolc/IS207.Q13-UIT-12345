<?php

namespace App\Http\Resources;

class CartItemResource
{
    public static function transform($item): array
    {
        return [
            'cart_item_id' => $item->cart_item_id,
            'product_id' => $item->product_id,
            'price' => (float) $item->price,
            'quantity' => $item->quantity,
            'discount' => (float) $item->discount,
            'note' => $item->note,
            'product' => $item->relationLoaded('product') && $item->product ? [
                'product_id' => $item->product->product_id,
                'title' => $item->product->title,
                'slug' => $item->product->slug,
                'thumb' => $item->product->thumb,
                'price' => (float) $item->product->price,
                'discount' => (float) $item->product->discount,
                'quantity' => $item->product->quantity,
                'status' => $item->product->status,
            ] : null,
        ];
    }

    public static function collection($items): array
    {
        return $items->map(fn($item) => self::transform($item))->toArray();
    }
}
