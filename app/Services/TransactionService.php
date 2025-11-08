<?php

namespace App\Services;

use App\Constants\ResponseCode;
use App\Constants\TransactionStatus;
use App\Dtos\Transaction\CreateTransactionDto;
use App\Dtos\Transaction\SearchTransactionsDto;
use App\Dtos\Transaction\UpdateTransactionStatusDto;
use App\Exceptions\BusinessException;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Utils\PaginationUtil;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function searchTransactions(SearchTransactionsDto $dto): array
    {
        $query = $this->buildTransactionQuery();
        $this->applyFilters($query, $dto->getFilters());
        $totalCount = $query->count();

        $transactions = $query->orderBy($dto->sortField, $dto->sortOrder)
            ->offset(($dto->page - 1) * $dto->size)
            ->limit($dto->size)
            ->get();

        return PaginationUtil::fromPageSize(
            TransactionResource::collection($transactions),
            $dto->page,
            $dto->size,
            $totalCount
        );
    }

    public function getTransactionById(int $transactionId): array
    {
        $transaction = $this->findTransactionById($transactionId);
        return TransactionResource::transform($transaction);
    }

    public function createTransaction(CreateTransactionDto $dto): array
    {
        $transaction = DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $transaction = Transaction::create($data);
            $transaction->load(['order', 'order.user']);
            return $transaction;
        });

        return TransactionResource::transform($transaction);
    }

    public function updateTransactionStatus(UpdateTransactionStatusDto $dto): array
    {
        $transaction = DB::transaction(function () use ($dto) {
            $transaction = $this->findTransactionById($dto->transactionId);
            $this->updateStatus($transaction, $dto->status);
            $transaction->load(['order', 'order.user']);
            return $transaction;
        });

        return TransactionResource::transform($transaction);
    }

    private function buildTransactionQuery()
    {
        return Transaction::query()->with(['order', 'order.user']);
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['user_id'])) {
            $query->whereHas('order', function($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            });
        }

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
    }

    private function findTransactionById(int $transactionId): Transaction
    {
        $transaction = Transaction::with(['order', 'order.user'])->find($transactionId);

        if (!$transaction) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $transaction;
    }

    private function prepareCreateData(CreateTransactionDto $dto): array
    {
        $data = $dto->toArray();
        $data['status'] = $dto->status ?? TransactionStatus::INITIATED;
        return $data;
    }

    private function updateStatus(Transaction $transaction, int $status): void
    {
        $transaction->update(['status' => $status]);
    }
}
