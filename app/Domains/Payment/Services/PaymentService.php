<?php

namespace App\Domains\Payment\Services;

use App\Domains\Order\Entities\Order;
use App\Domains\Payment\Adapters\PayOSAdapter;
use App\Domains\Payment\Adapters\VNPayAdapter;
use App\Domains\Payment\Constants\PaymentProvider;
use App\Domains\Payment\DTOs\Commands\InitPaymentDTO;
use App\Domains\Payment\DTOs\Responses\InitPaymentResponseDTO;
use App\Domains\Transaction\Constants\TransactionStatus;
use App\Domains\Transaction\Constants\TransactionType;
use App\Domains\Transaction\Repositories\TransactionRepository;

class PaymentService
{
    public function __construct(
        private readonly VNPayAdapter $vnPayAdapter,
        private readonly PayOSAdapter $payOSAdapter,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    public function initVNPayPayment(Order $order, string $ipAddress): InitPaymentResponseDTO
    {
        $this->createTransaction($order, PaymentProvider::VNPAY);

        $dto = new InitPaymentDTO(
            orderId: $order->order_id,
            amount: (string) $order->grand_total,
            orderInfo: 'Thanh toan don hang #' . $order->order_id,
            ipAddress: $ipAddress,
        );

        return $this->vnPayAdapter->initPayment($dto);
    }

    public function initPayOSPayment(Order $order): InitPaymentResponseDTO
    {
        $this->createTransaction($order, PaymentProvider::PAYOS);

        $dto = new InitPaymentDTO(
            orderId: $order->order_id,
            amount: (string) $order->grand_total,
            orderInfo: 'Thanh toan don hang #' . $order->order_id,
            ipAddress: '127.0.0.1',
        );

        return $this->payOSAdapter->initPayment($dto);
    }

    private function createTransaction(Order $order, string $provider): void
    {
        $this->transactionRepository->create([
            'order_id' => $order->order_id,
            'amount' => $order->grand_total,
            'content' => 'Thanh toan don hang #' . $order->order_id,
            'type' => TransactionType::PAYMENT,
            'mode' => $provider,
            'status' => TransactionStatus::INITIATED,
        ]);
    }
}

