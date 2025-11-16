<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

readonly class UpdateCategoriesDTO
{
    public function __construct(
        public int   $productId,
        public array $categoryIds
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            categoryIds: $data['categoryIds']
        );
    }
}
