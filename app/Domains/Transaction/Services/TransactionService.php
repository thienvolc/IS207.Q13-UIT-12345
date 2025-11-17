<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Common\DTOs\PageResponseDTO;
use App\Domains\Transaction\Constants\TransactionStatus;
use App\Domains\Transaction\DTOs\Queries\SearchTransactionsDTO;
use App\Domains\Transaction\DTOs\Requests\CreateTransactionDTO;
use App\Domains\Transaction\DTOs\Requests\UpdateTransactionStatusDTO;
use App\Domains\Transaction\DTOs\Responses\TransactionDTO;
use App\Domains\Transaction\Repositories\TransactionRepository;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\Sort;
use Illuminate\Support\Facades\DB;

readonly class TransactionService
{
    public function __construct(
        private TransactionRepository $repository,
    ) {}

    public function search(SearchTransactionsDTO $dto): PageResponseDTO
    {
        $filters = $dto->getFilters();
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $transactions = $this->repository->search($pageable, $filters);

        return PageResponseDTO::fromPaginator($transactions);
    }

    public function getById(int $transactionId): TransactionDTO
    {
        $transaction = $this->repository->getByIdOrFail($transactionId);
        return TransactionDTO::fromModel($transaction);
    }

    public function create(CreateTransactionDTO $dto): TransactionDTO
    {
        $transaction = DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $transaction = $this->repository->create($data);

            $transaction->load(['order', 'order.user']);

            return $transaction;
        });

        return TransactionDTO::fromModel($transaction);
    }

    public function updateStatus(UpdateTransactionStatusDTO $dto): TransactionDTO
    {
        return DB::transaction(function () use ($dto) {
            $transaction = $this->repository->getByIdOrFail($dto->transactionId);

            $transaction->update(['status' => $dto->status]);
            $transaction->load(['order', 'order.user']);

            return TransactionDTO::fromModel($transaction);
        });
    }

    private function prepareCreateData(CreateTransactionDTO $dto): array
    {
        $data = $dto->toArray();
        $data['status'] = $dto->status ?? TransactionStatus::INITIATED;
        return $data;
    }
}
