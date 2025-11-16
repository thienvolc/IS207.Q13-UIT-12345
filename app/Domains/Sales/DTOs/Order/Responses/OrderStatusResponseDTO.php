<?php

namespace App\Domains\Sales\DTOs\Order\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\Entities\Order;

readonly class OrderStatusResponseDTO implements BaseDTO

{

    private function __construct(
        public int    $orderId,
        public ?int   $status,
    ) {}
    public static function fromModel(Order $order): self
    {
        return new self(
            orderId: $order->order_id,
            status: $order->status,
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'status' => $this->status,
        ];
    }
}
