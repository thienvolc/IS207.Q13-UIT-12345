<?php

namespace App\Domains\Payment\Services;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Order\Repositories\OrderRepository;
use App\Domains\Payment\Adapters\VNPayAdapter;
use App\Domains\Payment\Constants\PaymentProvider;
use App\Domains\Payment\DTOs\Commands\RefundRequestDTO;
use App\Domains\Payment\DTOs\Responses\RefundResponseDTO;
use App\Domains\Transaction\Constants\TransactionStatus;
use App\Domains\Transaction\Constants\TransactionType;
use App\Domains\Transaction\Repositories\TransactionRepository;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundService
{
    public function __construct(
        private readonly VNPayAdapter $vnPayAdapter,
        private readonly OrderRepository $orderRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    public function refundOrder(int $orderId, string $ipAddress): RefundResponseDTO
    {
        $order = $this->orderRepository->getByIdOrFail($orderId);

        $this->validateRefundable($order);

        $transaction = $this->transactionRepository->getSuccessfulByOrderId($orderId);
        if (!$transaction) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, ['message' => 'No successful payment found']);
        }

        $refundDto = new RefundRequestDTO(
            orderId: $orderId,
            transactionNo: $transaction->code ?? '',
            transactionDate: $transaction->created_at->format('YmdHis'),
            amount: (string) $order->grand_total,
            createdBy: Auth::user()?->email ?? 'admin',
            ipAddress: $ipAddress,
        );

        return DB::transaction(function () use ($order, $refundDto, $transaction) {
            $this->transactionRepository->create([
                'order_id' => $order->order_id,
                'amount' => $order->grand_total,
                'content' => 'Hoan tien don hang #' . $order->order_id,
                'type' => TransactionType::REFUND,
                'mode' => PaymentProvider::VNPAY,
                'status' => TransactionStatus::PENDING,
            ]);

            $result = $this->vnPayAdapter->refund($refundDto);

            if ($result->success) {
                $order->update(['status' => OrderStatus::REFUNDED]);
                $this->transactionRepository->updateByOrderId($order->order_id, [
                    'status' => TransactionStatus::SUCCESS,
                    'code' => $result->transactionNo,
                ]);
            } else {
                $this->transactionRepository->updateByOrderId($order->order_id, [
                    'status' => TransactionStatus::FAILED,
                    'content' => 'Refund failed: ' . $result->message,
                ]);
            }

            return $result;
        });
    }

    private function validateRefundable($order): void
    {
        $refundableStatuses = [
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
            OrderStatus::SHIPPED,
            OrderStatus::DELIVERED,
        ];

        if (!in_array($order->status, $refundableStatuses)) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, ['message' => 'Order cannot be refunded']);
        }
    }
}
