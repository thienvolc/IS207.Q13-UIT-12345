<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function findById(int $orderId): ?Order
    {
        return Order::with('items')->find($orderId);
    }

    public function findByIdWithoutRelations(int $orderId): ?Order
    {
        return Order::find($orderId);
    }

    public function findUserOrder(int $userId, int $orderId): ?Order
    {
        return Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->with('items')
            ->first();
    }

    public function findUserOrderWithoutRelations(int $userId, int $orderId): ?Order
    {
        return Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }

    public function findAndLockUserOrder(int $userId, int $orderId): ?Order
    {
        $order = Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->first();

        if ($order) {
            $order->load('items.product');
        }

        return $order;
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): bool
    {
        return $order->update($data);
    }

    public function getUserOrders(
        int    $userId,
        ?int   $status,
        string $sortField,
        string $sortOrder,
        int    $offset, int $limit
    ): Collection
    {
        $query = Order::where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countUserOrders(int $userId, ?int $status): int
    {
        $query = Order::where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }

    public function searchWithFilters(
        array  $filters,
        string $sortField,
        string $sortOrder,
        int    $offset,
        int    $limit
    ): Collection
    {
        $query = Order::query()->with('items');

        $this->applyFilters($query, $filters);

        return $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countWithFilters(array $filters): int
    {
        $query = Order::query();

        $this->applyFilters($query, $filters);

        return $query->count();
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['query'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['query'] . '%')
                    ->orWhere('last_name', 'like', '%' . $filters['query'] . '%')
                    ->orWhere('phone', 'like', '%' . $filters['query'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['query'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['start'])) {
            $query->whereDate('orders_at', '>=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $query->whereDate('orders_at', '<=', $filters['end']);
        }

        if (isset($filters['min'])) {
            $query->where('grand_total', '>=', $filters['min']);
        }

        if (isset($filters['max'])) {
            $query->where('grand_total', '<=', $filters['max']);
        }
    }
}
