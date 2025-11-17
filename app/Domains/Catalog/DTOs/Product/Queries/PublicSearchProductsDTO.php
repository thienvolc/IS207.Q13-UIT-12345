<?php

namespace App\Domains\Catalog\DTOs\Product\Queries;

readonly class PublicSearchProductsDTO
{
    public function __construct(
        public ?string $query,
        public ?int    $category,
        public ?float  $priceMin,
        public ?float  $priceMax,
        public int     $offset,
        public int     $limit,
        public string  $sortField,
        public string  $sortOrder
    )
    {
    }

    public function getFilter(): PublicProductFilter
    {
        return new PublicProductFilter(
            query: $this->query,
            category: $this->category,
            priceMin: $this->priceMin,
            priceMax: $this->priceMax
        );
    }
}
