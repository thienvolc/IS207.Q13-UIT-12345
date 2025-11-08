<?php

namespace App\Http\Controllers\Public;

use App\Dtos\Category\GetProductsByCategoryDto;
use App\Dtos\Category\SearchCategoriesPublicDto;
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

    public function index(SearchCategoryRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchCategoriesPublicDto::fromArray([
            'query' => $request->input('query'),
            'level' => $request->input('level'),
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->categoryService->searchCategoriesPublic($dto);

        return $this->success($result);
    }

    public function show(string $slug): JsonResponse
    {
        $category = $this->categoryService->getCategoryBySlug($slug);

        return $this->success($category);
    }

    public function products(SearchProductRequest $request, string $slug): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetProductsByCategoryDto::fromArray([
            'slug' => $slug,
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->categoryService->getProductsByCategorySlug($dto);

        return $this->success($result);
    }
}
