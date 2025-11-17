<?php

namespace App\Infra\Utils\Pagination;

class PaginationUtil
{
    public static function getSortFieldAndOrder(string $sort): array
    {
        [$field, $order] = explode(':', $sort);
        return [$field, $order];
    }

    public static function offsetToPage(int $offset, int $limit): int
    {
        return (int)floor($offset / $limit) + 1;
    }
}
