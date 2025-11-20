<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Category\FormRequests\CreateCategoryRequest;
use App\Domains\Catalog\DTOs\Category\FormRequests\SearchCategoriesRequest;
use App\Domains\Catalog\DTOs\Category\FormRequests\UpdateCategoryRequest;
use App\Domains\Catalog\Services\CategoryService;
use App\Http\Controllers\AppController;

class CategoryAdminController extends AppController
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    /**
     * [GET] /api/admin/categories
     */
    public function index(SearchCategoriesRequest $req): ResponseDTO
    {
        $result = $this->categoryService->search($req->toAdminDTO());
        return $this->success($result);
    }

    /**
     * [GET] /api/admin/categories/{category_id}
     */
    public function show(int $category_id): ResponseDTO
    {
        $category = $this->categoryService->getById($category_id);
        return $this->success($category);
    }

    /**
     * [POST] /api/admin/categories
     */
    public function store(CreateCategoryRequest $req): ResponseDTO
    {
        $category = $this->categoryService->create($req->toDTO());
        return $this->created($category);
    }

    /**
     * [PUT] /api/admin/categories/{category_id}
     */
    public function update(UpdateCategoryRequest $req, int $category_id): ResponseDTO
    {
        $category = $this->categoryService->update($req->toDTO($category_id));
        return $this->success($category);
    }

    /**
     * [DELETE] /api/admin/categories/{category_id}
     */
    public function destroy(int $category_id): ResponseDTO
    {
        $category = $this->categoryService->delete($category_id);
        return $this->success($category);
    }
}
