<?php

namespace App\Http\Controllers\Api\Admin\Sales;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Transaction\DTOs\FormRequest\SearchTransactionsRequest;
use App\Domains\Transaction\Services\TransactionService;
use App\Http\Controllers\AppController;

class TransactionAdminController extends AppController
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {}

    /**
     * [GET] /api/admin/transactions
     */
    public function index(SearchTransactionsRequest $request): ResponseDTO
    {
        $transactions = $this->transactionService->search($request->toDTO());
        return $this->success($transactions);
    }

    /**
     * [GET] /api/admin/transactions/{trans_id}
     */
    public function show(int $trans_id): ResponseDTO
    {
        $transaction = $this->transactionService->getById($trans_id);
        return $this->success($transaction);
    }
}
