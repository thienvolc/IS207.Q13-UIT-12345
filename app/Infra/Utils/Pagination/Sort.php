<?php

namespace App\Infra\Utils\Pagination;

readonly class Sort
{
    private function __construct(
        public string $by,
        public string $order,
    ) {}

    static public function of($sortBy, $order): Sort
    {
        return new Sort($sortBy, $order);
    }
}
