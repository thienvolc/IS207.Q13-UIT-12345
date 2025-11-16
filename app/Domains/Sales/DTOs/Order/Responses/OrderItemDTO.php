<?php

namespace App\Domains\Sales\DTOs\Order\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\DTOs\ProductResponseDTO;
use App\Domains\Sales\Entities\OrderItem;

readonly class OrderItemDTO implements BaseDTO
{
    private function __construct(
        public int                 $orderItemId,
        public int                 $productId,
        public float               $price,
        public int                 $quantity,
        public float               $discount,
        public ?string             $note = null,
        public ?ProductResponseDTO $product = null,
    ) {}

    public static function fromModel(OrderItem $orderItem): self
    {
        return new self(
            orderItemId: $orderItem->order_item_id,
            productId: $orderItem->product_id,
            price: (float)$orderItem->price,
            quantity: $orderItem->quantity,
            discount: (float)$orderItem->discount,
            note: $orderItem->note,
            product: self::buildProduct($orderItem)
        );
    }

    private static function buildProduct(OrderItem $orderItem): ?ProductResponseDTO
    {
        if (!$orderItem->relationLoaded('product')) {
            return null;
        }
        return ProductResponseDTO::fromModel($orderItem->product);
    }

    public function toArray(): array
    {
        return [
            'order_item_id' => $this->orderItemId,
            'product_id' => $this->productId,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'note' => $this->note,
            'product' => $this->product ? $this->product->toArray() : null,
        ];
    }
}
