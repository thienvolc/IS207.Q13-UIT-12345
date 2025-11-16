<?php

namespace App\Domains\Catalog\DTOs\Category\Requests;

readonly class SearchCategoriesAdminDTO
{
    public function __construct(
        public int    $level,
        public int    $page,
        public int    $size,
        public string $sortField,
        public string $sortOrder
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            level: $data['level'] ?? 0,
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }
}
