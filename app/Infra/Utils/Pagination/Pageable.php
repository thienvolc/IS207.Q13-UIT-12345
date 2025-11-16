<?php

namespace App\Infra\Utils\Pagination;

readonly class Pageable
{
    private function __construct(
        public int  $page,
        public int  $size,
        public Sort $sort
    ) {}

    static public function of(int $page, int $size, Sort $sort): Pageable
    {
        return new Pageable($page, $size, $sort);
    }
}
