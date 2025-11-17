<?php

namespace App\Domains\Catalog\DTOs\Product\Commands;

readonly class UpdateProductStatusDTO
{
    public function __construct(
        public int $productId,
        public int $status
    )
    {
    }
}
