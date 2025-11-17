<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Category\FormRequests\SearchCategoriesRequest;
use App\Domains\Catalog\Services\CategoryService;
use App\Http\Controllers\AppController;
use Cache;

class PublicCategoryController extends AppController
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    /**
     * [GET] /api/categories/all
     */
    public function all(): ResponseDTO
    {
        $categories = Cache::remember('public_categories_all', 3600, function () {
            return $this->categoryService->getAllPublic();
        });
        return $this->success($categories);
    }

    /**
     * [GET] /api/categories
     */
    public function index(SearchCategoriesRequest $req): ResponseDTO
    {
        $result = $this->categoryService->searchPublic($req->toPublicDTO());
        return $this->success($result);
    }

    /**
     * [GET] /api/categories/{slug}
     */
    public function show(string $slug): ResponseDTO
    {
        $category = $this->categoryService->getBySlug($slug);
        return $this->success($category);
    }
}
