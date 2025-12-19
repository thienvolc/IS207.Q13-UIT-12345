<?php

namespace App\Domains\Payment\Services;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Order\Constants\OrderStatus;
use App\Domains\Order\Repositories\OrderRepository;
use App\Domains\Payment\Adapters\VNPayAdapter;
use App\Domains\Payment\Constants\IpnResponse;
use App\Domains\Payment\Constants\VNPayParams;
use App\Domains\Payment\Constants\VNPayResponseCode;
use App\Domains\Transaction\Constants\TransactionStatus;
use App\Domains\Transaction\Repositories\TransactionRepository;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VNPayIpnHandler
{
    public function __construct(
        private readonly VNPayAdapter $adapter,
        private readonly OrderRepository $orderRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    public function process(array $params): array
    {
        if (!$this->adapter->verifyCallback($params)) {
            Log::warning('VNPay IPN: Invalid signature', $params);
            return IpnResponse::SIGNATURE_FAILED;
        }

        try {
            return $this->handlePaymentResult($params);
        } catch (BusinessException $e) {
            Log::error('VNPay IPN: Business error', ['error' => $e->getMessage(), 'params' => $params]);
            return $this->mapBusinessExceptionToResponse($e);
        } catch (\Exception $e) {
            Log::error('VNPay IPN: Unknown error', ['error' => $e->getMessage(), 'params' => $params]);
            return IpnResponse::UNKNOWN_ERROR;
        }
    }

    private function handlePaymentResult(array $params): array
    {
        $orderId = (int) $params[VNPayParams::TXN_REF];
        $responseCode = $params[VNPayParams::RESPONSE_CODE];
        $transactionNo = $params[VNPayParams::TRANSACTION_NO] ?? null;
        $amount = (int) $params[VNPayParams::AMOUNT] / 100;

        $order = $this->orderRepository->getByIdOrFail($orderId);

        if ($order->status !== OrderStatus::PENDING_PAYMENT) {
            return IpnResponse::ORDER_ALREADY_PAID;
        }

        if ((int) $order->grand_total !== (int) $amount) {
            Log::warning('VNPay IPN: Amount mismatch', [
                'order_amount' => $order->grand_total,
                'vnpay_amount' => $amount,
            ]);
            return IpnResponse::INVALID_AMOUNT;
        }

        if ($responseCode === VNPayResponseCode::SUCCESS) {
            $this->markOrderPaid($order, $transactionNo);
            return IpnResponse::SUCCESS;
        }

        $this->markPaymentFailed($order, $responseCode);
        return IpnResponse::SUCCESS;
    }

    private function markOrderPaid($order, ?string $transactionNo): void
    {
        DB::transaction(function () use ($order, $transactionNo) {
            $order->update(['status' => OrderStatus::PAID]);

            $this->transactionRepository->updateByOrderId($order->order_id, [
                'status' => TransactionStatus::SUCCESS,
                'code' => $transactionNo,
            ]);
        });
    }

    private function markPaymentFailed($order, string $responseCode): void
    {
        DB::transaction(function () use ($order, $responseCode) {
            $this->transactionRepository->updateByOrderId($order->order_id, [
                'status' => TransactionStatus::FAILED,
                'content' => 'VNPay response code: ' . $responseCode,
            ]);
        });
    }

    private function mapBusinessExceptionToResponse(BusinessException $e): array
    {
        if ($e->getResponseCode() === ResponseCode::NOT_FOUND) {
            return IpnResponse::ORDER_NOT_FOUND;
        }
        return IpnResponse::UNKNOWN_ERROR;
    }
}
