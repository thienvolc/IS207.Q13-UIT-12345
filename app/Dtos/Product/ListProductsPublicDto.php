<?php

namespace App\Dtos\Product;

class ListProductsPublicDto
{
    public function __construct(
        public readonly int $limit = 20,
        public readonly int $offset = 0,
        public readonly ?string $sortField = 'created_at',
        public readonly ?string $sortOrder = 'desc',
        public readonly array $filters = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['limit'] ?? 20,
            $data['offset'] ?? 0,
            $data['sortField'] ?? 'created_at',
            $data['sortOrder'] ?? 'desc',
            $data['filters'] ?? []
        );
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
