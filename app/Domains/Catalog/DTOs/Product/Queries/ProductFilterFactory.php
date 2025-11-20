<?php

namespace App\Domains\Catalog\DTOs\Product\Queries;

trait ProductFilterFactory
{
    public function getFilter(): ProductFilter
    {
        return new ProductFilter(
            query: $this->query,
            categoryIdOrSlug: $this->categoryIdOrSlug,
            tagId: $this->tagId,
            priceMin: $this->priceMin,
            priceMax: $this->priceMax
        );
    }
}
