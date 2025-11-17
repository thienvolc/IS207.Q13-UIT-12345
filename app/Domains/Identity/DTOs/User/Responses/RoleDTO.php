<?php

namespace App\Domains\Identity\DTOs\User\Responses;

use App\Domains\Common\DTOs\BaseDTO;

class RoleDTO implements BaseDTO
{
    public function __construct(
        public int $roleId,
        public string $name,
    ) {}

    public function toArray(): array
    {
        return [
            'role_id' => $this->role_id,
            'name' => $this->name,
        ];
    }
}
