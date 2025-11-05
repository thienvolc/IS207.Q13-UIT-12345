<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\AppController;
use App\Http\Requests\Category\SearchCategoryRequest;
use App\Http\Requests\Product\SearchProductRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends AppController
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    /**
     * GET /categories
     */
    public function index(SearchCategoryRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $result = $this->categoryService->searchCategoriesPublic(
            $request->input('query'),
            $request->input('level'),
            $request->getOffset(),
            $request->getLimit(),
            $sortField,
            $sortOrder
        );

        return $this->success($result);
    }

    /**
     * GET /categories/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        $category = $this->categoryService->getCategoryBySlug($slug);

        return $this->success($category);
    }

    /**
     * GET /categories/{slug}/products
     */
    public function products(SearchProductRequest $request, string $slug): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $result = $this->categoryService->getProductsByCategorySlug(
            $slug,
            $request->getOffset(),
            $request->getLimit(),
            $sortField,
            $sortOrder
        );

        return $this->success($result);
    }
}
