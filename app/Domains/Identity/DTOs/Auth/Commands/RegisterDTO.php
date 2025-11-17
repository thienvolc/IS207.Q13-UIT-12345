<?php

namespace App\Domains\Identity\DTOs\Auth\Commands;

readonly class RegisterDTO
{
    public function __construct(
        public string  $email,
        public string  $password,
        public ?string $phone,
        public ?string $firstName,
        public ?string $middleName,
        public ?string $lastName,
    )
    {
    }
}
