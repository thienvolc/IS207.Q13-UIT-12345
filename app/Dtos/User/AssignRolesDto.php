<?php

namespace App\Dtos\User;

class AssignRolesDto
{
    public function __construct(
        public readonly int $userId,
        public readonly array $roles,
    ) {}

    public static function fromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            roles: $data['roles'],
        );
    }
}
