<?php

namespace App\Domains\Catalog\DTOs\Product\Requests;

readonly class SearchProductsAdminDTO
{
    public function __construct(
        public ?string $query,
        public ?int    $category,
        public ?float  $priceMin,
        public ?float  $priceMax,
        public int     $page,
        public int     $size,
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
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 10,
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
