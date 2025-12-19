<?php

namespace App\Http\Controllers\Api\Public\Content;

use App\Domains\Blog\DTOs\FormRequests\PublicSearchBlogPostsRequest;
use App\Domains\Blog\Services\BlogPostService;
use App\Http\Controllers\AppController;

class BlogController extends AppController
{
    public function __construct(
        private readonly BlogPostService $blogPostService
    ) {
    }

    // [GET] /blogs
    public function index(PublicSearchBlogPostsRequest $request)
    {
        $result = $this->blogPostService->searchPublic($request->toDTO());

        return $this->success($result->toArray());
    }

    // [GET] /blogs/{slug}
    public function show(string $slug)
    {
        $result = $this->blogPostService->getBySlug($slug);

        return $this->success($result->toArray());
    }
}
