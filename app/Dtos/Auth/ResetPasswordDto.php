<?php

namespace App\Dtos\Auth;

class ResetPasswordDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $passwordResetToken,
        public readonly string $newPassword,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            passwordResetToken: $data['password_reset_token'],
            newPassword: $data['new_password'],
        );
    }
}
