<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

readonly class UpdateTagsDTO
{
    public function __construct(
        public int   $productId,
        public array $tagIds
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            tagIds: $data['tagIds']
        );
    }
}
