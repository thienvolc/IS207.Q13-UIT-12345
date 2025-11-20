{{-- resources/views/pages/blog/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tin tức - PinkCapyStore')

@section('content')
<!-- Breadcrumb -->
<div class="grid mb-4">
    @include('partials.breadcrumb', [
    'items' => [],
    'current' => 'Tin tức'
    ])
</div>

<div class="grid pb-5">
    <!-- Page Title -->
    <div class="blog-header text-center mb-5">
        <h1 class="blog-main-title">Tin tức & Đánh giá</h1>
        <p class="blog-subtitle">Cập nhật tin tức công nghệ, đánh giá sản phẩm và hướng dẫn sử dụng</p>
    </div>

    <!-- Featured Post (bài viết mới nhất) -->
    @if($posts->count())
    @php $featured = $posts->first(); @endphp
    <div class="featured-post mb-5">
        <div class="row g-0">
            <div class="col-lg-6">
                <div class="featured-image">
                    <img src="{{ $featured->thumb ?? 'https://via.placeholder.com/800x500' }}" alt="Featured Post" class="img-fluid">
                    <span class="badge-featured">Nổi bật</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="featured-content">
                    <div class="post-meta mb-3">
                        <span class="post-category">{{ $featured->summary }}</span>
                        <span class="post-date"><i class="fa-regular fa-clock"></i> {{ $featured->created_at->diffForHumans() }}</span>
                    </div>
                    <h2 class="featured-title">{{ $featured->title }}</h2>
                    <p class="featured-excerpt">{{ $featured->summary }}</p>
                    <a href="#" class="btn btn-primary">Đọc tiếp <i class="fa-solid fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Categories Filter -->
    <div class="blog-filters mb-4">
        <div class="filter-label">Danh mục:</div>
        <div class="filter-buttons">
            <button class="filter-btn active" data-category="all">
                Tất cả
            </button>
            <button class="filter-btn" data-category="review">
                <i class="fa-solid fa-star"></i> Đánh giá
            </button>
            <button class="filter-btn" data-category="news">
                <i class="fa-regular fa-newspaper"></i> Tin tức
            </button>
            <button class="filter-btn" data-category="guide">
                <i class="fa-solid fa-book"></i> Hướng dẫn
            </button>
            <button class="filter-btn" data-category="tech">
                <i class="fa-solid fa-microchip"></i> Công nghệ
            </button>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="row g-4 mb-5">
        @foreach($posts->skip(1) as $post)
        <div class="col-lg-4 col-md-6">
            <article class="blog-card">
                <div class="blog-card-image">
                    <img src="{{ $post->thumb ?? 'https://via.placeholder.com/400x250' }}" alt="Blog Post" class="img-fluid">
                    <span class="post-badge">{{ $post->summary }}</span>
                </div>
                <div class="blog-card-content">
                    <div class="post-meta">
                        <span class="post-date"><i class="fa-regular fa-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="post-title">
                        <a href="#">{{ $post->title }}</a>
                    </h3>
                    <p class="post-excerpt">{{ $post->summary }}</p>
                    <div class="post-footer">
                        <div class="post-author">
                            <i class="fa-solid fa-user-circle"></i> Admin
                        </div>
                        <a href="#" class="read-more">Đọc tiếp <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </article>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <nav aria-label="Blog pagination">
        {{ $posts->links() }}
    </nav>
    @endif
    <div class="mb-3 text-end">
        <a href="{{ url('/blog/create') }}" class="btn btn-primary">Đăng bài mới</a>
    </div>
</div>
@endsection