<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Category\FormRequests\SearchCategoryRequest;
use App\Domains\Catalog\DTOs\Category\Requests\GetProductsByCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Requests\SearchCategoriesPublicDTO;
use App\Domains\Catalog\DTOs\Product\FormRequests\SearchProductRequest;
use App\Domains\Catalog\Services\CategoryService;
use App\Http\Controllers\AppController;

class PublicCategoryController extends AppController
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function index(SearchCategoryRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchCategoriesPublicDTO::fromArray([
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

    public function show(string $slug): ResponseDTO
    {
        $category = $this->categoryService->getCategoryBySlug($slug);

        return $this->success($category);
    }

    public function products(SearchProductRequest $request, string $slug): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetProductsByCategoryDTO::fromArray([
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
