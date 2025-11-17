<?php

namespace App\Domains\Order\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class ProductDTO implements BaseDTO
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
