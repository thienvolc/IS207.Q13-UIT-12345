<?php

namespace App\Domains\Order\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Order\Entities\Order;

readonly class OrderDTO implements BaseDTO
{
    public function __construct(
        public int     $orderId,
        public int     $userId,
        public float   $total,
        public ?int    $status = null,
        public ?float  $shipping = null,
        /** @var OrderItemDTO[] */
        public array   $items = [],
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {}

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'user_id' => $this->userId,
            'total' => $this->total,
            'status' => $this->status,
            'shipping' => $this->shipping,
            'items' => array_map(fn($it) => $it->toArray(), $this->items),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
