<?php

namespace App\Http\Controllers\Web\Content;

use App\Domains\Blog\DTOs\Commands\CreateBlogPostDTO;
use App\Domains\Blog\Services\BlogPostService;
use App\Http\Controllers\AppController;
use App\Infra\Helpers\StringHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBlogController extends AppController
{
    public function __construct(
        private readonly BlogPostService $blogPostService
    ) {
    }

    /**
     * Xử lý đăng bài viết mới từ form
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'thumb' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $thumbUrl = null;
        if ($request->hasFile('thumb') && $request->file('thumb')->isValid()) {
            try {
                // Upload to storage
                $file = $request->file('thumb');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('blog-thumbs', $filename, 'public');
                $thumbUrl = asset('storage/' . $path);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['thumb' => 'Lỗi upload ảnh: ' . $e->getMessage()]);
            }
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

        return redirect()->route('account.my-posts')
            ->with('success', 'Bài viết đã được tạo và đang chờ phê duyệt!');
    }

    /**
     * Hiển thị danh sách bài viết của user hiện tại
     */
    public function myPosts()
    {
        // Get posts của user hiện tại
        $posts = $this->blogPostService->getPostsByCurrentUser();
        
        return view('pages.account.my-posts', compact('posts'));
    }
}
