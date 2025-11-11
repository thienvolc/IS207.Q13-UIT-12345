<?php

namespace App\Http\Controllers\Admin;

use App\Dtos\Category\CreateCategoryDto;
use App\Dtos\Category\SearchCategoriesAdminDto;
use App\Dtos\Category\UpdateCategoryDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\SearchCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends AppController
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function index(SearchCategoryRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchCategoriesAdminDto::fromArray([
            'level' => $request->input('level'),
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->categoryService->searchCategoriesAdmin($dto);

        return $this->success($result);
    }

    public function show(int $category_id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($category_id);

        return $this->success($category);
    }

    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $dto = CreateCategoryDto::fromArray($request->validated());

        $category = $this->categoryService->createCategory($dto);

        return $this->created($category);
    }

    public function update(UpdateCategoryRequest $request, int $category_id): JsonResponse
    {
        $dto = UpdateCategoryDto::fromArray([
            'categoryId' => $category_id,
            ...$request->validated()
        ]);

        $category = $this->categoryService->updateCategory($dto);

        return $this->success($category);
    }

    public function destroy(int $category_id): JsonResponse
    {
        $category = $this->categoryService->deleteCategory($category_id);

        return $this->success($category);
    }
}
