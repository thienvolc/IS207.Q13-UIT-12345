<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\AppController;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends AppController
{
    /**
     * Hiển thị danh sách bài viết blog.
     */
    public function index(Request $request)
    {
        $posts = BlogPost::where('status', 1)
            ->orderByDesc('created_at')
            ->paginate(12);
        return view('pages.blog.index', compact('posts'));
    }
}
