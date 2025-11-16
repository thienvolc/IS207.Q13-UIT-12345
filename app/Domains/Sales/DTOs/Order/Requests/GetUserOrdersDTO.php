<?php

namespace App\Domains\Sales\DTOs\Order\Requests;

readonly class GetUserOrdersDTO
{
    public function __construct(
        public ?int   $status,
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder,
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
