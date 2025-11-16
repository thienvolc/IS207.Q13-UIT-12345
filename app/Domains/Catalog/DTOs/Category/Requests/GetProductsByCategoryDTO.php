<?php

namespace App\Domains\Catalog\DTOs\Category\Requests;

readonly class GetProductsByCategoryDTO
{
    public function __construct(
        public string $slug,
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            slug: $data['slug'],
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }
}
