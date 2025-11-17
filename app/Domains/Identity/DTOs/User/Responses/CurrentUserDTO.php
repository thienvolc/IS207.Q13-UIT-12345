<?php

namespace App\Domains\Identity\DTOs\User\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class CurrentUserDTO implements BaseDTO
{
    public function __construct(
        public int $userId,
        public string $email,
        public ?string $phone,
        public bool $isAdmin,
        public int $status,
        public ?string $firstName = null,
        public ?string $middleName = null,
        public ?string $lastName = null,
        public ?string $avatar = null,
        public ?array $profile = null,
        public ?string $registeredAt = null,
        public ?string $lastLogin = null,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_admin' => $this->isAdmin,
            'status' => $this->status,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'avatar' => $this->avatar,
            'profile' => $this->profile,
            'registered_at' => $this->registeredAt,
            'last_login' => $this->lastLogin,
        ];
    }
}
