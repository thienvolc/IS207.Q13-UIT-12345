<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Queries\PublicSearchProductsDTO;
use App\Domains\Catalog\DTOs\Product\Queries\SearchRelatedProductsDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Foundation\Http\FormRequest;

class PublicSearchProductsRequest extends FormRequest
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
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): PublicSearchProductsDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new PublicSearchProductsDTO(
            query: get_string($v, 'query'),
            categoryIdOrSlug: get_string($v, 'category'),
            tagId: get_int($v, 'tag_id'),
            priceMin: get_float($v, 'price_min'),
            priceMax: get_float($v, 'price_max'),
            offset: get_int($v, 'offset') ?? 1,
            limit: get_int($v, 'limit') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder
        );
    }

    public function toDTOByCategorySlug(string $slug): PublicSearchProductsDTO
    {
        $this->merge(['category' => $slug]);
        return $this->toDTO();
    }

    public function toDTOByTag(int $tagId): PublicSearchProductsDTO
    {
        $this->merge(['tag_id' => $tagId]);
        return $this->toDTO();
    }

    public function toRelatedDTO(int $productId): SearchRelatedProductsDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new SearchRelatedProductsDTO(
            productId: $productId,
            offset: get_int($v, 'offset') ?? 1,
            limit: get_int($v, 'limit') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }
}
