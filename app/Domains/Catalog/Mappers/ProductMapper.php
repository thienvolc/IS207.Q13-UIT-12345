<?php

namespace App\Domains\Catalog\Mappers;

use App\Domains\Catalog\DTOs\Product\Responses\ProductDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductMetaDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductStatusDTO;
use App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO;
use App\Domains\Catalog\Entities\Product;
use Illuminate\Database\Eloquent\Collection;

readonly class ProductMapper
{
    private const THUMBNAIL_PREFIX = "https://broad-snowflake-e396.ttt2042005.workers.dev/proxy?img=";
    public function toDTO(Product $product): ProductDTO
    {
        $categories = [];
        $tags = [];
        $metas = [];

        if ($product->relationLoaded('categories')) {
            $categories = $product->categories->map(fn($cat) => [
                'category_id' => $cat->category_id,
                'title' => $cat->title,
                'slug' => $cat->slug,
                'meta_title' => $cat->meta_title,
            ])->toArray();
        }

        if ($product->relationLoaded('tags')) {
            $tags = $product->tags->map(fn($tag) => [
                'tag_id' => $tag->tag_id,
                'name' => $tag->title,
                'slug' => $tag->slug,
                'meta_title' => $tag->meta_title,
            ])->toArray();
        }

        if ($product->relationLoaded('metas')) {
            $metas = $product->metas->map(fn($meta) => [
                'meta_id' => $meta->meta_id,
                'key' => $meta->key,
                'content' => $meta->content,
            ])->toArray();
        }

        return new ProductDTO(
            productId: $product->product_id,
            title: $product->title,
            metaTitle: $product->meta_title,
            slug: $product->slug,
            thumb: $this->padThumbnailPrefix($product->thumb),
            desc: $product->desc,
            summary: $product->summary,
            type: $product->type,
            sku: $product->sku,
            price: (float)$product->price,
            quantity: $product->quantity,
            status: $product->status,
            discount: (float)$product->discount,
            publishedAt: optional($product->published_at)?->toIso8601String(),
            startsAt: optional($product->starts_at)?->toIso8601String(),
            endsAt: optional($product->ends_at)?->toIso8601String(),
            createdAt: optional($product->created_at)?->toIso8601String(),
            updatedAt: optional($product->updated_at)?->toIso8601String(),
            createdBy: $product->created_by,
            updatedBy: $product->updated_by,
            categories: $categories,
            tags: $tags,
            metas: $metas,
        );
    }

    public function toPublicDTO(Product $product): PublicProductDTO
    {
        $categories = [];
        $tags = [];
        $meta = [];

        if ($product->relationLoaded('categories')) {
            $categories = $product->categories->map(fn($cat) => [
                'category_id' => $cat->category_id,
                'title' => $cat->title,
                'slug' => $cat->slug,
                'meta_title' => $cat->meta_title,
            ])->toArray();
        }

        if ($product->relationLoaded('tags')) {
            $tags = $product->tags->map(fn($tag) => [
                'tag_id' => $tag->tag_id,
                'name' => $tag->title,
                'slug' => $tag->slug,
                'meta_title' => $tag->meta_title,
            ])->toArray();
        }

        if ($product->relationLoaded('metas')) {
            $meta = $product->metas->map(fn($m) => [
                'key' => $m->key,
                'content' => $m->content,
            ])->toArray();
        }

        return new PublicProductDTO(
            productId: $product->product_id,
            title: $product->title,
            slug: $product->slug,
            thumb: $this->padThumbnailPrefix($product->thumb),
            desc: $product->desc,
            summary: $product->summary,
            type: $product->type,
            price: (float)$product->price,
            quantity: $product->quantity,
            status: $product->status,
            discount: (float)$product->discount,
            endsAt: optional($product->ends_at)?->toIso8601String(),
            categories: $categories,
            tags: $tags,
            meta: $meta,
        );
    }

    public function toMetaDTO(object $meta): ProductMetaDTO
    {
        return new ProductMetaDTO(
            metaId: $meta->meta_id,
            key: $meta->key,
            content: $meta->content,
        );
    }

    public function toStatusDTO(Product $product): ProductStatusDTO
    {
        return new ProductStatusDTO(
            productId: $product->product_id,
            status: $product->status,
        );
    }

    private function padThumbnailPrefix(?string $thumb): ?string
    {
        if ($thumb === null) {
            return null;
        }

        return self::THUMBNAIL_PREFIX . $thumb;
    }
}
