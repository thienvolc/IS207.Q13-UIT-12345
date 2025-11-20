<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

use App\Domains\Identity\DTOs\User\Queries\SearchUsersDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Foundation\Http\FormRequest;

class SearchUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean',
            'status' => 'nullable|integer|in:1,2,3,4',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): SearchUsersDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new SearchUsersDTO(
            query: get_string($v, 'query'),
            isAdmin: get_bool($v, 'is_admin'),
            status: get_int($v, 'status'),
            page: get_int($v, 'page') ?? 1,
            size: get_int($v, 'size') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }
}

