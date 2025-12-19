<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\AppController;
use App\Domains\Blog\Entities\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends AppController
{
    /**
     * Hiển thị danh sách bài viết blog.
     */
    public function index(Request $request)
    {
        $query = BlogPost::where('status', 2); // Status 2 = Published
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('summary', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }
        
        $posts = $query->orderByDesc('created_at')->paginate(13);
        
        return view('pages.blog.index', compact('posts'));
    }

    /**
     * Hiển thị chi tiết bài viết.
     */
    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('status', 2)
            ->firstOrFail();
        
        return view('pages.blog.show', compact('post'));
    }
}
