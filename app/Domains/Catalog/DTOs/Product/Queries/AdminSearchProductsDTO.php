<?php

namespace App\Domains\Catalog\DTOs\Product\Queries;

readonly class AdminSearchProductsDTO
{
    use ProductFilterFactory;

    public function __construct(
        public ?string $query,
        public ?int    $categoryIdOrSlug,
        public ?int    $tagId,
        public ?float  $priceMin,
        public ?float  $priceMax,
        public int     $page,
        public int     $size,
        public string  $sortField,
        public string  $sortOrder
    )
    {
    }
}
