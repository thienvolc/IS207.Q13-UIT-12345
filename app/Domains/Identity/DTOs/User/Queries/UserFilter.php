<?php

namespace App\Domains\Identity\DTOs\User\Queries;

class UserFilter
{
    public function __construct(
        public ?string $query,
        public ?bool   $isAdmin,
        public ?int    $status,
    ) {}
}
