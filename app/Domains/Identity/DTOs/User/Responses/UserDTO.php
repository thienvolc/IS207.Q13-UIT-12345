<?php

namespace App\Domains\Identity\DTOs\User\Responses;

use App\Domains\Common\DTOs\BaseDTO;

class UserDTO implements BaseDTO
{
    public function __construct(
        public int     $userId,
        public string  $email,
        public ?string $phone,
        public string  $isAdmin,
        public string  $status,
        public string  $firstName,
        public ?string $middleName,
        public string  $lastName,
        public ?string $avatar,
        public ?string $profile,
        public string  $registeredAt,
        public string  $lastLogin,
        public string  $createdAt,
        public string  $updatedAt,
        public string  $createdBy,
        public string  $updatedBy,
        /** @var RoleDTO[] */
        public array   $roles
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
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'roles' => array_map(fn($role) => $role->toArray(), $this->roles),
        ];
    }
}
