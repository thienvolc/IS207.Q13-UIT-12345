{{-- resources/views/pages/blog/index.blade.php --}}
@extends('layouts.app')

@section('title', 'PinkCapy - Tin tức')

@section('content')
<!-- Breadcrumb -->
<div class="grid mb-4">
    @include('partials.breadcrumb', [
    'items' => [],
    'current' => 'Tin tức'
    ])
</div>

<div class="grid">
    <!-- Header Section -->
    <div class="row align-items-center text-center mb-4">
        <h1 class="blog-title mb-2">TIN TỨC MỚI</h1>
        <p class="text-muted mb-0 fs-5">Khám phá những bài viết mới nhất về công nghệ</p>
    </div>
    <div class="text-md-end mt-3 mb-4">
        <a href="{{ route('blog.create') }}" class="btn btn-primary btn-create-post">
            <i class="bi bi-plus-lg"></i> Tạo bài viết
        </a>
    </div>

    <!-- Featured Post -->
    @if($posts->count())
    @php $featured = $posts->first(); @endphp
    <div class="featured-post mb-4">
        <div class="card border-0 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5">
                    <img src="{{ $featured->thumb ?? 'https://via.placeholder.com/600x400' }}" 
                         alt="{{ $featured->title }}" 
                         class="img-fluid object-fit-cover">
                </div>
                <div class="col-md-7">
                    <div class="card-body p-4">
                        <span class="badge bg-danger mb-2 fs-5" style="padding: 10px">Nổi bật</span>
                        <h2 class="card-title mb-3 fw-bold">
                            <a href="{{ route('blog.show', $featured->slug) }}" class="text-dark text-decoration-none">
                                {{ $featured->title }}
                            </a>
                        </h2>
                        <p class="card-text text-muted mb-3 fs-5">{{ Str::limit($featured->summary, 150) }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-calendar3 me-1"></i>
                            <span class="me-3">{{ $featured->created_at->format('d/m/Y') }}</span>
                            <i class="bi bi-person-circle me-1"></i>
                            <span>Admin</span>
                        </div>
                        <a href="{{ route('blog.show', $featured->slug) }}" class="btn btn-outline-primary">
                            Đọc thêm <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Blog Posts Grid -->
    <div class="row g-3 mb-4">
        @forelse($posts->skip(1) as $post)
        <div class="col-lg-4 col-md-6">
            <article class="blog-card card">
                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none">
                    <img src="{{ $post->thumb ?? 'https://via.placeholder.com/400x250' }}" 
                         alt="{{ $post->title }}" 
                         class="card-img-top" 
                         style="height: 200px;; object-fit: cover;">
                </a>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                    </div>
                    <h3 class="card-title h5 mb-2 fw-bold">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-primary text-decoration-none post-link">
                            {{ Str::limit($post->title, 60) }}
                        </a>
                    </h3>
                    <p class="card-text mb-3">{{ Str::limit($post->summary, 80) }}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-info text-muted">
                        Xem chi tiết
                    </a>
                </div>
            </article>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-journal-text text-muted" style="font-size: 3rem;"></i>
                <h4 class="mt-3 text-muted">Chưa có bài viết nào</h4>
                <p class="text-muted">Hãy là người đầu tiên đăng bài viết!</p>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Blog pagination">
            {{ $posts->links('pagination::bootstrap-5') }}
        </nav>
    </div>
    @endif
</div>

@push('styles')
<style>
    .blog-title {
        font-size: 2rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.5rem;
    }

    .btn-create-post {
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        border-radius: 8px;
    }

    .featured-post .card {
        border-radius: 12px;
        transition: transform 0.3s ease;
    }

    .featured-post .card:hover {
        transform: translateY(-2px);
    }

    .featured-post .card-title a:hover {
        color: #0d6efd !important;
    }

    .blog-card {
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    }

    .blog-card .card-img-top {
        border-radius: 10px 10px 0 0;
        transition: transform 0.3s ease;
    }

    .blog-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .blog-card .post-link:hover {
        color: #0d6efd !important;
    }

    .object-fit-cover {
        object-fit: cover;
    }

    /* Pagination Styling */
    .pagination {
        gap: 0.5rem;
    }

    .pagination .page-link {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    @media (max-width: 768px) {
        .blog-title {
            font-size: 1.5rem;
        }
        
        .featured-post .card-body {
            padding: 1.5rem !important;
        }

        .pagination .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush
@endsection