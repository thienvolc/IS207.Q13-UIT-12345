<?php

namespace App\Domains\Transaction\Repositories;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Transaction\DTOs\Queries\TransactionFilter;
use App\Domains\Transaction\Entities\Transaction;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository
{
    public function search(Pageable $pageable, TransactionFilter $filters): LengthAwarePaginator
    {
        $query = Transaction::query()->with(['order', 'order.user']);
        $this->applyFilters($query, $filters);

        return $query
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    private function applyFilters($query, TransactionFilter $f): void
    {
        $query->when($f->userId, fn($q, $v) =>
            $q->whereHas('order', fn($o) => $o->where('user_id', $v))
        );
        $query->when($f->orderId, fn($q, $v) => $q->where('order_id', $v));
        $query->when($f->status, fn($q, $v) => $q->where('status', $v));
        $query->when($f->type, fn($q, $v) => $q->where('type', $v));

        $query->when($f->start, fn($q, $v) => $q->whereDate('updated_at', '>=', $v));
        $query->when($f->end, fn($q, $v) => $q->whereDate('updated_at', '<=', $v));

        $query->when($f->min, fn($q, $v) => $q->where('amount', '>=', $v));
        $query->when($f->max, fn($q, $v) => $q->where('amount', '<=', $v));
    }

    public function getByIdOrFail(int $transactionId): Transaction
    {
        return Transaction::with(['order', 'order.user'])->find($transactionId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }
}
