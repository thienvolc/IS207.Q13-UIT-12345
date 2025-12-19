<?php

namespace App\Domains\Payment\Services;

use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Order\Repositories\OrderRepository;
use App\Domains\Payment\Adapters\PayOSAdapter;
use App\Domains\Payment\Constants\PayOSWebhookResponse;
use App\Domains\Transaction\Constants\TransactionStatus;
use App\Domains\Transaction\Repositories\TransactionRepository;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayOSWebhookHandler
{
    public function __construct(
        private readonly PayOSAdapter $adapter,
        private readonly OrderRepository $orderRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    public function process(array $payload): array
    {
        try {
            $verified = $this->adapter->verifyWebhook($payload);

            return $this->handlePaymentResult($verified);
        } catch (\Exception $e) {
            Log::error('PayOS Webhook Error', ['error' => $e->getMessage(), 'payload' => $payload]);
            return PayOSWebhookResponse::UNKNOWN_ERROR;
        }
    }

    private function handlePaymentResult(object $webhookData): array
    {
        $orderId = (int) $webhookData->orderCode;

        try {
            $order = $this->orderRepository->getByIdOrFail($orderId);
        } catch (BusinessException $e) {
            Log::warning('PayOS Webhook: Order not found', ['orderCode' => $orderId]);
            return PayOSWebhookResponse::ORDER_NOT_FOUND;
        }

        if ($order->status !== OrderStatus::PENDING_PAYMENT) {
            return PayOSWebhookResponse::ORDER_ALREADY_PROCESSED;
        }

        DB::transaction(function () use ($order, $webhookData) {
            $order->update(['status' => OrderStatus::PAID]);

            $this->transactionRepository->updateByOrderId($order->order_id, [
                'status' => TransactionStatus::SUCCESS,
                'code' => $webhookData->reference ?? null,
            ]);
        });

        return PayOSWebhookResponse::SUCCESS;
    }
}

