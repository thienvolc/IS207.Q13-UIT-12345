<?php

namespace App\Domains\Catalog\DTOs\Product\Queries;

readonly class AdminSearchProductsDTO
{
    public function __construct(
        public ?string $query,
        public ?int    $category,
        public ?float  $priceMin,
        public ?float  $priceMax,
        public int     $page,
        public int     $size,
        public string  $sortField,
        public string  $sortOrder
    ) {}

    public function getFilter(): AdminProductFilter
    {
        return new AdminProductFilter(
            query: $this->query,
            category: $this->category,
            priceMin: $this->priceMin,
            priceMax: $this->priceMax
        );
    }
}
