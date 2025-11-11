<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function findById(int $categoryId): ?Category
    {
        return Category::with('children')->find($categoryId);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->with('children')->first();
    }

    public function findByIds(array $categoryIds): Collection
    {
        return Category::whereIn('category_id', $categoryIds)->get();
    }

    public function searchPublic(int $level, ?string $query, string $sortField, string $sortOrder, int $offset, int $limit): Collection
    {
        $queryBuilder = Category::query()->where('level', $level);

        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('desc', 'like', '%' . $query . '%');
            });
        }

        return $queryBuilder->with('children')
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countPublic(int $level, ?string $query): int
    {
        $queryBuilder = Category::query()->where('level', $level);

        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('desc', 'like', '%' . $query . '%');
            });
        }

        return $queryBuilder->count();
    }

    public function searchAdmin(
        int $level,
        string $sortField,
        string $sortOrder,
        int $offset,
        int $limit
    ): Collection
    {
        return Category::query()
            ->where('level', $level)
            ->with('children')
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countAdmin(int $level): int
    {
        return Category::where('level', $level)->count();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    public function updateParentId(array $categoryIds, ?int $parentId, int $userId): int
    {
        return Category::whereIn('category_id', $categoryIds)
            ->update([
                'parent_id' => $parentId,
                'updated_by' => $userId,
            ]);
    }

    public function removeParent(int $parentId, int $userId): int
    {
        return Category::where('parent_id', $parentId)
            ->update([
                'parent_id' => null,
                'updated_by' => $userId,
            ]);
    }

    public function detachAllProducts(Category $category): void
    {
        $category->products()->detach();
    }
}
