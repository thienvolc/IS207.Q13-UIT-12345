<?php

namespace App\Domains\Identity\DTOs\User\Commands;

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
