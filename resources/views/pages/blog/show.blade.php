{{-- resources/views/pages/blog/show.blade.php --}}
@extends('layouts.app')

@section('title', $post->title . ' - PinkCapy')

@section('content')
<!-- Breadcrumb -->
<div class="grid mb-4">
    @include('partials.breadcrumb', [
    'items' => [
        ['name' => 'Tin tức', 'url' => route('blog.index')]
    ],
    'current' => Str::limit($post->title, 50)
    ])
</div>

<div class="grid pb-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="blog-detail bg-white shadow-sm rounded p-4 p-md-5">
                <!-- Post Header -->
                <header class="blog-detail-header mb-4">
                    <h1 class="blog-detail-title">{{ $post->title }}</h1>
                    <div class="blog-detail-meta d-flex flex-wrap gap-3">
                        <span class="meta-item">
                            <i class="bi bi-calendar3 text-primary"></i>
                            {{ $post->created_at->format('d/m/Y') }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-clock text-primary"></i>
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-person text-primary"></i>
                            Admin
                        </span>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($post->thumb)
                <div class="blog-detail-image mb-4">
                    <img src="{{ $post->thumb }}" alt="{{ $post->title }}" class="img-fluid rounded w-100" style="max-height: 500px; object-fit: cover;">
                </div>
                @endif

                <!-- Post Summary -->
                @if($post->summary)
                <div class="blog-detail-summary mb-4 fs-4">
                    <div class="alert alert-light border-start border-primary">
                        <p class="mb-0">{{ $post->summary }}</p>
                    </div>
                </div>
                @endif

                <!-- Post Content -->
                <div class="blog-detail-content mb-5 fs-5">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Post Conclusion -->
                @if($post->conclusion)
                <div class="blog-detail-conclusion mb-4">
                    <div class="alert alert-info border-0">
                        <h5 class="mb-3"><i class="bi bi-lightbulb-fill"></i> Kết luận</h5>
                        <p class="mb-0">{{ $post->conclusion }}</p>
                    </div>
                </div>
                @endif

                <!-- Share Section -->
                <div class="blog-share mb-4 p-4 bg-light rounded">
                    <h5 class="mb-3"><i class="bi bi-share"></i> Chia sẻ bài viết:</h5>
                    <div class="share-buttons d-flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" class="btn" style="background-color: #3b5998; color: white;">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-info text-white">
                            <i class="bi bi-twitter"></i> Twitter
                        </a>
                        <button onclick="copyLink()" class="btn btn-secondary">
                            <i class="bi bi-link-45deg"></i> Sao chép link
                        </button>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="blog-navigation pt-4 border-top">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <aside class="blog-sidebar">
                <!-- Latest Posts Widget -->
                <div class="sidebar-widget bg-white shadow-sm rounded p-4 mb-4">
                    <h4 style="font-size: 1.6rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #007bff;">Bài viết mới nhất</h4>
                    <div class="latest-posts">
                        @php
                        $latestPosts = \App\Domains\Blog\Entities\BlogPost::where('status', 2)
                            ->where('blogpost_id', '!=', $post->blogpost_id)
                            ->orderByDesc('created_at')
                            ->limit(5)
                            ->get();
                        @endphp

                        @forelse($latestPosts as $latestPost)
                        <div class="latest-post-item mb-3 pb-3 border-bottom">
                            <a href="{{ route('blog.show', $latestPost->slug) }}" class="text-decoration-none">
                                @if($latestPost->thumb)
                                <img src="{{ $latestPost->thumb }}" alt="{{ $latestPost->title }}" class="latest-post-thumb rounded w-100 mb-2" style="height: 350px; object-fit: cover;">
                                @endif
                                <div>
                                    <h6 class="mb-1 text-primary fw-semibold fs-4">{{ Str::limit($latestPost->title, 50) }}</h6>
                                    <small class="text-muted fs-5">
                                        <i class="bi bi-calendar3"></i> {{ $latestPost->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            </a>
                        </div>
                        @empty
                        <p class="text-muted small">Chưa có bài viết khác</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Links Widget -->
                <div class="sidebar-widget bg-white shadow-sm rounded p-4">
                    <h4 style="font-size: 1.6rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #007bff;">Liên kết nhanh</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="{{ route('blog.index') }}" class="text-decoration-none fs-5">
                                <i class="bi bi-house-door"></i> Tất cả bài viết
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('blog.create') }}" class="text-decoration-none fs-5">
                                <i class="bi bi-plus-circle"></i> Đăng bài mới
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('products.index') }}" class="text-decoration-none fs-5">
                                <i class="bi bi-bag"></i> Sản phẩm
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="text-decoration-none fs-5">
                                <i class="bi bi-envelope"></i> Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Đã sao chép link bài viết!');
        }).catch(() => {
            alert('Không thể sao chép link. Vui lòng thử lại!');
        });
    }
</script>
@endpush

