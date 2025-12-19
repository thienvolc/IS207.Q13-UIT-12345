<?php

namespace App\Domains\Payment\Adapters;

use App\Domains\Payment\DTOs\Commands\InitPaymentDTO;
use App\Domains\Payment\DTOs\Responses\InitPaymentResponseDTO;

interface PaymentAdapterInterface
{
    public function initPayment(InitPaymentDTO $dto): InitPaymentResponseDTO;

    public function verifyCallback(array $params): bool;
}
