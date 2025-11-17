<?php

namespace App\Domains\Order\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Order\Entities\OrderItem;

readonly class OrderItemDTO implements BaseDTO
{
    public function __construct(
        public int         $orderItemId,
        public int         $productId,
        public float       $price,
        public int         $quantity,
        public float       $discount,
        public ?string     $note = null,
        public ?ProductDTO $product = null,
    ) {}

    public function toArray(): array
    {
        return [
            'order_item_id' => $this->orderItemId,
            'product_id' => $this->productId,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'note' => $this->note,
            'product' => $this->product?->toArray(),
        ];
    }
}
