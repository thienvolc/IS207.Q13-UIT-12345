<?php

namespace App\Domains\Catalog\DTOs\Product\Commands;

readonly class UpdateProductDTO
{
    public function __construct(
        public int     $productId,
        public ?string $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $thumb,
        public ?string $desc,
        public ?string $summary,
        public ?string $type,
        public ?string $sku,
        public ?float  $price,
        public ?float  $discount,
        public ?int    $status,
        public ?string $startsAt,
        public ?string $endsAt,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'desc' => $this->desc,
            'summary' => $this->summary,
            'type' => $this->type,
            'sku' => $this->sku,
            'price' => $this->price,
            'discount' => $this->discount,
            'status' => $this->status,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
        ], fn($value) => !is_null($value));
    }
}
