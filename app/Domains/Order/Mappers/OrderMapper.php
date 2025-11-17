<?php

namespace App\Domains\Order\Mappers;

use App\Domains\Order\DTOs\Responses\OrderDTO;
use App\Domains\Order\DTOs\Responses\OrderItemDTO;
use App\Domains\Order\DTOs\Responses\OrderStatusDTO;
use App\Domains\Order\DTOs\Responses\OrderSummaryDTO;
use App\Domains\Order\DTOs\Responses\ProductDTO;
use App\Domains\Order\Entities\Order;
use App\Domains\Order\Entities\OrderItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class OrderMapper
{
    public function toDTO(Order $order): OrderDTO
    {
        $items = $this->toItemDTOs($order->items);

        return new OrderDTO(
            orderId: $order->order_id,
            userId: $order->user_id,
            total: (float)$order->total,
            status: $order->status,
            shipping: $order->shipping,
            items: $items->toArray(),
            createdAt: $order->created_at?->toDateTimeString(),
            updatedAt: $order->updated_at?->toDateTimeString(),
        );
    }

    public function toSummaryDTO(Order $order): OrderSummaryDTO
    {
        return new OrderSummaryDTO(
            orderId: $order->order_id,
            userId: $order->user_id,
            total: (float)$order->total,
            status: $order->status,
            createdAt: $order->created_at?->toDateTimeString(),
        );
    }

    public function toStatusDTO(Order $order): OrderStatusDTO
    {
        return new OrderStatusDTO(
            orderId: $order->order_id,
            status: $order->status,
        );
    }

    /**
     * @param EloquentCollection $orderItems
     * @return Collection<OrderItemDTO>
     */
    public function toItemDTOs(EloquentCollection $orderItems): Collection
    {
        return $orderItems->map(fn($i) => $this->toItemDTO($i));
    }

    public function toItemDTO(OrderItem $orderItem): OrderItemDTO
    {
        $product = null;
        if ($orderItem->relationLoaded('product') && $orderItem->product) {
            $product = $this->toProductDTO($orderItem->product);
        }

        return new OrderItemDTO(
            orderItemId: $orderItem->order_item_id,
            productId: $orderItem->product_id,
            price: (float)$orderItem->price,
            quantity: $orderItem->quantity,
            discount: (float)$orderItem->discount,
            note: $orderItem->note,
            product: $product,
        );
    }

    private function toProductDTO(object $product): ProductDTO
    {
        return new ProductDTO(
            productId: $product->product_id,
            title: $product->title,
            slug: $product->slug,
            thumb: $product->thumb,
            price: (float)$product->price,
            discount: (float)$product->discount,
            quantity: $product->quantity,
            status: $product->status,
        );
    }
}
