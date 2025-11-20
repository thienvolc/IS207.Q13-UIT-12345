<?php

namespace App\Domains\Catalog\DTOs\Product\Queries;

readonly class PublicSearchProductsDTO
{
    use ProductFilterFactory;

    public function __construct(
        public ?string $query,
        public ?int    $categoryIdOrSlug,
        public ?int    $tagId,
        public ?float  $priceMin,
        public ?float  $priceMax,
        public int     $offset,
        public int     $limit,
        public string  $sortField,
        public string  $sortOrder
    ) {}
}
