<?php

namespace App\Dtos\Category;

readonly class SearchCategoriesAdminDto
{

    public function __construct(
        public int $level,
        public int $page,
        public int $size,
        public string $sortField,
        public string $sortOrder
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            level: $data['level'],
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }
}
