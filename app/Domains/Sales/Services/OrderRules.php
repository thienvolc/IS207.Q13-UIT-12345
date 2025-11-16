<?php

namespace App\Domains\Sales\Services;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Sales\Constants\OrderConfig;
use App\Domains\Sales\Constants\OrderStatus;
use App\Domains\Sales\Entities\Order;
use App\Exceptions\BusinessException;

readonly class OrderRules
{
    public function assertCanUpdateShipping(Order $order): void
    {
        if (!$this->isUpdatableOrder($order)) {
            throw new BusinessException(ResponseCode::ORDER_CANNOT_UPDATE,
                ['status' => $order->status]);
        }
    }

    public function assertCanCancelOrder(Order $order): void
    {
        if (!$this->isCancelableStatus($order->status)) {
            throw new BusinessException(ResponseCode::ORDER_CANNOT_CANCEL,
                ['status' => $order->status]);
        }
    }

    private function isUpdatableOrder(Order $order): bool
    {
        return $this->isUpdatableStatus($order->status) &&
            !$this->isPastUpdateDeadline($order->orders_at);
    }

    private function isUpdatableStatus(int $status): bool
    {
        $updatableStatuses = [
            OrderStatus::PENDING_PAYMENT,
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
        ];

        return in_array($status, $updatableStatuses);
    }

    private function isPastUpdateDeadline($ordersAt): bool
    {
        $updateDeadline = $ordersAt->copy()->addHours(OrderConfig::MAX_TIME_UPDATE_ORDER_HOURS);

        return now()->greaterThan($updateDeadline);
    }

    private function isCancelableStatus(int $status): bool
    {
        $cancelableStatuses = [
            OrderStatus::PENDING_PAYMENT,
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
        ];

        return in_array($status, $cancelableStatuses);
    }
}
