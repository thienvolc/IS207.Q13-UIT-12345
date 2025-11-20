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
        $transactions = $this->transactionService->search($request->toDTO());
        return $this->success($transactions);
    }

    public function show(int $trans_id): ResponseDTO
    {
        $transaction = $this->transactionService->getById($trans_id);
        return $this->success($transaction);
    }
}
