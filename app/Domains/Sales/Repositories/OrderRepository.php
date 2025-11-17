<?php

namespace App\Domains\Sales\Repositories;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Sales\DTOs\Order\Requests\OrderFilterDTO;
use App\Domains\Sales\Entities\Order;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function searchOrdersForUser(Pageable $pageable, int $userId, ?int $status): LengthAwarePaginator
    {
        return Order::where('user_id', $userId)
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    public function search(Pageable $pageable, OrderFilterDTO $filters): LengthAwarePaginator
    {
        $query = Order::query()->with('items');
        $this->applyFilters($query, $filters);

        return $query
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    private function applyFilters(Builder $query, OrderFilterDTO $f): void
    {
        $query->when($f->query, function ($q, $search) {
            $q->where(fn($s) => $s->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
            );
        });

        $query->when($f->status, fn($q, $v) => $q->where('status', $v));
        $query->when($f->userId, fn($q, $v) => $q->where('user_id', $v));

        $query->when($f->start, fn($q, $v) => $q->whereDate('orders_at', '>=', $v));
        $query->when($f->end, fn($q, $v) => $q->whereDate('orders_at', '<=', $v));

        $query->when($f->min !== null, fn($q) => $q->where('grand_total', '>=', $f->min));
        $query->when($f->max !== null, fn($q) => $q->where('grand_total', '<=', $f->max));
    }

    public function getByIdAndUserWithItemsOrFail(int $orderId, int $userId): Order
    {
        return Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->with('items')
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function getByIdAndUserOrFail(int $userId, int $orderId): Order
    {
        return Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function getLockedByIdAndUserWithProductsOrFail(int $userId, int $orderId): Order
    {
        return Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->with('items.product')
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function getByIdWithItemsOrFail(int $orderId): Order
    {
        return Order::with('items')->find($orderId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    public function getByIdOrFail(int $orderId): ?Order
    {
        return Order::find($orderId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }
}
