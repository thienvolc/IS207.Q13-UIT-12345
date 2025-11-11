<?php

namespace App\Dtos\Category;

readonly class SearchCategoriesPublicDto
{
    public function __construct(
        public ?string $query,
        public int $level,
        public int $offset,
        public int $limit,
        public string $sortField,
        public string $sortOrder
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            query: $data['query'] ?? null,
            level: $data['level'],
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }
}
