<?php

namespace App\Domains\Order\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Order\Entities\Order;

readonly class OrderSummaryDTO implements BaseDTO
{
    public function __construct(
        public int     $orderId,
        public int     $userId,
        public float   $total,
        public ?int    $status = null,
        public ?string $createdAt = null,
    ) {}

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
