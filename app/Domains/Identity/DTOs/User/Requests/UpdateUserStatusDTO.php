<?php

namespace App\Domains\Identity\DTOs\User\Requests;

readonly class UpdateUserStatusDTO
{
    public function __construct(
        public int $userId,
        public int $status,
    )
    {
    }

    public static function fromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            status: $data['status'],
        );
    }
}
