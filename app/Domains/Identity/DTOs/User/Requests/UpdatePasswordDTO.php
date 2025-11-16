<?php

namespace App\Domains\Identity\DTOs\User\Requests;

readonly class UpdatePasswordDTO
{
    public function __construct(
        public string $oldPassword,
        public string $newPassword,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            oldPassword: $data['old_password'],
            newPassword: $data['new_password'],
        );
    }
}
