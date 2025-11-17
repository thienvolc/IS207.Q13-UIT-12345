<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class ProductPublicResponseDTO implements BaseDTO
{
    public function __construct(
        public int $productId,
        public ?int $categoryId,
        public string $title,
        public string $slug,
        public ?string $thumb,
        public ?string $desc,
        public ?string $summary,
        public ?string $type,
        public float $price,
        public int $quantity,
        public int $status,
        public float $discount,
        public ?string $endsAt = null,
        public array $categories = [],
        public array $tags = [],
        public array $meta = [],
    ) {}

    public static function fromModel($product): self
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

        return new self(
            productId: $product->product_id,
            categoryId: $product->category_id,
            title: $product->title,
            slug: $product->slug,
            thumb: $product->thumb,
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

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'desc' => $this->desc,
            'summary' => $this->summary,
            'type' => $this->type,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'discount' => $this->discount,
            'ends_at' => $this->endsAt,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'meta' => $this->meta,
        ];
    }

    public static function collection($products): array
    {
        return $products->map(fn($product) => self::fromModel($product)->toArray())->toArray();
    }
}
