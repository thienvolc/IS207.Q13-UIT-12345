<?php

namespace App\Domains\Catalog\Repositories;

use App\Domains\Catalog\Entities\Category;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository
{
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function getByIdWithChildrenOrFail(int $categoryId): Category
    {
        return Category::with('children')->find($categoryId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    public function getBySlugWithChildrenOrFail(string $slug): Category
    {
        return Category::where('slug', $slug)->with('children')
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function getAllWithChildren(): Collection
    {
        // TODO: Add position for stable ordering

        return Category::whereNull('parent_id')->with('children')->get();
    }

    public function search(Pageable $pageable, int $level, ?string $query): LengthAwarePaginator
    {
        return Category::query()
            ->where('level', $level)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('desc', 'like', '%' . $query . '%');
            })
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    public function searchPublic(Pageable $pageable, int $level, ?string $query): LengthAwarePaginator
    {
        return Category::query()
            ->where('level', $level)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('desc', 'like', '%' . $query . '%');
            })
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    public function removeParent(int $parentId, int $userId): int
    {
        return Category::where('parent_id', $parentId)
            ->update([
                'parent_id' => null,
                'updated_by' => $userId,
            ]);
    }
}
