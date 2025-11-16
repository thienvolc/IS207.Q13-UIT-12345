<?php

namespace App\Domains\Identity\DTOs\Auth\Requests;

readonly class ResetPasswordDTO
{
    public function __construct(
        public string $email,
        public string $passwordResetToken,
        public string $newPassword,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            passwordResetToken: $data['password_reset_token'],
            newPassword: $data['new_password'],
        );
    }
}
