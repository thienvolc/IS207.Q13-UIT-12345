<?php

namespace App\Domains\Sales\DTOs\Order\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\Entities\Order;

readonly class ShortOrderResponseDTO implements BaseDTO
{
    private function __construct(
        public int     $orderId,
        public int     $userId,
        public float   $total,
        public ?int    $status = null,
        public ?string $createdAt = null,
    ) {}

    public static function fromModel(Order $order): self
    {
        return new self(
            orderId: $order->order_id,
            userId: $order->user_id,
            total: (float)$order->total,
            status: $order->status,
            createdAt: optional($order->created_at)?->toDateTimeString(),
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'user_id' => $this->userId,
            'total' => $this->total,
            'status' => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}
