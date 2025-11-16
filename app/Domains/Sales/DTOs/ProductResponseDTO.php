<?php

namespace App\Domains\Sales\DTOs;

use App\Domains\Common\DTOs\BaseDTO;

readonly class ProductResponseDTO implements BaseDTO
{
    public function __construct(
        public int     $productId,
        public string  $title,
        public string  $slug,
        public ?string $thumb,
        public float   $price,
        public float   $discount,
        public int     $quantity,
        public int     $status
    ) {}

    static public function fromModel(object $product): self
    {
        return new self(
            productId: $product->product_id,
            title: $product->title,
            slug: $product->slug,
            thumb: $product->thumb,
            price: (float)$product->price,
            discount: (float)$product->discount,
            quantity: $product->quantity,
            status: $product->status
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'title' => $this->title,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'price' => $this->price,
            'discount' => $this->discount,
            'quantity' => $this->quantity,
            'status' => $this->status,
        ];
    }
}
