<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Category\FormRequests\CreateCategoryRequest;
use App\Domains\Catalog\DTOs\Category\FormRequests\SearchCategoryRequest;
use App\Domains\Catalog\DTOs\Category\FormRequests\UpdateCategoryRequest;
use App\Domains\Catalog\DTOs\Category\Requests\CreateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Requests\SearchCategoriesAdminDTO;
use App\Domains\Catalog\DTOs\Category\Requests\UpdateCategoryDTO;
use App\Domains\Catalog\Services\CategoryService;
use App\Http\Controllers\AppController;

class CategoryController extends AppController
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function index(SearchCategoryRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchCategoriesAdminDTO::fromArray([
            'level' => $request->input('level'),
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->categoryService->search($dto);

        return $this->success($result);
    }

    public function show(int $category_id): ResponseDTO
    {
        $category = $this->categoryService->getById($category_id);

        return $this->success($category);
    }

    public function store(CreateCategoryRequest $request): ResponseDTO
    {
        $dto = CreateCategoryDTO::fromArray($request->validated());
        $category = $this->categoryService->create($dto);

        return $this->created($category);
    }

    public function update(UpdateCategoryRequest $request, int $category_id): ResponseDTO
    {
        $dto = UpdateCategoryDTO::fromArray([
            'category_id' => $category_id,
            ...$request->validated()
        ]);

        $category = $this->categoryService->update($dto);

        return $this->success($category);
    }

    public function destroy(int $category_id): ResponseDTO
    {
        $category = $this->categoryService->delete($category_id);

        return $this->success($category);
    }
}
