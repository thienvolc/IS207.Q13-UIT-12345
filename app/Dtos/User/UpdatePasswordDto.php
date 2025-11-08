<?php

namespace App\Dtos\User;

class UpdatePasswordDto
{
    public function __construct(
        public readonly string $oldPassword,
        public readonly string $newPassword,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            oldPassword: $data['old_password'],
            newPassword: $data['new_password'],
        );
    }
}
