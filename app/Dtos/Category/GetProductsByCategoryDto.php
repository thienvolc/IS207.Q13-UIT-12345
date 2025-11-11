<?php

namespace App\Dtos\Category;

readonly class GetProductsByCategoryDto
{
    public function __construct(
        public string $slug,
        public int $offset,
        public int $limit,
        public string $sortField,
        public string $sortOrder
    ) {}

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
