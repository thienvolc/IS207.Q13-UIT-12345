<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Queries\AdminSearchProductsDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Foundation\Http\FormRequest;

class AdminSearchProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'tag_id' => 'nullable|integer|min:1',
            'price_min' => 'nullable|integer|min:0',
            'price_max' => 'nullable|integer|min:0|gte:price_min',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): AdminSearchProductsDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new AdminSearchProductsDTO(
            query: get_string($v, 'query'),
            categoryIdOrSlug: get_string($v, 'category'),
            tagId: get_int($v, 'tag_id'),
            priceMin: get_float($v, 'price_min'),
            priceMax: get_float($v, 'price_max'),
            page: get_int($v, 'page') ?? 1,
            size: get_int($v, 'size') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder
        );
    }
}
