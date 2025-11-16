<?php

namespace App\Domains\Sales\DTOs\Transaction\Requests;

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
