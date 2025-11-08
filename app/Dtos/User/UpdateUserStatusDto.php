<?php

namespace App\Dtos\User;

class UpdateUserStatusDto
{
    public function __construct(
        public readonly int $userId,
        public readonly int $status,
    ) {}

    public static function fromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            status: $data['status'],
        );
    }
}
