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
            'level' => 'required|integer|min:1',
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
            query: string_or_null($v['query'] ?? null),
            level: int_or_null($v['level']) ?? 0,
            page: int_or_null($v['page']) ?? 1,
            size: int_or_null($v['size']) ?? 10,
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
            query: string_or_null($v['query']),
            level: int_or_null($v['level']) ?? 0,
            offset: int_or_null($v['offset']) ?? 1,
            limit: int_or_null($v['limit']) ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }
}
