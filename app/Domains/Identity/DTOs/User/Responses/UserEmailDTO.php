<?php

namespace App\Domains\Identity\DTOs\User\Responses;

class UserEmailDTO
{
    public function __construct(
        public int    $userId,
        public string $email
    )
    {
    }
}
