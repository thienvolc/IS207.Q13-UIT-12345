<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\DTOs\Category\Requests\CreateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Requests\GetProductsByCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Requests\SearchCategoriesAdminDTO;
use App\Domains\Catalog\DTOs\Category\Requests\SearchCategoriesPublicDTO;
use App\Domains\Catalog\DTOs\Category\Requests\UpdateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Responses\CategoryDTO;
use App\Domains\Catalog\DTOs\Category\Responses\CategoryPublicDTO;
use App\Domains\Catalog\DTOs\Category\Responses\ProductPublicDTO;
use App\Domains\Catalog\Entities\Category;
use App\Domains\Catalog\Repositories\CategoryRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Helpers\StringHelper;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function searchCategoriesPublic(SearchCategoriesPublicDTO $dto): array
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

        return PaginationUtil::offsetLimit(
            CategoryPublicDTO::collectionWithChildren($categories),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function getCategoryBySlug(string $slug): array
    {
        $category = $this->findCategoryBySlug($slug);
        return CategoryPublicDTO::transformWithChildren($category);
    }

    public function getProductsByCategorySlug(GetProductsByCategoryDTO $dto): array
    {
        $category = $this->findCategoryBySlug($dto->slug);
        $query = $this->buildActiveProductsQuery($category);
        $totalCount = $query->count();

        $products = $query->orderBy($dto->sortField, $dto->sortOrder)
            ->offset($dto->offset)
            ->limit($dto->limit)
            ->get();

        return PaginationUtil::offsetLimit(
            ProductPublicDTO::collection($products),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function searchCategoriesAdmin(SearchCategoriesAdminDTO $dto): array
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
            CategoryDTO::collectionWithChildren($categories),
            $dto->page,
            $dto->size,
            $totalCount
        );
    }

    public function getCategoryById(int $categoryId): array
    {
        $category = $this->findCategoryById($categoryId);
        return CategoryDTO::transformWithChildren($category);
    }

    public function createCategory(CreateCategoryDTO $dto): array
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

        return CategoryDTO::transformWithChildren($category);
    }

    public function updateCategory(UpdateCategoryDTO $dto): array
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

        return CategoryDTO::transformWithChildren($category);
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

        return CategoryDTO::transformWithChildren($deletedCategory);
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

    private function prepareCreateData(CreateCategoryDTO $dto): array
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

    private function prepareUpdateData(UpdateCategoryDTO $dto): array
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
