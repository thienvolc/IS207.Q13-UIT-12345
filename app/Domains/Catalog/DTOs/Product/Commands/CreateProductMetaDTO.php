<?php

namespace App\Domains\Catalog\DTOs\Product\Commands;

readonly class CreateProductMetaDTO
{
    public function __construct(
        public int    $productId,
        public string $key,
        public string $content
    )
    {
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'key' => $this->key,
            'content' => $this->content,
        ];
    }
}
