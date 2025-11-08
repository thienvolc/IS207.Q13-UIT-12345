<?php

namespace App\Dtos\Order;

class GetUserOrdersDto
{
    public function __construct(
        public readonly ?int $status,
        public readonly int $offset,
        public readonly int $limit,
        public readonly string $sortField,
        public readonly string $sortOrder,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'] ?? null,
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10,
            sortField: $data['sort_field'] ?? 'orders_at',
            sortOrder: $data['sort_order'] ?? 'desc',
        );
    }
}
