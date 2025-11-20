<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\DTOs\Category\Commands\CreateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Commands\UpdateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Queries\AdminSearchCategoriesDTO;
use App\Domains\Catalog\DTOs\Category\Queries\PublicSearchCategoriesDTO;
use App\Domains\Catalog\DTOs\Category\Responses\PublicCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Responses\CategoryDTO;
use App\Domains\Catalog\Mappers\CategoryMapper;
use App\Domains\Catalog\Repositories\CategoryRepository;
use App\Domains\Common\DTOs\OffsetPageResponseDTO;
use App\Domains\Common\DTOs\PageResponseDTO;
use App\Infra\Helpers\StringHelper;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\PaginationUtil;
use App\Infra\Utils\Pagination\Sort;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CategoryMapper     $categoryMapper,
    ) {}

    /**
     * @return PublicCategoryDTO[]
     */
    public function getAllPublic(): array
    {
        $categories = $this->categoryRepository->getAllWithChildren();
        return $this->categoryMapper->toPublicDTOs($categories);
    }

    /**
     * @return OffsetPageResponseDTO<PublicCategoryDTO>
     */
    public function searchPublic(PublicSearchCategoriesDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);

        $categories = $this->categoryRepository->searchPublic($pageable, $dto->level, $dto->query);

        return OffsetPageResponseDTO::fromPaginator($categories,
            fn($c) => $this->categoryMapper->toPublicDTO($c));
    }

    public function getBySlug(string $slug): PublicCategoryDTO
    {
        $category = $this->categoryRepository->getBySlugWithChildrenOrFail($slug);
        return $this->categoryMapper->toPublicDTO($category);
    }

    /**
     * @return PageResponseDTO<CategoryDTO>
     */
    public function search(AdminSearchCategoriesDTO $dto): PageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $categories = $this->categoryRepository->search($pageable, $dto->level, $dto->query);

        return PageResponseDTO::fromPaginator($categories,
            fn($c) => $this->categoryMapper->toDTO($c));
    }

    public function getById(int $categoryId): CategoryDTO
    {
        $category = $this->categoryRepository->getByIdWithChildrenOrFail($categoryId);
        return $this->categoryMapper->toDTO($category);
    }

    public function create(CreateCategoryDTO $dto): CategoryDTO
    {
        return DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $category = $this->categoryRepository->create($data);

            return $this->categoryMapper->toDTO($category);
        });
    }

    public function update(UpdateCategoryDTO $dto): CategoryDTO
    {
        $category = $this->categoryRepository->getByIdWithChildrenOrFail($dto->categoryId);

        return DB::transaction(function () use ($category, $dto) {
            $data = $this->prepareUpdateData($dto);

            $category->update($data);
            $category->refresh();
            $category->load('children');

            return $this->categoryMapper->toDTO($category);
        });
    }

    public function delete(int $categoryId): CategoryDTO
    {
        $category = $this->categoryRepository->getByIdWithChildrenOrFail($categoryId);

        return DB::transaction(function () use ($category) {
            $this->removeChildrenParent($category->category_id);
            $category->products()->detach();

            $replica = $category->replicate();
            $category->delete();

            return $this->categoryMapper->toDTO($replica);
        });
    }

    private function prepareCreateData(CreateCategoryDTO $dto): array
    {
        $userId = $this->userId();
        $data = $dto->toArray();

        if (empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        unset($data['children']);

        return $data;
    }

    private function prepareUpdateData(UpdateCategoryDTO $dto): array
    {
        $userId = $this->userId();
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['updated_by'] = $userId;

        unset($data['children']);

        return $data;
    }

    private function removeChildrenParent(int $parentId): void
    {
        $userId = $this->userId();
        $this->categoryRepository->removeParent($parentId, $userId);
    }

    private function userId(): int
    {
        return Auth::id();
    }
}
