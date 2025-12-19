<?php

namespace App\Domains\Payment\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class InitPaymentResponseDTO implements BaseDTO
{
    public function __construct(
        public int $orderId,
        public string $paymentUrl,
    ) {
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'payment_url' => $this->paymentUrl,
        ];
    }
}
