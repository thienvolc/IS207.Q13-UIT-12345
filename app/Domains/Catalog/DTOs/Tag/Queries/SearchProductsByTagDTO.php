<?php

namespace App\Domains\Catalog\DTOs\Tag\Queries;

readonly class SearchProductsByTagDTO
{
    public function __construct(
        public int    $tagId,
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder
    )
    {
    }
}
