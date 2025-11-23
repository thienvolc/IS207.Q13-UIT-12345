<?php

namespace App\Domains\Common\DTOs;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T
 */
class OffsetPageResponseDTO implements BaseDTO
{
    /**
     * @param T[] $data
     */
    public function __construct(
        public array $data,
        public int   $limit,
        public int   $offset,
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

        $limit = $paginator->perPage();
        $offset = ($paginator->currentPage() - 1) * $limit;
        $total = $paginator->total();
        $hasMore = $paginator->hasMorePages();
        $count = $paginator->count();

        return new self($data, $limit, $offset, $count, $total, $hasMore);
    }

    public function toArray(): array
    {
        return [
            'data' => array_map(fn($item) => $item instanceof BaseDTO ? $item->toArray() : $item, $this->data),
            'limit' => $this->limit,
            'offset' => $this->offset,
            'count' => $this->count,
            'total' => $this->total,
            'has_more' => $this->hasMore,
        ];
    }
}
