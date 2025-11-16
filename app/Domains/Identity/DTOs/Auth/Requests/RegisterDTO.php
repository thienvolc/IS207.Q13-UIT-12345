<?php

namespace App\Domains\Identity\DTOs\Auth\Requests;

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

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            phone: $data['phone'] ?? null,
            firstName: $data['first_name'] ?? null,
            middleName: $data['middle_name'] ?? null,
            lastName: $data['last_name'] ?? null,
        );
    }
}
