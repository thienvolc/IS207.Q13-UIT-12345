<?php

namespace App\Dtos\User;

class UpdateCurrentUserDto
{
    public function __construct(
        public readonly ?string $firstName,
        public readonly ?string $middleName,
        public readonly ?string $lastName,
        public readonly ?string $phone,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['first_name'] ?? null,
            middleName: $data['middle_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            phone: $data['phone'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
        ];
    }
}
