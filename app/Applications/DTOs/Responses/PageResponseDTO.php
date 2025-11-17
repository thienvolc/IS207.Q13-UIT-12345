<?php

namespace App\Applications\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T
 */
class PageResponseDTO implements BaseDTO
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

    static public function fromPaginator(LengthAwarePaginator $paginator, callable $transform = null): self
    {
        $data = $paginator->items();

        if ($transform) {
            $data = array_map($transform, $data);
        }

        $size = $paginator->perPage();
        $page = $paginator->currentPage();
        $total = $paginator->total();
        $hasMore = $paginator->hasMorePages();

        return new self($data, $page, $size, $total, $hasMore);
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'page' => $this->page,
            'size' => $this->size,
            'total' => $this->total,
            'has_more' => $this->hasMore,
        ];
    }
}
