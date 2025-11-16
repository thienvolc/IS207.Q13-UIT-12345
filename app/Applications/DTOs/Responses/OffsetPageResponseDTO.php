<?php

namespace App\Applications\DTOs\Responses;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T
 */
class OffsetPageResponseDTO
{
    /**
     * @param T[] $data
     */
    public function __construct(
        public array $data,
        public int   $limit,
        public int   $offset,
        public int   $total,
        public bool  $hasMore
    ) {}

    static public function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $data = $paginator->items();
        $limit = $paginator->perPage();
        $offset = ($paginator->currentPage() - 1) * $limit;
        $total = $paginator->total();
        $hasMore = $paginator->hasMorePages();

        return new self($data, $limit, $offset, $total, $hasMore);
    }
}
