<?php

namespace App\Http\Controllers\Api\Admin\Content;

use App\Domains\Blog\DTOs\FormRequests\AdminSearchBlogPostsRequest;
use App\Domains\Blog\DTOs\FormRequests\CreateBlogPostRequest;
use App\Domains\Blog\DTOs\FormRequests\UpdateBlogPostRequest;
use App\Domains\Blog\Services\BlogPostService;
use App\Http\Controllers\AppController;

class BlogAdminController extends AppController
{
    public function __construct(
        private readonly BlogPostService $blogPostService
    ) {
    }

    // [GET] /admin/blogs
    public function index(AdminSearchBlogPostsRequest $request)
    {
        $result = $this->blogPostService->search($request->toDTO());

        return $this->success($result->toArray());
    }

    // [GET] /admin/blogs/{id}
    public function show(int $id)
    {
        $result = $this->blogPostService->getById($id);

        return $this->success($result->toArray());
    }

    // [POST] /admin/blogs
    public function store(CreateBlogPostRequest $request)
    {
        $result = $this->blogPostService->create($request->toDTO());

        return $this->created($result->toArray());
    }

    // [PUT] /admin/blogs/{id}
    public function update(int $id, UpdateBlogPostRequest $request)
    {
        $result = $this->blogPostService->update($request->toDTO());

        return $this->success($result->toArray());
    }

    // [DELETE] /admin/blogs/{id}
    public function destroy(int $id)
    {
        $this->blogPostService->delete($id);

        return $this->noContent();
    }

    // [POST] /admin/blogs/{id}/publish
    public function publish(int $id)
    {
        $result = $this->blogPostService->publish($id);

        return $this->success($result->toArray());
    }
}
