<?php

namespace App\Domains\Identity\DTOs\User\Requests;

readonly class AssignRolesDTO
{
    public function __construct(
        public int   $userId,
        public array $roles,
    )
    {
    }

    public static function fromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            roles: $data['roles'],
        );
    }
}
