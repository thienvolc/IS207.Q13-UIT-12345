<?php

namespace App\Domains\Catalog\DTOs\Category\Queries;

readonly class SearchProductsByCategorySlugDTO
{
    public function __construct(
        public string $slug,
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder
    ) {}
}
