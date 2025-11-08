<?php

namespace App\Dtos\Product;

readonly class UpdateStatusDto
{
    public function __construct(
        public int $productId,
        public int $status
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            status: $data['status']
        );
    }
}
