<?php

namespace App\Domains\Identity\DTOs\Auth\Commands;

readonly class SendPasswordResetDTO
{
    public function __construct(
        public string $email,
    )
    {
    }
}
