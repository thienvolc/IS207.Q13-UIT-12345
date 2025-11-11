<?php

namespace App\Services;

use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Dtos\Category\CreateCategoryDto;
use App\Dtos\Category\GetProductsByCategoryDto;
use App\Dtos\Category\SearchCategoriesAdminDto;
use App\Dtos\Category\SearchCategoriesPublicDto;
use App\Dtos\Category\UpdateCategoryDto;
use App\Exceptions\BusinessException;
use App\Helpers\StringHelper;
use App\Http\Resources\CategoryPublicResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductPublicResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Utils\PaginationUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function searchCategoriesPublic(SearchCategoriesPublicDto $dto): array
    {
        $totalCount = $this->categoryRepository->countPublic($dto->level, $dto->query);

        $categories = $this->categoryRepository->searchPublic(
            $dto->level,
            $dto->query,
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );

        return PaginationUtil::fromOffsetLimit(
            CategoryPublicResource::collectionWithChildren($categories),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function getCategoryBySlug(string $slug): array
    {
        $category = $this->findCategoryBySlug($slug);
        return CategoryPublicResource::transformWithChildren($category);
    }

    public function getProductsByCategorySlug(GetProductsByCategoryDto $dto): array
    {
        $category = $this->findCategoryBySlug($dto->slug);
        $query = $this->buildActiveProductsQuery($category);
        $totalCount = $query->count();

        $products = $query->orderBy($dto->sortField, $dto->sortOrder)
            ->offset($dto->offset)
            ->limit($dto->limit)
            ->get();

        return PaginationUtil::fromOffsetLimit(
            ProductPublicResource::collection($products),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function searchCategoriesAdmin(SearchCategoriesAdminDto $dto): array
    {
        $totalCount = $this->categoryRepository->countAdmin($dto->level);

        $categories = $this->categoryRepository->searchAdmin(
            $dto->level,
            $dto->sortField,
            $dto->sortOrder,
            $dto->page,
            $dto->size
        );

        return PaginationUtil::fromPageSize(
            CategoryResource::collectionWithChildren($categories),
            $dto->page,
            $dto->size,
            $totalCount
        );
    }

    public function getCategoryById(int $categoryId): array
    {
        $category = $this->findCategoryById($categoryId);
        return CategoryResource::transformWithChildren($category);
    }

    public function createCategory(CreateCategoryDto $dto): array
    {
        $category = DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $category = $this->categoryRepository->create($data);

            if ($dto->children) {
                $this->validateAndSetChildren($category, $dto->children);
            }

            $category->load('children');
            return $category;
        });

        return CategoryResource::transformWithChildren($category);
    }

    public function updateCategory(UpdateCategoryDto $dto): array
    {
        $category = $this->findCategoryById($dto->categoryId);

        $category = DB::transaction(function () use ($category, $dto) {
            $data = $this->prepareUpdateData($dto);
            $this->categoryRepository->update($category, $data);

            if ($dto->children !== null) {
                $this->updateCategoryChildren($category, $dto->children);
            }

            $category->refresh();
            $category->load('children');
            return $category;
        });

        return CategoryResource::transformWithChildren($category);
    }

    public function deleteCategory(int $categoryId): array
    {
        $category = $this->findCategoryById($categoryId);

        $deletedCategory = DB::transaction(function () use ($category) {
            $this->removeChildrenParent($category->category_id);
            $this->categoryRepository->detachAllProducts($category);

            $deletedCategory = $category->replicate();
            $deletedCategory->load('children');
            $this->categoryRepository->delete($category);

            return $deletedCategory;
        });

        return CategoryResource::transformWithChildren($deletedCategory);
    }

    private function findCategoryBySlug(string $slug): Category
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (!$category) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $category;
    }

    private function buildActiveProductsQuery(Category $category)
    {
        return $category->products()
            ->where('status', ProductStatus::ACTIVE)
            ->with(['categories', 'tags', 'metas']);
    }

    private function findCategoryById(int $categoryId): Category
    {
        $category = $this->categoryRepository->findById($categoryId);

        if (!$category) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $category;
    }

    private function prepareCreateData(CreateCategoryDto $dto): array
    {
        $userId = $this->getAuthUserId();
        $data = $dto->toArray();

        if (empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        unset($data['children']);

        return $data;
    }

    private function validateAndSetChildren(Category $category, array $childrenIds): void
    {
        $children = $this->categoryRepository->findByIds($childrenIds);

        foreach ($children as $child) {
            if ($child->level !== ($category->level + 1)) {
                throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                    'message' => 'Children must have level = parent level + 1'
                ]);
            }
        }

        $this->setChildrenParent($childrenIds, $category->category_id);
    }

    private function setChildrenParent(array $childrenIds, int $parentId): void
    {
        $userId = $this->getAuthUserId();

        $this->categoryRepository->updateParentId($childrenIds, $parentId, $userId);
    }

    private function prepareUpdateData(UpdateCategoryDto $dto): array
    {
        $userId = $this->getAuthUserId();
        $data = $dto->toArray();

        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['updated_by'] = $userId;

        unset($data['children']);

        return $data;
    }

    private function updateCategoryChildren(Category $category, ?array $childrenIds): void
    {
        $this->removeChildrenParent($category->category_id);

        if (!empty($childrenIds)) {
            $this->setChildrenParent($childrenIds, $category->category_id);
        }
    }

    private function removeChildrenParent(int $parentId): void
    {
        $userId = $this->getAuthUserId();

        $this->categoryRepository->removeParent($parentId, $userId);
    }

    private function getAuthUserId(): int
    {
        return Auth::id();
    }
}
