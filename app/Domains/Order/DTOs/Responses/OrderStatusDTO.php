<?php

namespace App\Domains\Order\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Order\Entities\Order;

readonly class OrderStatusDTO implements BaseDTO
{
    public function __construct(
        public int    $orderId,
        public ?int   $status,
    ) {}

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'status' => $this->status,
        ];
    }
}
