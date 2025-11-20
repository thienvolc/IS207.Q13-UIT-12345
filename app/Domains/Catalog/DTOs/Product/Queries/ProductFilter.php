<?php

namespace App\Domains\Catalog\DTOs\Product\Queries;

class ProductFilter
{
    public function __construct(
        public ?string $query,
        public ?string $categoryIdOrSlug,
        public ?string $tagId,
        public ?string $priceMin,
        public ?string $priceMax,
    ) {}
}
