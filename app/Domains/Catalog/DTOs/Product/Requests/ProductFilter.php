<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

class ProductFilter
{
    public function __construct(
        public ?string $query,
        public ?string $category,
        public ?string $priceMin,
        public ?string $priceMax,
    ) {}
}
