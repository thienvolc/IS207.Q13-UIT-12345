<?php

namespace App\Dtos\Auth;

class RegisterDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $phone,
        public readonly ?string $firstName,
        public readonly ?string $middleName,
        public readonly ?string $lastName,
    ) {}

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
