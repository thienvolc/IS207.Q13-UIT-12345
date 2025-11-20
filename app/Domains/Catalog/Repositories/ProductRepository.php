<?php

namespace App\Domains\Catalog\Repositories;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\DTOs\Product\Queries\ProductFilter;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function existsById($productId): bool
    {
        return Product::where('product_id', $productId)->exists();
    }

    public function getByIdOrFail(int $productId): Product
    {
        return Product::with(['categories', 'tags', 'metas'])->find($productId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    public function getActiveByIdWithRelationsOrFail(int $productId): Product
    {
        return Product::with(['categories', 'tags', 'metas'])
            ->where('product_id', $productId)
            ->where('status', ProductStatus::ACTIVE)
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function getActiveBySlugWithRelationsOrFail(string $slug): ?Product
    {
        return Product::with(['categories', 'tags', 'metas'])
            ->where('slug', $slug)
            ->where('status', ProductStatus::ACTIVE)
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function search(Pageable $pageable, ProductFilter $filters): LengthAwarePaginator
    {
        $query = Product::query()->with(['categories', 'tags', 'metas']);
        $this->applyFilters($query, $filters);
        return $this->queryWithPagination($query, $pageable);
    }

    public function searchPublic(Pageable $pageable, ProductFilter $filters): LengthAwarePaginator
    {
        $query = Product::query()->with(['categories', 'tags', 'metas']);
        $this->applyFilters($query, $filters);
        $query->where('status', ProductStatus::ACTIVE);
        return $this->queryWithPagination($query, $pageable);
    }

    public function searchRelated(
        Pageable $pageable,
        int      $excludeProductId,
        array    $categoryIds,
        array    $tagIds
    ): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['categories', 'tags', 'metas'])
            ->where('product_id', '!=', $excludeProductId)
            ->where('status', ProductStatus::ACTIVE);

        $this->applyRelated($query, $categoryIds, $tagIds);

        return $this->queryWithPagination($query, $pageable);
    }

    // cart
    public function getActiveByIdOrFail(int $productId): Product
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

    // order, stock
    public function decrementStock(int $productId, int $requestedQuantity)
    {
        return Product::where('product_id', $productId)
            ->where('quantity', '>=', $requestedQuantity)
            ->decrement('quantity', $requestedQuantity);
    }

    // helpers
    private function applyFilters(Builder $query, ProductFilter $f): void
    {
        $query->when($f->query, function (Builder $q, $v) {
            $searchTerm = '%' . $v . '%';

            $q->where(function (Builder $sq) use ($searchTerm) {
                $sq->where('title', 'like', $searchTerm)
                    ->orWhere('desc', 'like', $searchTerm)
                    ->orWhere('summary', 'like', $searchTerm);
            });
        });

        $isCategoryId = ctype_digit($f->categoryIdOrSlug);

        $query->when($f->categoryIdOrSlug,
            fn($q, $v) => $isCategoryId
                ? $query->whereHas('categories', fn($q) => $q->where('categories.category_id', $f->categoryIdOrSlug))
                : $query->whereHas('categories', fn($q) => $q->where('categories.slug', $f->categoryIdOrSlug))
        );
        $query->when($f->tagId,
            fn($q, $v) => $query->whereHas('tags',
                fn($q) => $q->where('tags.tag_id', $f->tagId)));
        $query->when($f->priceMin, fn($q, $v) => $q->where('price', '>=', $v));
        $query->when($f->priceMax, fn($q, $v) => $q->where('price', '<=', $v));
    }

    private function applyRelated(Builder $query, array $categoryIds, array $tagIds): void
    {
        $hasCategory = !empty($categoryIds);
        $hasTag = !empty($tagIds);

        if (!$hasCategory && !$hasTag) {
            return;
        }

        $query->where(function (Builder $q) use ($hasCategory, $hasTag, $categoryIds, $tagIds) {
            if ($hasCategory) {
                $q->whereHas('categories', fn($c) => $c->whereIn('categories.category_id', $categoryIds));
            }

            if ($hasTag) {
                $method = $hasCategory ? 'orWhereHas' : 'whereHas';
                $q->{$method}('tags', fn($t) => $t->whereIn('tags.tag_id', $tagIds));
            }
        });
    }

    private function queryWithPagination($query, $pageable): LengthAwarePaginator
    {
        return $query
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }
}
