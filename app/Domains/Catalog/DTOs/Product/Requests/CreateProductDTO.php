<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

readonly class CreateProductDTO
{
    public function __construct(
        public string  $title,
        public ?string $desc,
        public ?string $summary,
        public ?string $slug,
        public ?string $thumbnail,
        public float   $price,
        public ?float  $salePrice,
        public int     $quantity,
        public ?int    $status
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            desc: $data['desc'] ?? null,
            summary: $data['summary'] ?? null,
            slug: $data['slug'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            price: $data['price'],
            salePrice: $data['sale_price'] ?? null,
            quantity: $data['quantity'],
            status: $data['status'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'desc' => $this->desc,
            'summary' => $this->summary,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
            'sale_price' => $this->salePrice,
            'quantity' => $this->quantity,
            'status' => $this->status,
        ];
    }
}
