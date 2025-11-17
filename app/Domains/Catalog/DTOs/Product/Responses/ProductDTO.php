<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class ProductDTO implements BaseDTO
{
    public function __construct(
        public int $productId,
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

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
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
}
