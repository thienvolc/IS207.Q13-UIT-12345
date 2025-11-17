<?php

namespace App\Domains\Catalog\DTOs\Category\Queries;

readonly class PublicSearchCategoriesDTO
{
    public function __construct(
        public ?string $query,
        public ?int    $level,
        public int     $offset,
        public int     $limit,
        public string  $sortField,
        public string  $sortOrder,
    ) {}
}
