<?php

namespace App\Domains\Catalog\DTOs\Product\Queries;

readonly class SearchRelatedProductsDTO
{
    public function __construct(
        public int    $productId,
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder
    )
    {
    }
}
