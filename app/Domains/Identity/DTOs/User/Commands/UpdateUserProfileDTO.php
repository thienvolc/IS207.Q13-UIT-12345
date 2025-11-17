<?php

namespace App\Domains\Identity\DTOs\User\Commands;

readonly class UpdateUserProfileDTO
{
    public function __construct(
        public ?string $firstName,
        public ?string $middleName,
        public ?string $lastName,
        public ?string $phone,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
        ], fn($value) => !is_null($value));
    }
}
