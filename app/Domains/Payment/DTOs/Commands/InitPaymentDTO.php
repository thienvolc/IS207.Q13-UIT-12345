<?php

namespace App\Domains\Payment\DTOs\Commands;

readonly class InitPaymentDTO
{
    public function __construct(
        public int $orderId,
        public string $amount,
        public string $orderInfo,
        public string $ipAddress,
        public ?string $txnRef = null,
    ) {
    }
}
