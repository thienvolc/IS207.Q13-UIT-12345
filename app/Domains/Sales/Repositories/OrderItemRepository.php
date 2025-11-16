<?php

namespace App\Domains\Sales\Repositories;

use App\Domains\Sales\Entities\OrderItem;

class OrderItemRepository
{
    public function create(array $data): OrderItem
    {
        return OrderItem::create($data);
    }
}
