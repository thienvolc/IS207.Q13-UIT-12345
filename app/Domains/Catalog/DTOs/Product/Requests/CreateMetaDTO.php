<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

readonly class CreateMetaDTO
{
    public function __construct(
        public int    $productId,
        public string $key,
        public string $content
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            key: $data['key'],
            content: $data['content']
        );
    }
}
