<?php

namespace App\Domains\Catalog\Repositories;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function findById(int $productId): ?Product
    {
        return Product::with(['categories', 'tags', 'metas'])->find($productId);
    }

    public function findActiveById(int $productId): ?Product
    {
        return Product::with(['categories', 'tags', 'metas'])
            ->where('product_id', $productId)
            ->where('status', ProductStatus::ACTIVE)
            ->first();
    }

    public function findActiveBySlug(string $slug): ?Product
    {
        return Product::with(['categories', 'tags', 'metas'])
            ->where('slug', $slug)
            ->where('status', ProductStatus::ACTIVE)
            ->first();
    }

    public function findActiveWithRelations(int $productId, array $relations = ['categories', 'tags']): ?Product
    {
        return Product::with($relations)
            ->where('product_id', $productId)
            ->where('status', ProductStatus::ACTIVE)
            ->first();
    }

    public function searchWithFilters(
        array  $filters,
        string $sortField,
        string $sortOrder,
        int    $offset,
        int    $limit
    ): Collection
    {
        $query = Product::query()->with(['categories', 'tags', 'metas']);
        $this->applyFilters($query, $filters);

        return $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countWithFilters(array $filters): int
    {
        $query = Product::query();
        $this->applyFilters($query, $filters);
        return $query->count();
    }

    public function searchActiveWithFilters(
        array  $filters,
        string $sortField,
        string $sortOrder,
        int    $offset,
        int    $limit
    ): Collection
    {
        $query = Product::query()
            ->with(['categories', 'tags', 'metas'])
            ->where('status', ProductStatus::ACTIVE);

        $this->applyFilters($query, $filters);

        return $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countActiveWithFilters(array $filters): int
    {
        $query = Product::query()->where('status', ProductStatus::ACTIVE);
        $this->applyFilters($query, $filters);
        return $query->count();
    }

    public function searchRelatedProducts(
        int    $excludeProductId,
        array  $categoryIds,
        array  $tagIds,
        string $sortField,
        string $sortOrder,
        int    $offset,
        int    $limit
    ): Collection
    {
        $query = $this->buildRelatedProductsQuery($excludeProductId, $categoryIds, $tagIds);

        return $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countRelatedProducts(int $excludeProductId, array $categoryIds, array $tagIds): int
    {
        return $this->buildRelatedProductsQuery($excludeProductId, $categoryIds, $tagIds)->count();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
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

    private function buildRelatedProductsQuery(
        int   $excludeProductId,
        array $categoryIds,
        array $tagIds
    ): Builder
    {
        $query = Product::query()
            ->with(['categories', 'tags', 'metas'])
            ->where('product_id', '!=', $excludeProductId)
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

        return $query;
    }

    // cart
    public function findActiveOrFail(int $productId): Product
    {
        return Product::where('product_id', $productId)
            ->where('status', ProductStatus::ACTIVE)
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    // checkout
    public function findLockedByIds(mixed $productIds)
    {
        return Product::whereIn('product_id', $productIds)
            ->orderBy('product_id')
            ->lockForUpdate()
            ->get()
            ->keyBy('product_id');
    }

    // order
    public function decrementStock(int $productId, int $requestedQuantity)
    {
        return Product::where('product_id', $productId)
            ->where('quantity', '>=', $requestedQuantity)
            ->decrement('quantity', $requestedQuantity);
    }
}
