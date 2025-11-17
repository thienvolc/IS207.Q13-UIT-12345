<?php

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Entities\OrderItem;

class OrderItemRepository
{
    public function create(array $data): OrderItem
    {
        return OrderItem::create($data);
    }
}
