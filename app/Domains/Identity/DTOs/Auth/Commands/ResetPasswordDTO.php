<?php

namespace App\Domains\Identity\DTOs\Auth\Commands;

readonly class ResetPasswordDTO
{
    public function __construct(
        public string $email,
        public string $passwordResetToken,
        public string $newPassword,
    )
    {
    }
}
