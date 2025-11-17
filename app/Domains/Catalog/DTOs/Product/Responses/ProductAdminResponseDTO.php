<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class ProductAdminResponseDTO implements BaseDTO
{
    public function __construct(
        public int $productId,
        public ?int $categoryId,
        public string $title,
        public ?string $metaTitle,
        public string $slug,
        public ?string $thumb,
        public ?string $desc,
        public ?string $summary,
        public ?string $type,
        public ?string $sku,
        public float $price,
        public int $quantity,
        public int $status,
        public float $discount,
        public ?string $publishedAt = null,
        public ?string $startsAt = null,
        public ?string $endsAt = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
        public array $categories = [],
        public array $tags = [],
        public array $metas = [],
    ) {}

    public static function fromModel($product): self
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

        return new self(
            productId: $product->product_id,
            categoryId: $product->category_id,
            title: $product->title,
            metaTitle: $product->meta_title,
            slug: $product->slug,
            thumb: $product->thumb,
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

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'desc' => $this->desc,
            'summary' => $this->summary,
            'type' => $this->type,
            'sku' => $this->sku,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'discount' => $this->discount,
            'published_at' => $this->publishedAt,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'metas' => $this->metas,
        ];
    }

    public static function collection($products): array
    {
        return $products->map(fn($product) => self::fromModel($product)->toArray())->toArray();
    }
}
