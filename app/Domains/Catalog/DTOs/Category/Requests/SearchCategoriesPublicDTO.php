<?php

namespace App\Domains\Catalog\DTOs\Category\Requests;

readonly class SearchCategoriesPublicDTO
{
    public function __construct(
        public ?int    $level,
        public ?string $query,
        public string  $sortField,
        public string  $sortOrder,
        public int     $offset,
        public int     $limit
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            level: $data['level'] ?? null,
            query: $data['query'] ?? null,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc',
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10
        );
    }
}
