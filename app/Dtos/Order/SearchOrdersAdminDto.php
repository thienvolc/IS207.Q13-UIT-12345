<?php

namespace App\Dtos\Order;

class SearchOrdersAdminDto
{
    public function __construct(
        public readonly ?string $query,
        public readonly ?int $status,
        public readonly ?int $userId,
        public readonly ?string $start,
        public readonly ?string $end,
        public readonly ?float $min,
        public readonly ?float $max,
        public readonly int $page,
        public readonly int $size,
        public readonly string $sortField,
        public readonly string $sortOrder,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            query: $data['query'] ?? null,
            status: $data['status'] ?? null,
            userId: $data['user_id'] ?? null,
            start: $data['start'] ?? null,
            end: $data['end'] ?? null,
            min: $data['min'] ?? null,
            max: $data['max'] ?? null,
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 10,
            sortField: $data['sort_field'] ?? 'orders_at',
            sortOrder: $data['sort_order'] ?? 'desc',
        );
    }

    public function getFilters(): array
    {
        return [
            'query' => $this->query,
            'status' => $this->status,
            'user_id' => $this->userId,
            'start' => $this->start,
            'end' => $this->end,
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}
