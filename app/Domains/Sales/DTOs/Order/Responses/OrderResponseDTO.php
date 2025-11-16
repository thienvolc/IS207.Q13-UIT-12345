<?php

namespace App\Domains\Sales\DTOs\Order\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\Entities\Order;

readonly class OrderResponseDTO implements BaseDTO
{
    private function __construct(
        public int     $orderId,
        public int     $userId,
        public float   $total,
        public ?int    $status = null,
        public ?float  $shipping = null,
        /** @var OrderItemDTO[] */
        public array   $items = [],
        public ?string $createdAt = null
    ) {}

    public static function fromModel(Order $order): self
    {
        return new self(
            orderId: $order->order_id,
            userId: $order->user_id,
            total: (float)$order->total,
            status: $order->status,
            shipping: $order->shipping,
            items: self::loadItems($order),
            createdAt: optional($order->created_at)?->toDateTimeString(),
        );
    }

    private static function loadItems(Order $order): array
    {
        return $order->relationLoaded('items')
            ? $order->items->map(fn($i) => OrderItemDTO::fromModel($i))->all()
            : [];
    }

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
        ];
    }
}
