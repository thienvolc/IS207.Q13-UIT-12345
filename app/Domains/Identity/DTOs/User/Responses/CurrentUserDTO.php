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
        public ?array $profile = null,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_admin' => $this->isAdmin,
            'status' => $this->status,
            'profile' => $this->profile,
        ];
    }
}
