<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Category\Queries\SearchProductsByCategorySlugDTO;
use App\Domains\Catalog\DTOs\Product\Queries\PublicSearchProductsDTO;
use App\Domains\Catalog\DTOs\Product\Queries\SearchRelatedProductsDTO;
use App\Domains\Catalog\DTOs\Tag\Queries\SearchProductsByTagDTO;
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
            'category' => 'nullable|integer|exists:categories,category_id',
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
            query: $v['query'],
            category: $this->input('category'),
            priceMin: float_or_null($v['price_min'] ?? null),
            priceMax: float_or_null($v['price_max'] ?? null),
            offset: int_or_null($v['offset']) ?? 1,
            limit: int_or_null($v['limit']) ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }

    public function toProductsByCategoryDTO(string $slug): SearchProductsByCategorySlugDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new SearchProductsByCategorySlugDTO(
            slug: $slug,
            offset: int_or_null($v['offset']) ?? 1,
            limit: int_or_null($v['limit']) ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }

    public function toProductsByTagDTO(int $tagId): SearchProductsByTagDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new SearchProductsByTagDTO(
            tagId: $tagId,
            offset: int_or_null($v['offset']) ?? 1,
            limit: int_or_null($v['limit']) ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }

    public function toRelatedDTO(int $productId): SearchRelatedProductsDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new SearchRelatedProductsDTO(
            productId: $productId,
            offset: int_or_null($v['offset']) ?? 1,
            limit: int_or_null($v['limit']) ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }
}
