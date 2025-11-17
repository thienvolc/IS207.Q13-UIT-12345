<?php

namespace App\Domains\Transaction\DTOs\Queries;

readonly class SearchTransactionsDTO
{
    public function __construct(
        public ?int   $userId,
        public ?int   $orderId,
        public ?int   $status,
        public ?int   $type,
        public int    $page,
        public int    $size,
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

    public function getFilters(): TransactionFilter
    {
        return new TransactionFilter(
            userId: $this->userId,
            orderId: $this->orderId,
            status: $this->status,
            type: $this->type
        );
    }
}
