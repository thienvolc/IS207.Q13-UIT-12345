<?php

namespace App\Dtos\User;

class CreateUserDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $phone,
        public readonly ?string $firstName,
        public readonly ?string $middleName,
        public readonly ?string $lastName,
        public readonly bool $isAdmin,
        public readonly array $roles,
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
