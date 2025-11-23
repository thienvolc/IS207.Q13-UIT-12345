<?php

namespace App\Domains\Common\DTOs;

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
        public int   $count,
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
        $count = $paginator->count();

        return new self($data, $page, $size, $count, $total, $hasMore);
    }

    public function toArray(): array
    {
        return [
            'data' => array_map(fn($item) => $item instanceof BaseDTO ? $item->toArray() : $item, $this->data),
            'page' => $this->page,
            'size' => $this->size,
            'count' => $this->count,
            'total' => $this->total,
            'has_more' => $this->hasMore,
        ];
    }
}
