<?php

namespace App\Dtos\Transaction;

readonly class SearchTransactionsDto
{
    public function __construct(
        public ?int $userId,
        public ?int $orderId,
        public ?int $status,
        public ?int $type,
        public int $page,
        public int $size,
        public string $sortField,
        public string $sortOrder
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['userId'] ?? null,
            orderId: $data['orderId'] ?? null,
            status: $data['status'] ?? null,
            type: $data['type'] ?? null,
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }

    public function getFilters(): array
    {
        return array_filter([
            'user_id' => $this->userId,
            'order_id' => $this->orderId,
            'status' => $this->status,
            'type' => $this->type,
        ], fn($value) => $value !== null);
    }
}
