<?php

namespace App\Domains\Catalog\DTOs\Category\Queries;

readonly class AdminSearchCategoriesDTO
{
    public function __construct(
        public ?string $query,
        public int     $level,
        public int     $page,
        public int     $size,
        public string  $sortField,
        public string  $sortOrder
    ) {}
}
