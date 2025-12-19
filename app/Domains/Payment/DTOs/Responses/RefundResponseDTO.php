<?php

namespace App\Domains\Payment\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class RefundResponseDTO implements BaseDTO
{
    public function __construct(
        public bool $success,
        public string $responseCode,
        public ?string $message = null,
        public ?string $transactionNo = null,
        public ?string $responseId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'response_code' => $this->responseCode,
            'message' => $this->message,
            'transaction_no' => $this->transactionNo,
            'response_id' => $this->responseId,
        ];
    }
}
