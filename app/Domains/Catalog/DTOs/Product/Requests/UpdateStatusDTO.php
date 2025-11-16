<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

readonly class UpdateStatusDTO
{
    public function __construct(
        public int $productId,
        public int $status
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            status: $data['status']
        );
    }
}
