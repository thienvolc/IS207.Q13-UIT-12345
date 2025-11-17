<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class PublicProductDTO implements BaseDTO
{
    public function __construct(
        public int $productId,
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

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
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
}
