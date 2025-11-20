<?php

namespace App\Domains\Catalog\DTOs\Tag\FormRequests;

use App\Domains\Catalog\DTOs\Tag\Queries\SearchTagsDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Foundation\Http\FormRequest;

class SearchTagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): SearchTagsDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new SearchTagsDTO(
            offset: get_int($v, 'offset') ?? 1,
            limit: get_int($v, 'limit') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }
}

