<?php

namespace App\Domains\Catalog\Services;

use App\Applications\DTOs\Responses\OffsetPageResponseDTO;
use App\Applications\DTOs\Responses\PageResponseDTO;
use App\Domains\Catalog\DTOs\Category\Requests\CreateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Requests\SearchCategoriesAdminDTO;
use App\Domains\Catalog\DTOs\Category\Requests\SearchCategoriesPublicDTO;
use App\Domains\Catalog\DTOs\Category\Requests\UpdateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Responses\CategoryPublicResponseDTO;
use App\Domains\Catalog\DTOs\Category\Responses\CategoryResponseDTO;
use App\Domains\Catalog\Repositories\CategoryRepository;
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
    ) {}

    /**
     * @return CategoryPublicResponseDTO[]
     */
    public function getAllPublic(): array
    {
        $categories = $this->categoryRepository->getAllWithChildren();
        return CategoryPublicResponseDTO::collection($categories);
    }

    /**
     * @return OffsetPageResponseDTO<CategoryPublicResponseDTO>
     */
    public function searchPublic(SearchCategoriesPublicDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);

        $categories = $this->categoryRepository->searchPublic($pageable, $dto->level, $dto->query);

        return OffsetPageResponseDTO::fromPaginator($categories);
    }

    public function getBySlug(string $slug): CategoryPublicResponseDTO
    {
        $category = $this->categoryRepository->getBySlugWithChildrenOrFail($slug);
        return CategoryPublicResponseDTO::fromModel($category);
    }

    /**
     * @return PageResponseDTO<CategoryResponseDTO>
     */
    public function search(SearchCategoriesAdminDTO $dto): PageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $categories = $this->categoryRepository->search($pageable, $dto->level, $dto->query);

        return PageResponseDTO::fromPaginator($categories);
    }

    public function getById(int $categoryId): CategoryResponseDTO
    {
        $category = $this->categoryRepository->getByIdWithChildrenOrFail($categoryId);
        return CategoryResponseDTO::fromModel($category);
    }

    public function create(CreateCategoryDTO $dto): CategoryResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $category = $this->categoryRepository->create($data);

            return CategoryResponseDTO::fromModel($category);
        });
    }

    public function update(UpdateCategoryDTO $dto): CategoryResponseDTO
    {
        $category = $this->categoryRepository->getByIdWithChildrenOrFail($dto->categoryId);

        return DB::transaction(function () use ($category, $dto) {
            $data = $this->prepareUpdateData($dto);

            $category->update($data);
            $category->refresh();
            $category->load('children');

            return CategoryResponseDTO::fromModel($category);
        });
    }

    public function delete(int $categoryId): array
    {
        $category = $this->categoryRepository->getByIdWithChildrenOrFail($categoryId);

        return DB::transaction(function () use ($category) {
            $this->removeChildrenParent($category->category_id);
            $category->products()->detach();

            $replica = $category->replicate();
            $category->delete();

            return CategoryResponseDTO::fromModel($replica);
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
