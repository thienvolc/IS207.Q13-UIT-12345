<?php

namespace App\Applications\DTOs\Responses;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T
 */
class PageResponseDTO
{
    /**
     * @param T[] $data
     */
    public function __construct(
        public array $data,
        public int   $page,
        public int   $size,
        public int   $total,
        public bool  $hasMore
    ) {}

    static public function fromPaginator(LengthAwarePaginator $paginator): self
    {
        $data = $paginator->items();
        $size = $paginator->perPage();
        $page = $paginator->currentPage();
        $total = $paginator->total();
        $hasMore = $paginator->hasMorePages();

        return new self($data, $page, $size, $total, $hasMore);
    }
}
