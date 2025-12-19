<?php

namespace App\Http\Controllers\Api\Public\Content;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Blog\DTOs\Commands\CreateBlogPostDTO;
use App\Domains\Blog\DTOs\FormRequests\PublicSearchBlogPostsRequest;
use App\Domains\Blog\Services\BlogPostService;
use App\Http\Controllers\AppController;
use App\Infra\Helpers\StringHelper;
use Illuminate\Http\Request;

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

    // [POST] /me/blogs - User tạo bài viết mới (trạng thái draft)
    public function store(Request $request): ResponseDTO
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'thumb' => 'nullable|image|max:2048', // 2MB max
        ]);

        $thumbUrl = null;
        if ($request->hasFile('thumb')) {
            // Upload to storage/cloudinary
            $path = $request->file('thumb')->store('blog-thumbs', 'public');
            $thumbUrl = asset('storage/' . $path);
        }

        $dto = new CreateBlogPostDTO(
            title: $validated['title'],
            metaTitle: $validated['title'],
            slug: StringHelper::slugify($validated['title']) . '-' . time(),
            thumb: $thumbUrl,
            summary: $validated['summary'] ?? null,
            content: $validated['content'],
            conclusion: null,
            status: 1, // Draft status - chờ admin duyệt
            publishedAt: null,
        );

        $post = $this->blogPostService->create($dto);

        return $this->success([
            'message' => 'Bài viết đã được tạo và đang chờ phê duyệt!',
            'data' => $post->toArray()
        ]);
    }

    // [GET] /me/blogs - Lấy danh sách bài viết của user hiện tại
    public function myPosts(): ResponseDTO
    {
        $posts = $this->blogPostService->getPostsByCurrentUser();
        
        return $this->success($posts);
    }
}
