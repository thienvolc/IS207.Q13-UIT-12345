<?php

namespace App\Domains\Catalog\DTOs\Category\FormRequests;

use App\Domains\Catalog\DTOs\Category\Queries\AdminSearchCategoriesDTO;
use App\Domains\Catalog\DTOs\Category\Queries\PublicSearchCategoriesDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Foundation\Http\FormRequest;

class SearchCategoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',
            'level' => 'nullable|integer|min:1',
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toAdminDTO(): AdminSearchCategoriesDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new AdminSearchCategoriesDTO(
            query: get_string($v, 'query'),
            level: get_int($v, 'level'),
            page: get_int($v, 'page') ?? 1,
            size: get_int($v, 'size') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }

    public function toPublicDTO(): PublicSearchCategoriesDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new PublicSearchCategoriesDTO(
            query: get_string($v, 'query'),
            level: get_int($v, 'level'),
            offset: get_int($v, 'offset') ?? 1,
            limit: get_int($v, 'limit') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }
}
