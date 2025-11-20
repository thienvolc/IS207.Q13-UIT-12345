<?php

namespace App\Domains\Transaction\DTOs\Queries;

readonly class SearchTransactionsDTO
{
    public function __construct(
        public ?string $query,
        public ?int    $userId,
        public ?int    $orderId,
        public ?int    $status,
        public ?int    $type,
        public ?string $start,
        public ?string $end,
        public ?float  $min,
        public ?float  $max,
        public int     $page,
        public int     $size,
        public string  $sortField,
        public string  $sortOrder
    ) {}

    public function getFilters(): TransactionFilter
    {
        return new TransactionFilter(
            userId: $this->userId,
            orderId: $this->orderId,
            status: $this->status,
            type: $this->type,
            start: $this->start,
            end: $this->end,
            min: $this->min,
            max: $this->max
        );
    }
}
