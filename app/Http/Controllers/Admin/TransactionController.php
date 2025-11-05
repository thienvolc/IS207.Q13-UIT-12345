<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppController;
use App\Http\Requests\Transaction\SearchTransactionsRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends AppController
{
    public function __construct(
        private TransactionService $transactionService
    ) {}

    /**
     * GET /admin/transactions
     */
    public function index(SearchTransactionsRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $filters = [
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status'),
            'order_id' => $request->input('order_id'),
            'type' => $request->input('type'),
        ];

        $transactions = $this->transactionService->searchTransactions(
            $filters,
            $request->getPage(),
            $request->getSize(),
            $sortField,
            $sortOrder
        );

        return $this->successResponse($transactions);
    }
}
