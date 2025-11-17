<?php

namespace App\Domains\Catalog\DTOs\Product\Commands;

readonly class CreateProductDTO
{
    public function __construct(
        public string  $title,
        public ?string $meta_title,
        public ?string $slug,
        public ?string $thumb,
        public ?string $desc,
        public ?string $summary,
        public ?string $type,
        public string  $sku,
        public float   $price,
        public ?int    $discount,
        public int     $quantity,
        public int     $status,
        public ?string $starts_at,
        public ?string $ends_at,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'meta_title' => $this->meta_title,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'desc' => $this->desc,
            'summary' => $this->summary,
            'type' => $this->type,
            'sku' => $this->sku,
            'price' => $this->price,
            'discount' => $this->discount,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
        ];
    }
}
