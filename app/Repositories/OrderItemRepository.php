<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;

class OrderItemRepository
{
    public function create(array $data): OrderItem
    {
        return OrderItem::create($data);
    }

    public function restoreProductQuantities(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity);
            }
        }
    }
}
