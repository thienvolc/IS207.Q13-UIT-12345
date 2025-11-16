<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

class ProductAdminDTO
{
    public static function transform($product): array
    {
        return [
            'product_id' => $product->product_id,
            'category_id' => $product->category_id,
            'title' => $product->title,
            'meta_title' => $product->meta_title,
            'slug' => $product->slug,
            'thumb' => $product->thumb,
            'desc' => $product->desc,
            'summary' => $product->summary,
            'type' => $product->type,
            'sku' => $product->sku,
            'price' => (int)$product->price,
            'quantity' => $product->quantity,
            'status' => $product->status,
            'discount' => (float)$product->discount,
            'published_at' => $product->published_at?->toIso8601String(),
            'starts_at' => $product->starts_at?->toIso8601String(),
            'ends_at' => $product->ends_at?->toIso8601String(),
            'created_at' => $product->created_at?->toIso8601String(),
            'updated_at' => $product->updated_at?->toIso8601String(),
            'created_by' => $product->created_by,
            'updated_by' => $product->updated_by,
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
            'metas' => $product->metas->map(fn($meta) => [
                'meta_id' => $meta->meta_id,
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
