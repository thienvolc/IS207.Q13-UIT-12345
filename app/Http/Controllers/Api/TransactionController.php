<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Transaction\DTOs\FormRequest\SearchTransactionsRequest;
use App\Domains\Transaction\DTOs\Queries\SearchTransactionsDTO;
use App\Domains\Transaction\Services\TransactionService;
use App\Http\Controllers\AppController;

class TransactionController extends AppController
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {}

    public function index(SearchTransactionsRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchTransactionsDTO::fromArray([
            'userId' => $request->input('user_id'),
            'orderId' => $request->input('order_id'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $transactions = $this->transactionService->search($dto);

        return $this->success($transactions);
    }

    public function show(int $transactionId): ResponseDTO
    {
        $transaction = $this->transactionService->getById($transactionId);

        return $this->success($transaction);
    }
}
