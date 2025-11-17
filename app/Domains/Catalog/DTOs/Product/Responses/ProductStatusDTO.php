<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

use App\Domains\Catalog\Entities\Product;
use App\Domains\Common\DTOs\BaseDTO;

class ProductStatusDTO implements BaseDTO
{
    public function __construct(
        public int    $productId,
        public string $status,
    ) {}

    public static function fromModel(Product $product): self
    {
        return new self(
            productId: $product->product_id,
            status: $product->status,
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'status' => $this->status,
        ];
    }
}
