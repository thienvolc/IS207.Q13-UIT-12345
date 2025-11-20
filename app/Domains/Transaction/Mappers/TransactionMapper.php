<?php

namespace App\Domains\Transaction\Mappers;

use App\Domains\Order\Entities\Order;
use App\Domains\Transaction\DTOs\Responses\TransactionDTO;
use App\Domains\Transaction\Entities\Transaction;

readonly class TransactionMapper
{
    public function toDTO(Transaction $tran): TransactionDTO
    {
        return new TransactionDTO(
            transactionId: $tran->transaction_id,
            orderId: $tran->order_id,
            amount: (float)$tran->amount,
            content: $tran->content,
            code: $tran->code,
            type: $tran->type,
            mode: $tran->mode,
            status: $tran->status,
            createdAt: $tran->created_at?->toIso8601String(),
            updatedAt: $tran->updated_at?->toIso8601String(),
            order: $this->transformOrder($tran)
        );
    }

    private function transformOrder(Transaction $trans): ?array
    {
        if (!$trans->relationLoaded('order') || !$trans->order) {
            return null;
        }
        $ord = $trans->order;
        return [
            'orderId' => $ord->order_id,
            'userId' => $ord->user_id,
            'grandTotal' => (float)$ord->grand_total,
            'status' => $ord->status,
            'ordersAt' => $ord->orders_at?->toIso8601String(),
            'user' => self::transformUser($ord),
        ];
    }

    private function transformUser(Order $order): ?array
    {
        if (!$order->relationLoaded('user') || !$order->user) {
            return null;
        }
        $u = $order->user;
        return [
            'userId' => $u->user_id,
            'firstName' => $u->first_name,
            'lastName' => $u->last_name,
            'email' => $u->email,
        ];
    }
}
