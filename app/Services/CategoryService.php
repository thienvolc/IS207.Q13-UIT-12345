<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Helpers\StringHelper;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryPublicResource;
use App\Http\Resources\ProductPublicResource;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    /**
     * Search categories for public (with hierarchy)
     */
    public function searchCategoriesPublic(?string $query, int $level, int $offset = 0, int $limit = 10, string $sortField = 'created_at', string $sortOrder = 'desc'): array
    {
        $queryBuilder = Category::query()->where('level', $level);

        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('desc', 'like', '%' . $query . '%');
            });
        }

        $totalCount = $queryBuilder->count();

        $categories = $queryBuilder->with('children')
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();

        return [
            'data' => CategoryPublicResource::collectionWithChildren($categories),
            'limit' => $limit,
            'offset' => $offset,
            'total_count' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
        ];
    }

    /**
     * Get category by slug for public
     */
    public function getCategoryBySlug(string $slug): array
    {
        $category = Category::where('slug', $slug)->with('children')->first();

        if (!$category) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return CategoryPublicResource::transformWithChildren($category);
    }

    /**
     * Get products by category slug
     */
    public function getProductsByCategorySlug(string $slug, int $offset = 0, int $limit = 10, string $sortField = 'created_at', string $sortOrder = 'desc'): array
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $query = $category->products()
            ->where('status', ProductStatus::ACTIVE)
            ->with(['categories', 'tags', 'metas']);

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

    /**
     * Search categories for admin (with pagination)
     */
    public function searchCategoriesAdmin(int $level, int $page = 1, int $size = 10, string $sortField = 'created_at', string $sortOrder = 'desc'): array
    {
        $query = Category::query()->where('level', $level);

        // Count before loading relationships for better performance
        $totalCount = $query->count();
        $totalPage = (int)ceil($totalCount / $size);

        $categories = $query->with('children')
            ->orderBy($sortField, $sortOrder)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get();

        return [
            'data' => CategoryResource::collectionWithChildren($categories),
            'current_page' => $page,
            'total_page' => $totalPage,
            'total_count' => $totalCount,
            'has_more' => $page < $totalPage,
        ];
    }

    /**
     * Get category by ID for admin
     */
    public function getCategoryById(int $categoryId): array
    {
        $category = Category::with('children')->find($categoryId);

        if (!$category) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return CategoryResource::transformWithChildren($category);
    }

    /**
     * Create a new category
     */
    public function createCategory(array $data): array
    {
        $category = DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = StringHelper::slugify($data['title']);
            }

            $userId = Auth::id();
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            $childrenIds = $data['children'] ?? [];
            unset($data['children']);

            $category = Category::create($data);

            // Validate and update children to set this category as parent
            if (!empty($childrenIds)) {
                // Validate children exist and have correct level
                $children = Category::whereIn('category_id', $childrenIds)->get();

                foreach ($children as $child) {
                    // Children must have level = parent level + 1
                    if ($child->level !== ($category->level + 1)) {
                        throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                            'message' => 'Children must have level = parent level + 1'
                        ]);
                    }
                }

                Category::whereIn('category_id', $childrenIds)
                    ->update([
                        'parent_id' => $category->category_id,
                        'updated_by' => $userId,
                    ]);
            }

            $category->load('children');

            return $category;
        });

        return CategoryResource::transformWithChildren($category);
    }

    /**
     * Update a category
     */
    public function updateCategory(int $categoryId, array $data): array
    {
        $category = $this->getCategoryModel($categoryId);

        $category = DB::transaction(function () use ($category, $data) {
            if (isset($data['title']) && empty($data['slug'])) {
                $data['slug'] = StringHelper::slugify($data['title']);
            }

            $userId = Auth::id();
            $data['updated_by'] = $userId;

            $childrenIds = $data['children'] ?? null;
            unset($data['children']);

            $category->update($data);

            // Update children if provided
            if ($childrenIds !== null) {
                // Remove old children
                Category::where('parent_id', $category->category_id)
                    ->update([
                        'parent_id' => null,
                        'updated_by' => $userId,
                    ]);

                // Set new children
                if (!empty($childrenIds)) {
                    Category::whereIn('category_id', $childrenIds)
                        ->update([
                            'parent_id' => $category->category_id,
                            'updated_by' => $userId,
                        ]);
                }
            }

            $category->refresh();
            $category->load('children');

            return $category;
        });

        return CategoryResource::transformWithChildren($category);
    }

    /**
     * Delete a category
     */
    public function deleteCategory(int $categoryId): array
    {
        $category = $this->getCategoryModel($categoryId);

        $deletedCategory = DB::transaction(function () use ($category) {
            $userId = Auth::id();

            // Update children to remove parent
            Category::where('parent_id', $category->category_id)
                ->update([
                    'parent_id' => null,
                    'updated_by' => $userId,
                ]);

            // Detach all products
            $category->products()->detach();

            $deletedCategory = $category->replicate();
            $deletedCategory->load('children');

            $category->delete();

            return $deletedCategory;
        });

        return CategoryResource::transformWithChildren($deletedCategory);
    }

    /**
     * Get category model by ID
     */
    private function getCategoryModel(int $categoryId): Category
    {
        $category = Category::find($categoryId);

        if (!$category) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $category;
    }
}
