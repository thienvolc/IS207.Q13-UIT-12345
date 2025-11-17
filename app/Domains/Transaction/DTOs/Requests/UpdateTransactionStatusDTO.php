<?php

namespace App\Domains\Transaction\DTOs\Requests;

readonly class UpdateTransactionStatusDTO
{
    public function __construct(
        public int $transactionId,
        public int $status
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            transactionId: $data['transactionId'],
            status: $data['status']
        );
    }
}
