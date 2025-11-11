<?php

namespace App\Dtos\Product;

readonly class UpdateTagsDto
{
    public function __construct(
        public int $productId,
        public array $tagIds
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            tagIds: $data['tagIds']
        );
    }
}
