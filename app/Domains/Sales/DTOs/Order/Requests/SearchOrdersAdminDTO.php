<?php

namespace App\Domains\Sales\DTOs\Order\Requests;

readonly class SearchOrdersAdminDTO
{
    public function __construct(
        public ?string $query,
        public ?int    $status,
        public ?int    $userId,
        public ?string $start,
        public ?string $end,
        public ?float  $min,
        public ?float  $max,
        public int     $page,
        public int     $size,
        public string  $sortField,
        public string  $sortOrder,
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

    public function getFilters(): OrderFilterDTO
    {
        return new OrderFilterDTO(
            query: $this->query,
            status: $this->status,
            userId: $this->userId,
            start: $this->start,
            end: $this->end,
            min: $this->min,
            max: $this->max,
        );
    }
}
