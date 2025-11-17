<?php

namespace App\Domains\Order\DTOs\Queries;

readonly class OrderFilter
{
    public function __construct(
        public ?string $query,
        public ?int $status,
        public ?int $userId,
        public ?string $start,
        public ?string $end,
        public ?float $min,
        public ?float $max,
    ) {}
}
