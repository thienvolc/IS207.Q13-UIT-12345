<?php

namespace App\Domains\Order\DTOs\Queries;

readonly class UserSearchOrdersDTO
{
    public function __construct(
        public ?int   $status,
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder,
    ) {}
}
