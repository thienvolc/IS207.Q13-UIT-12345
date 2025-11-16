<?php

namespace App\Infra\Utils\Pagination;

class PaginationUtil
{
    public static function offsetLimit($data, int $limit, int $offset, int $totalCount): array
    {
        return [
            'data' => $data,
            'limit' => $limit,
            'offset' => $offset,
            'total_count' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
        ];
    }

    public static function fromPageSize($data, int $page, int $size, int $totalCount): array
    {
        $totalPage = (int)ceil($totalCount / $size);

        return [
            'data' => $data,
            'current_page' => $page,
            'total_page' => $totalPage,
            'total_count' => $totalCount,
            'has_more' => $page < $totalPage,
        ];
    }

    public static function offsetToPage(int $offset, int $limit): int
    {
        return (int)floor($offset / $limit) + 1;
    }
}
