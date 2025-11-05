<?php

namespace App\Http\Controllers\Admin;

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

    /**
     * GET /admin/categories
     */
    public function index(SearchCategoryRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $result = $this->categoryService->searchCategoriesAdmin(
            $request->input('level'),
            $request->getPage(),
            $request->getSize(),
            $sortField,
            $sortOrder
        );

        return $this->success($result);
    }

    /**
     * GET /admin/categories/{category_id}
     */
    public function show(int $category_id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($category_id);

        return $this->success($category);
    }

    /**
     * POST /admin/categories
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());

        return $this->createdResponse($category);
    }

    /**
     * PUT /admin/categories/{category_id}
     */
    public function update(UpdateCategoryRequest $request, int $category_id): JsonResponse
    {
        $category = $this->categoryService->updateCategory($category_id, $request->validated());

        return $this->success($category);
    }

    /**
     * DELETE /admin/categories/{category_id}
     */
    public function destroy(int $category_id): JsonResponse
    {
        $category = $this->categoryService->deleteCategory($category_id);

        return $this->success($category);
    }
}

