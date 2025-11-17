<?php

namespace App\Domains\Identity\DTOs\User\Responses;

class UserStatusDTO
{
    public function __construct(
        int $userId,
        int $status
    ) {}
}
