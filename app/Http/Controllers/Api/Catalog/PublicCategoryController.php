<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Category\FormRequests\SearchCategoryRequest;
use App\Domains\Catalog\DTOs\Category\Requests\GetProductsByCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Requests\SearchCategoriesPublicDTO;
use App\Domains\Catalog\DTOs\Product\FormRequests\SearchProductRequest;
use App\Domains\Catalog\Services\CategoryService;
use App\Http\Controllers\AppController;
use Cache;

class PublicCategoryController extends AppController
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function all(): ResponseDTO
    {
        $categories = Cache::remember('public_categories_all', 3600, function () {
            return $this->categoryService->getAllPublic();
        });

        return $this->success($categories);
    }

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

        $result = $this->categoryService->searchPublic($dto);

        return $this->success($result);
    }

    public function show(string $slug): ResponseDTO
    {
        $category = $this->categoryService->getBySlug($slug);

        return $this->success($category);
    }
}
