<?php

namespace App\Domains\Identity\DTOs\User\Responses;

class UserStatusDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $status
    ) {
    }
}
