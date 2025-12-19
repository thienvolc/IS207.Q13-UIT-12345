<?php

namespace App\Http\Controllers\Admin\Content;

use App\Domains\Blog\DTOs\Commands\CreateBlogPostDTO;
use App\Domains\Blog\DTOs\Commands\UpdateBlogPostDTO;
use App\Domains\Blog\DTOs\Queries\AdminSearchBlogPostsDTO;
use App\Domains\Blog\Services\BlogPostService;
use App\Infra\Helpers\StringHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BlogController extends Controller
{
    public function __construct(
        private readonly BlogPostService $blogService
    ) {
    }

    public function index(Request $request)
    {
        $dto = new AdminSearchBlogPostsDTO(
            page: (int) $request->get('page', 1),
            size: (int) $request->get('size', 20),
            sortField: $request->get('sort', 'created_at'),
            sortOrder: $request->get('order', 'desc'),
            query: $request->get('query'),
            status: $request->get('status') ? (int) $request->get('status') : null,
        );

        $posts = $this->blogService->search($dto);

        return view('admin.blogs.index', [
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150',
            'thumb' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|integer|between:1,3',
        ]);

        $dto = new CreateBlogPostDTO(
            title: $validated['title'],
            metaTitle: $validated['title'],
            slug: $validated['slug'] ?? StringHelper::slugify($validated['title']),
            thumb: $validated['thumb'] ?? null,
            summary: $validated['summary'] ?? null,
            content: $validated['content'] ?? null,
            conclusion: null,
            status: (int) $validated['status'],
            publishedAt: $validated['status'] == 2 ? now()->toDateTimeString() : null,
        );

        $this->blogService->create($dto);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Bài viết đã được tạo thành công!');
    }

    public function edit($id)
    {
        $post = $this->blogService->getById((int) $id);
        return view('admin.blogs.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150',
            'thumb' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|integer|between:1,3',
        ]);

        $dto = new UpdateBlogPostDTO(
            blogpostId: (int) $id,
            title: $validated['title'],
            metaTitle: $validated['title'],
            slug: $validated['slug'] ?? null,
            thumb: $validated['thumb'] ?? null,
            summary: $validated['summary'] ?? null,
            content: $validated['content'] ?? null,
            conclusion: null,
            status: (int) $validated['status'],
            publishedAt: $validated['status'] == 2 ? now()->toDateTimeString() : null,
        );

        $this->blogService->update($dto);

        if ($request->action === 'save_and_continue') {
            return redirect()
                ->route('admin.blogs.edit', $id)
                ->with('success', 'Bài viết đã được cập nhật!');
        }

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $this->blogService->delete((int) $id);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Bài viết đã được xóa!');
    }
}
