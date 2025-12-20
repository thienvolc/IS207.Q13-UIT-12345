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

use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private readonly VNPayAdapter $vnPayAdapter,
        private readonly PayOSAdapter $payOSAdapter,
        private readonly TransactionRepository $transactionRepository,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    // ... (existing methods)

    public function processVNPayReturn(array $params): bool
    {
        // 1. Verify Signature
        if (!$this->verifyVNPayCallback($params)) {
            Log::warning('VNPay Return Checksum Failed', $params);
            return false;
        }

        // 2. Check Response Code
        if (($params['vnp_ResponseCode'] ?? '') === '00') {
            $orderId = (int) ($params['vnp_TxnRef'] ?? 0);
            if ($orderId <= 0)
                return false;

            // 3. Update Transaction
            $txn = $this->transactionRepository->updateByOrderId($orderId, [
                'status' => TransactionStatus::SUCCESS,
                'code' => $params['vnp_TransactionNo'] ?? null,
            ]);

            if ($txn) {
                $this->transactionRepository->cancelPendingTransactions($orderId, $txn->transaction_id);
            }

            // 4. Update Order Status
            try {
                $order = $this->orderRepository->getByIdOrFail($orderId);
                // Only update if waiting for payment
                if ($order->status === OrderStatus::PENDING_PAYMENT) {
                    $order->update(['status' => OrderStatus::PAID]);
                }
            } catch (\Exception $e) {
                Log::error('Order Update Failed in VNPay Return', ['id' => $orderId, 'error' => $e->getMessage()]);
            }

            return true;
        }

        return false;
    }

    public function updateStatusByOrderId(int $orderId, int $status): void
    {
        $txn = $this->transactionRepository->updateByOrderId($orderId, ['status' => $status]);

        if ($txn && $status === TransactionStatus::SUCCESS) {
            $this->transactionRepository->cancelPendingTransactions($orderId, $txn->transaction_id);
        }
    }

    public function processPayOSReturn(array $params): bool
    {
        $orderCode = $params['orderCode'] ?? $params['order_id'] ?? null;
        $status = $params['status'] ?? '';
        $code = $params['code'] ?? null;

        if (!$orderCode) {
            return false;
        }

        // Check for success status (PAID/success) and success code (00/0/null)
        // Note: PayOS return might not include code, or code=00
        $isSuccess = ($status === 'PAID' || $status === 'success')
            && ($code === '00' || $code === '0' || $code === 0 || $code === null);

        if ($isSuccess) {
            // orderCode IS transaction_id
            $txnId = (int) $orderCode;

            try {
                // Update Transaction
                $txn = $this->transactionRepository->getByIdOrFail($txnId);
                $orderId = $txn->order_id;

                $txn->update([
                    'status' => TransactionStatus::SUCCESS,
                    'code' => $params['id'] ?? null,
                ]);

                $this->transactionRepository->cancelPendingTransactions($orderId, $txn->transaction_id);

                // Update Order Status
                $order = $this->orderRepository->getByIdOrFail($orderId);
                if ($order->status === OrderStatus::PENDING_PAYMENT) {
                    $order->update(['status' => OrderStatus::PAID]);
                }
            } catch (\Exception $e) {
                Log::error('Order Update Failed in PayOS Return', ['txnId' => $txnId, 'error' => $e->getMessage()]);
            }

            return true;
        }

        return false;
    }

    public function initVNPayPayment(Order $order, string $ipAddress): InitPaymentResponseDTO
    {
        // Cancel any existing pending transactions to avoid confusion
        $this->transactionRepository->cancelPendingTransactions($order->order_id);

        $this->createTransaction($order, PaymentProvider::VNPAY);

        // Generate unique TxnRef to allow retries (avoid duplicate ref at Gateway)
        $txnRef = $order->order_id . '_' . time();

        $dto = new InitPaymentDTO(
            orderId: $order->order_id,
            amount: (string) $order->grand_total,
            orderInfo: 'Thanh toan don hang #' . $order->order_id,
            ipAddress: $ipAddress,
            txnRef: $txnRef,
        );

        return $this->vnPayAdapter->initPayment($dto);
    }

    public function initPayOSPayment(Order $order): InitPaymentResponseDTO
    {
        // Cancel any existing pending transactions
        $this->transactionRepository->cancelPendingTransactions($order->order_id);

        $txn = $this->createTransaction($order, PaymentProvider::PAYOS);

        // Use Transaction ID as Unique Order Code (Safe Integer)
        $txnRef = (string) $txn->transaction_id;

        $dto = new InitPaymentDTO(
            orderId: $order->order_id,
            amount: (string) $order->grand_total,
            orderInfo: 'Thanh toan don hang #' . $order->order_id,
            ipAddress: '127.0.0.1',
            txnRef: $txnRef,
        );

        return $this->payOSAdapter->initPayment($dto);
    }

    public function initCODPayment(Order $order): void
    {
        $this->createTransaction($order, PaymentProvider::COD);
    }

    public function verifyVNPayCallback(array $params): bool
    {
        return $this->vnPayAdapter->verifyCallback($params);
    }

    private function createTransaction(Order $order, string $provider): \App\Domains\Transaction\Entities\Transaction
    {
        return $this->transactionRepository->create([
            'order_id' => $order->order_id,
            'amount' => $order->grand_total,
            'content' => 'Thanh toan don hang #' . $order->order_id,
            'type' => TransactionType::PAYMENT,
            'mode' => $provider,
            'status' => TransactionStatus::INITIATED,
        ]);
    }
}

