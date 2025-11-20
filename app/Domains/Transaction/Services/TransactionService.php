<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Common\DTOs\PageResponseDTO;
use App\Domains\Transaction\DTOs\Queries\SearchTransactionsDTO;
use App\Domains\Transaction\DTOs\Responses\TransactionDTO;
use App\Domains\Transaction\Mappers\TransactionMapper;
use App\Domains\Transaction\Repositories\TransactionRepository;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\Sort;

readonly class TransactionService
{
    public function __construct(
        private TransactionRepository $repository,
        private TransactionMapper     $mapper
    ) {}

    /**
     * @return PageResponseDTO<TransactionDTO>
     */
    public function search(SearchTransactionsDTO $dto): PageResponseDTO
    {
        $filters = $dto->getFilters();
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $transactions = $this->repository->search($pageable, $filters);

        return PageResponseDTO::fromPaginator($transactions,
            fn($i) => $this->mapper->toDTO($i));
    }

    public function getById(int $transactionId): TransactionDTO
    {
        $transaction = $this->repository->getByIdOrFail($transactionId);
        return $this->mapper->toDTO($transaction);
    }
}
