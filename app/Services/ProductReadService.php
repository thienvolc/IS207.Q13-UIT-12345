<?php

namespace App\Services;

use App\Models\Product;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Http\Resources\ProductAdminResource;
use App\Http\Resources\ProductPublicResource;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Builder;

class ProductReadService
{
    public function getByIdForAdmin(int $productId): array
    {
        $product = Product::with(['categories', 'tags', 'metas'])->find($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductAdminResource::transform($product);
    }

    public function getByIdForPublic(int $productId): array
    {
        $product = Product::with(['categories', 'tags', 'metas'])
            ->where('product_id', $productId)
            ->where('status', ProductStatus::ACTIVE)
            ->first();

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductPublicResource::transform($product);
    }

    public function getBySlugForPublic(string $slug): array
    {
        $product = Product::with(['categories', 'tags', 'metas'])
            ->where('slug', $slug)
            ->where('status', ProductStatus::ACTIVE)
            ->first();

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductPublicResource::transform($product);
    }

    public function searchForAdmin(array  $filters,
                                   int    $page = 1,
                                   int    $size = 10,
                                   string $sortField = 'created_at',
                                   string $sortOrder = 'desc'): array
    {
        $query = Product::query()->with(['categories', 'tags', 'metas']);

        $this->applyFilters($query, $filters);

        $totalCount = $query->count();
        $totalPage = (int)ceil($totalCount / $size);

        $products = $query->orderBy($sortField, $sortOrder)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get();

        return [
            'data' => ProductAdminResource::collection($products),
            'current_page' => $page,
            'total_page' => $totalPage,
            'total_count' => $totalCount,
            'has_more' => $page < $totalPage,
        ];
    }

    public function searchForPublic(array  $filters,
                                    int    $offset = 0,
                                    int    $limit = 10,
                                    string $sortField = 'created_at',
                                    string $sortOrder = 'desc'): array
    {
        $query = Product::query()
            ->with(['categories', 'tags', 'metas'])
            ->where('status', ProductStatus::ACTIVE);

        $this->applyFilters($query, $filters);

        $totalCount = $query->count();

        $products = $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();

        return [
            'data' => ProductPublicResource::collection($products),
            'limit' => $limit,
            'offset' => $offset,
            'total_count' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
        ];
    }

    public function getRelatedProductsById(int    $productId,
                                          int    $offset = 0,
                                          int    $limit = 10,
                                          string $sortField = 'created_at',
                                          string $sortOrder = 'desc'): array
    {
        $product = Product::with(['categories', 'tags'])
            ->where('product_id', $productId)
            ->where('status', ProductStatus::ACTIVE)
            ->first();

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $categoryIds = $product->categories()->pluck('category_id')->toArray();
        $tagIds = $product->tags()->pluck('tag_id')->toArray();

        $query = Product::query()
            ->with(['categories', 'tags', 'metas'])
            ->where('product_id', '!=', $product->product_id)
            ->where('status', ProductStatus::ACTIVE);

        $hasCategoryFilter = !empty($categoryIds);
        $hasTagFilter = !empty($tagIds);

        if ($hasCategoryFilter || $hasTagFilter) {
            $query->where(function (Builder $q) use ($hasTagFilter, $hasCategoryFilter, $categoryIds, $tagIds) {
                if ($hasCategoryFilter) {
                    $q->whereHas('categories', function (Builder $subQ) use ($categoryIds) {
                        $subQ->whereIn('category_id', $categoryIds);
                    });
                }

                if ($hasTagFilter) {
                    if ($hasCategoryFilter) {
                        $q->orWhereHas('tags', function (Builder $subQ) use ($tagIds) {
                            $subQ->whereIn('tag_id', $tagIds);
                        });
                    } else {
                        $q->whereHas('tags', function (Builder $subQ) use ($tagIds) {
                            $subQ->whereIn('tag_id', $tagIds);
                        });
                    }
                }
            });
        }

        $totalCount = $query->count();

        $products = $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();

        return [
            'data' => ProductPublicResource::collection($products),
            'limit' => $limit,
            'offset' => $offset,
            'total_count' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
        ];
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['query'])) {
            $searchTerm = '%' . $filters['query'] . '%';
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('desc', 'like', $searchTerm)
                    ->orWhere('summary', 'like', $searchTerm);
            });
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', function (Builder $q) use ($filters) {
                $q->where('category_id', $filters['category']);
            });
        }

        if (isset($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (isset($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }
    }
}

