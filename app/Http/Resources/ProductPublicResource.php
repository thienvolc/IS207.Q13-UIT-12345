<?php

namespace App\Http\Resources;

class ProductPublicResource
{
    public static function transform($product): array
    {
        return [
            'product_id' => $product->product_id,
            'category_id' => $product->category_id,
            'title' => $product->title,
            'slug' => $product->slug,
            'thumb' => $product->thumb,
            'desc' => $product->desc,
            'summary' => $product->summary,
            'type' => $product->type,
            'price' => (int) $product->price,
            'quantity' => $product->quantity,
            'status' => $product->status,
            'discount' => (float) $product->discount,
            'ends_at' => $product->ends_at?->toIso8601String(),
            'categories' => $product->categories->map(fn($cat) => [
                'category_id' => $cat->category_id,
                'title' => $cat->title,
                'slug' => $cat->slug,
                'meta_title' => $cat->meta_title,
            ])->toArray(),
            'tags' => $product->tags->map(fn($tag) => [
                'tag_id' => $tag->tag_id,
                'name' => $tag->title,
                'slug' => $tag->slug,
                'meta_title' => $tag->meta_title,
            ])->toArray(),
            'meta' => $product->metas->map(fn($meta) => [
                'key' => $meta->key,
                'content' => $meta->content,
            ])->toArray(),
        ];
    }

    public static function collection($products): array
    {
        return $products->map(fn($product) => self::transform($product))->toArray();
    }
}

