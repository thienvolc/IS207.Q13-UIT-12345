<?php

namespace App\Domains\Payment\DTOs\Commands;

readonly class RefundRequestDTO
{
    public function __construct(
        public int $orderId,
        public string $transactionNo,
        public string $transactionDate,
        public string $amount,
        public string $createdBy,
        public string $ipAddress,
        public string $orderInfo = 'Hoan tien don hang',
    ) {
    }
}
