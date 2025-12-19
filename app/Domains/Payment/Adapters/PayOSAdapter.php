<?php

namespace App\Domains\Payment\Adapters;

use App\Domains\Payment\Constants\PayOSParams;
use App\Domains\Payment\DTOs\Commands\InitPaymentDTO;
use App\Domains\Payment\DTOs\Responses\InitPaymentResponseDTO;
use Illuminate\Support\Facades\Log;
use PayOS\PayOS;

class PayOSAdapter implements PaymentAdapterInterface
{
    private PayOS $payOS;

    public function __construct()
    {
        $this->payOS = new PayOS(
            clientId: config('payos.client_id'),
            apiKey: config('payos.api_key'),
            checksumKey: config('payos.checksum_key'),
        );
    }

    public function initPayment(InitPaymentDTO $dto): InitPaymentResponseDTO
    {
        $paymentData = [
            PayOSParams::ORDER_CODE => $dto->orderId,
            PayOSParams::AMOUNT => (int) $dto->amount,
            PayOSParams::DESCRIPTION => $dto->orderInfo,
            PayOSParams::ITEMS => [
                [
                    PayOSParams::ITEM_NAME => 'Don hang #' . $dto->orderId,
                    PayOSParams::ITEM_QUANTITY => 1,
                    PayOSParams::ITEM_PRICE => (int) $dto->amount,
                ]
            ],
            PayOSParams::RETURN_URL => config('payos.return_url'),
            PayOSParams::CANCEL_URL => config('payos.cancel_url'),
        ];

        $paymentLink = $this->payOS->paymentRequests->create($paymentData);

        Log::info('PayOS Create Payment Response', $paymentLink);

        return new InitPaymentResponseDTO(
            orderId: $dto->orderId,
            paymentUrl: $paymentLink[PayOSParams::CHECKOUT_URL],
        );
    }

    public function verifyCallback(array $params): bool
    {
        try {
            $this->payOS->webhooks->verify($params);
            return true;
        } catch (\Exception $e) {
            Log::error('PayOS Webhook Verify Failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function verifyWebhook(array $payload): object
    {
        return $this->payOS->webhooks->verify($payload);
    }
}
