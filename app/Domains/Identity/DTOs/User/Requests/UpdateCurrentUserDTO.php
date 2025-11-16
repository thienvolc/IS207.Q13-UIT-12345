<?php

namespace App\Domains\Identity\DTOs\User\Requests;

readonly class UpdateCurrentUserDTO
{
    public function __construct(
        public ?string $firstName,
        public ?string $middleName,
        public ?string $lastName,
        public ?string $phone,
    )
    {
    }

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
