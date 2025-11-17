<?php

namespace App\Domains\Identity\DTOs\Auth\Commands;

readonly class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
    )
    {
    }
}
