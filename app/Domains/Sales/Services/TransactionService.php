<?php

namespace App\Domains\Sales\Services;

use App\Applications\DTOs\Responses\PageResponseDTO;
use App\Domains\Sales\Constants\TransactionStatus;
use App\Domains\Sales\DTOs\Transaction\Requests\CreateTransactionDTO;
use App\Domains\Sales\DTOs\Transaction\Requests\SearchTransactionsDTO;
use App\Domains\Sales\DTOs\Transaction\Requests\UpdateTransactionStatusDTO;
use App\Domains\Sales\DTOs\Transaction\Responses\TransactionResponseDTO;
use App\Domains\Sales\Repositories\TransactionRepository;
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

    public function getById(int $transactionId): TransactionResponseDTO
    {
        $transaction = $this->repository->getByIdOrFail($transactionId);
        return TransactionResponseDTO::fromModel($transaction);
    }

    public function create(CreateTransactionDTO $dto): TransactionResponseDTO
    {
        $transaction = DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $transaction = $this->repository->create($data);

            $transaction->load(['order', 'order.user']);

            return $transaction;
        });

        return TransactionResponseDTO::fromModel($transaction);
    }

    public function updateStatus(UpdateTransactionStatusDTO $dto): TransactionResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $transaction = $this->repository->getByIdOrFail($dto->transactionId);

            $transaction->update(['status' => $dto->status]);
            $transaction->load(['order', 'order.user']);

            return TransactionResponseDTO::fromModel($transaction);
        });
    }

    private function prepareCreateData(CreateTransactionDTO $dto): array
    {
        $data = $dto->toArray();
        $data['status'] = $dto->status ?? TransactionStatus::INITIATED;
        return $data;
    }
}
