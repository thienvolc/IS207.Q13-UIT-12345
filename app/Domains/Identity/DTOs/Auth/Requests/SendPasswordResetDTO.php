<?php

namespace App\Domains\Identity\DTOs\Auth\Requests;

readonly class SendPasswordResetDTO
{
    public function __construct(
        public string $email,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
        );
    }
}
