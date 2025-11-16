<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

readonly class SearchProductsPublicDTO
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

    public static function fromArray(array $data): self
    {
        return new self(
            query: $data['query'] ?? null,
            category: $data['category'] ?? null,
            priceMin: $data['price_min'] ?? null,
            priceMax: $data['price_max'] ?? null,
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }

    public function getFilters(): array
    {
        return array_filter([
            'query' => $this->query,
            'category' => $this->category,
            'price_min' => $this->priceMin,
            'price_max' => $this->priceMax,
        ], fn($value) => $value !== null);
    }
}
