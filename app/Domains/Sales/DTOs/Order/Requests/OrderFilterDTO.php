<?php

namespace App\Domains\Sales\DTOs\Order\Requests;

readonly class OrderFilterDTO
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
