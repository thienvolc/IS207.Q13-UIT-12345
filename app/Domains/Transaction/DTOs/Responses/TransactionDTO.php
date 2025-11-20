<?php

namespace App\Domains\Transaction\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class TransactionDTO implements BaseDTO
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
