<?php

namespace App\Dtos\Product;

readonly class GetRelatedProductsDto
{
    public function __construct(
        public int $productId,
        public int $offset,
        public int $limit,
        public string $sortField,
        public string $sortOrder
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }
}
