<?php

namespace App\Domains\Identity\DTOs\User\Commands;

readonly class AssignRolesDTO
{
    public function __construct(
        public int   $userId,
        public array $roleIds,
    ) {}
}
