<?php

namespace App\Http\Controllers\Admin;

use App\Dtos\Transaction\SearchTransactionsDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Transaction\SearchTransactionsRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends AppController
{
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function index(SearchTransactionsRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchTransactionsDto::fromArray([
            'userId' => $request->input('user_id'),
            'orderId' => $request->input('order_id'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $transactions = $this->transactionService->searchTransactions($dto);

        return $this->success($transactions);
    }
}
