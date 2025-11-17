<?php

namespace App\Domains\Order\DTOs\Queries;

readonly class AdminSearchOrdersDTO
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

    public function getFilters(): OrderFilter
    {
        return new OrderFilter(
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
