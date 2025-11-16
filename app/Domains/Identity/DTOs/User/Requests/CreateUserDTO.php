<?php

namespace App\Domains\Identity\DTOs\User\Requests;

readonly class CreateUserDTO
{
    public function __construct(
        public string  $email,
        public string  $password,
        public ?string $phone,
        public ?string $firstName,
        public ?string $middleName,
        public ?string $lastName,
        public bool    $isAdmin,
        public array   $roles,
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
            isAdmin: $data['is_admin'] ?? false,
            roles: $data['roles'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'is_admin' => $this->isAdmin,
            'roles' => $this->roles,
        ];
    }
}
