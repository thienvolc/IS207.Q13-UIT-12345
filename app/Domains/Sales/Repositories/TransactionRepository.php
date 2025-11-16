<?php

namespace App\Domains\Sales\Repositories;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Sales\DTOs\Transaction\Requests\TransactionFilterDTO;
use App\Domains\Sales\Entities\Transaction;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function findFilters(Pageable $pageable, TransactionFilterDTO $filters): LengthAwarePaginator
    {
        $query = Transaction::query()->with(['order', 'order.user']);
        $this->applyFilters($query, $filters);

        return $query
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    private function applyFilters(Builder $query, TransactionFilterDTO $f): void
    {
        $query->when($f->userId, fn($q, $v) =>
            $q->whereHas('order', fn($o) => $o->where('user_id', $v))
        );
        $query->when($f->orderId, fn($q, $v) => $q->where('order_id', $v));
        $query->when($f->status, fn($q, $v) => $q->where('status', $v));
        $query->when($f->type, fn($q, $v) => $q->where('type', $v));
    }

    public function findByIdOrFail(int $transactionId): Transaction
    {
        return Transaction::with(['order', 'order.user'])->find($transactionId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }
}
