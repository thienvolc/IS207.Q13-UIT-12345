<?php

namespace App\Domains\Catalog\DTOs\Product\Commands;

readonly class AssignProductCategoriesDTO
{
    public function __construct(
        public int   $productId,
        public array $categoryIds
    )
    {
    }
}
