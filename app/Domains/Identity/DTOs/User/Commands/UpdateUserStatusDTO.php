<?php

namespace App\Domains\Identity\DTOs\User\Commands;

readonly class UpdateUserStatusDTO
{
    public function __construct(
        public int $userId,
        public int $status,
    )
    {
    }
}
