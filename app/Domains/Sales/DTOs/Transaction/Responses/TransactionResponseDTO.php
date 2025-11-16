<?php

namespace App\Domains\Sales\DTOs\Transaction\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\Entities\Order;
use App\Domains\Sales\Entities\Transaction;

readonly class TransactionResponseDTO implements BaseDTO
{
    public function __construct(
        public int     $transactionId,
        public int     $orderId,
        public float   $amount,
        public ?string $content = null,
        public ?string $code = null,
        public ?int    $type = null,
        public ?string $mode = null,
        public ?int    $status = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public ?array  $order = null,
    ) {}

    public static function fromModel(Transaction $trans): self
    {
        return new self(
            transactionId: $trans->transaction_id,
            orderId: $trans->order_id,
            amount: (float)$trans->amount,
            content: $trans->content,
            code: $trans->code,
            type: $trans->type,
            mode: $trans->mode,
            status: $trans->status,
            createdAt: $trans->created_at?->toIso8601String(),
            updatedAt: $trans->updated_at?->toIso8601String(),
            order: self::loadOrder($trans)
        );
    }

    private static function loadOrder(Transaction $trans): ?array
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
            'user' => self::loadUser($ord),
        ];
    }

    private static function loadUser(Order $order): ?array
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

    public function toArray(): array
    {
        $data = [
            'transaction_id' => $this->transactionId,
            'order_id' => $this->orderId,
            'amount' => $this->amount,
            'content' => $this->content,
            'code' => $this->code,
            'type' => $this->type,
            'mode' => $this->mode,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];

        if ($this->order) {
            $data['order'] = [
                'order_id' => $this->order['orderId'],
                'user_id' => $this->order['userId'],
                'grand_total' => $this->order['grandTotal'],
                'status' => $this->order['status'],
                'orders_at' => $this->order['ordersAt'],
            ];

            if (isset($this->order['user'])) {
                $data['order']['user'] = [
                    'user_id' => $this->order['user']['userId'],
                    'first_name' => $this->order['user']['firstName'],
                    'last_name' => $this->order['user']['lastName'],
                    'email' => $this->order['user']['email'],
                ];
            }
        }

        return $data;
    }
}
