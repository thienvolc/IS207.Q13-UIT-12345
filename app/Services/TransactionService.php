<?php

namespace App\Services;

use App\Models\Transaction;
use App\Constants\ResponseCode;
use App\Http\Resources\TransactionResource;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Search transactions for admin
     */
    public function searchTransactions(
        array $filters,
        int $page = 1,
        int $size = 10,
        string $sortField = 'created_at',
        string $sortOrder = 'desc'
    ): array {
        $query = Transaction::query()->with(['order', 'order.user']);

        // Apply filters
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

        $totalCount = $query->count();
        $totalPage = (int)ceil($totalCount / $size);

        $transactions = $query->orderBy($sortField, $sortOrder)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get();

        return [
            'data' => TransactionResource::collection($transactions),
            'current_page' => $page,
            'total_page' => $totalPage,
            'total_count' => $totalCount,
            'has_more' => $page < $totalPage,
        ];
    }

    /**
     * Get transaction by ID
     */
    public function getTransactionById(int $transactionId): array
    {
        $transaction = Transaction::with(['order', 'order.user'])->find($transactionId);

        if (!$transaction) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return TransactionResource::transform($transaction);
    }

    /**
     * Create transaction for order
     */
    public function createTransaction(int $orderId, array $data): array
    {
        return DB::transaction(function () use ($orderId, $data) {
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'amount' => $data['amount'],
                'content' => $data['content'] ?? null,
                'code' => $data['code'] ?? null,
                'type' => $data['type'],
                'mode' => $data['mode'] ?? null,
                'status' => $data['status'] ?? \App\Constants\TransactionStatus::INITIATED,
            ]);

            $transaction->load(['order', 'order.user']);

            return TransactionResource::transform($transaction);
        });
    }

    /**
     * Update transaction status
     */
    public function updateTransactionStatus(int $transactionId, int $status): array
    {
        return DB::transaction(function () use ($transactionId, $status) {
            $transaction = Transaction::find($transactionId);

            if (!$transaction) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $transaction->update(['status' => $status]);
            $transaction->load(['order', 'order.user']);

            return TransactionResource::transform($transaction);
        });
    }
}

