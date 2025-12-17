<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Domains\Transaction\DTOs\Queries\SearchTransactionsDTO;
use App\Domains\Transaction\Services\TransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {
    }

    public function show(int $id)
    {
        $transaction = $this->transactionService->getById($id);

        return view('admin.transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function index(Request $request)
    {
        $dto = new SearchTransactionsDTO(
            query: $request->query('query'),
            userId: null,
            orderId: $request->query('order_id'),
            status: $request->query('status'),
            type: null,
            start: $request->query('start_date'),
            end: $request->query('end_date'),
            min: null,
            max: null,
            page: (int) $request->query('page', 1),
            size: 15,
            sortField: 'created_at',
            sortOrder: 'desc'
        );

        $transactions = $this->transactionService->search($dto);

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'filters' => $request->all(),
        ]);
    }
}
