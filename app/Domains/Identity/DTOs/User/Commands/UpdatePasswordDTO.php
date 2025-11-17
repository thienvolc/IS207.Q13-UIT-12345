<?php

namespace App\Domains\Identity\DTOs\User\Commands;

readonly class UpdatePasswordDTO
{
    public function __construct(
        public string $oldPassword,
        public string $newPassword,
    )
    {
    }
}
