<?php

namespace App\Domains\Catalog\DTOs\Tag\Queries;

readonly class SearchTagsDTO
{
    public function __construct(
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder
    ) {}
}