@push('styles')
<style>
    .blog-detail-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.3;
        margin-bottom: 1rem;
    }

    .blog-detail-meta {
        color: #7f8c8d;
        font-size: 0.95rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e9ecef;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .blog-detail-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
    }

    .blog-detail-content p {
        margin-bottom: 1.5rem;
    }


    .latest-post-thumb {
        width: 70px;
        height: 70px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .latest-post-item:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }

    .latest-post-item a:hover h6 {
        color: #007bff !important;
    }

    .sidebar-widget a:hover {
        color: #007bff !important;
    }

    @media (max-width: 767px) {
        .blog-detail-title {
            font-size: 1.75rem;
        }
        
        .blog-detail {
            padding: 1.5rem !important;
        }
    }
</style>
@endpush
@endsection
@extends('layouts.app')

@section('title', $post->title . ' - PinkCapy')

@section('content')
<!-- Breadcrumb -->
<div class="grid mb-4">
    @include('partials.breadcrumb', [
    'items' => [
        ['name' => 'Tin tức', 'url' => route('blog.index')]
    ],
    'current' => $post->title
    ])
</div>

<div class="grid pb-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="blog-detail">
                <!-- Post Header -->
                <header class="blog-detail-header mb-4">
                    <h1 class="blog-detail-title">{{ $post->title }}</h1>
                    <div class="blog-detail-meta">
                        <span class="meta-item">
                            <i class="bi bi-calendar3"></i>
                            {{ $post->created_at->format('d/m/Y') }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-clock"></i>
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                        <span class="meta-item">
                            <i class="bi bi-person"></i>
                            Admin
                        </span>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($post->thumb)
                <div class="blog-detail-image mb-4">
                    <img src="{{ $post->thumb }}" alt="{{ $post->title }}" class="img-fluid rounded">
                </div>
                @endif

                <!-- Post Summary -->
                @if($post->summary)
                <div class="blog-detail-summary mb-4">
                    <p class="lead">{{ $post->summary }}</p>
                </div>
                @endif

                <!-- Post Content -->
                <div class="blog-detail-content mb-5">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Post Conclusion -->
                @if($post->conclusion)
                <div class="blog-detail-conclusion mb-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-lightbulb"></i> Kết luận</h5>
                        <p class="mb-0">{{ $post->conclusion }}</p>
                    </div>
                </div>
                @endif

                <!-- Share Section -->
                <div class="blog-share mb-5">
                    <h5>Chia sẻ bài viết:</h5>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="bi bi-twitter"></i> Twitter
                        </a>
                        <button onclick="copyLink()" class="btn btn-secondary btn-sm">
                            <i class="bi bi-link-45deg"></i> Sao chép link
                        </button>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="blog-navigation">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <aside class="blog-sidebar">
                <!-- Latest Posts Widget -->
                <div class="sidebar-widget mb-4">
                    <h4 class="widget-title">Bài viết mới nhất</h4>
                    <div class="latest-posts">
                        @php
                        $latestPosts = \App\Domains\Blog\Entities\BlogPost::where('status', 2)
                            ->where('blogpost_id', '!=', $post->blogpost_id)
                            ->orderByDesc('created_at')
                            ->limit(5)
                            ->get();
                        @endphp

                        @foreach($latestPosts as $latestPost)
                        <div class="latest-post-item mb-3">
                            <a href="{{ route('blog.show', $latestPost->slug) }}" class="d-flex">
                                @if($latestPost->thumb)
                                <img src="{{ $latestPost->thumb }}" alt="{{ $latestPost->title }}" class="latest-post-thumb me-3">
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $latestPost->title }}</h6>
                                    <small class="text-muted">{{ $latestPost->created_at->format('d/m/Y') }}</small>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Categories Widget (Placeholder) -->
                <div class="sidebar-widget">
                    <h4 class="widget-title">Danh mục</h4>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('blog.index') }}">Tất cả bài viết</a></li>
                        <li><a href="{{ route('blog.index') }}">Đánh giá sản phẩm</a></li>
                        <li><a href="{{ route('blog.index') }}">Tin tức công nghệ</a></li>
                        <li><a href="{{ route('blog.index') }}">Hướng dẫn</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Đã sao chép link bài viết!');
        });
    }
</script>
@endpush

@push('styles')
<style>
    .blog-detail-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .blog-detail-meta {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .blog-detail-image img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 8px;
    }

    .blog-detail-summary {
        font-size: 1.1rem;
        color: #555;
        padding: 1rem;
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        border-radius: 4px;
    }

    .blog-detail-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #333;
    }

    .blog-share {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .share-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .sidebar-widget {
        padding: 1.5rem;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }

    .widget-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #007bff;
    }

    .latest-post-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    .latest-post-item a {
        text-decoration: none;
        color: inherit;
    }

    .latest-post-item a:hover h6 {
        color: #007bff;
    }

    .blog-navigation {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e9ecef;
    }
</style>
@endpush
@endsection
