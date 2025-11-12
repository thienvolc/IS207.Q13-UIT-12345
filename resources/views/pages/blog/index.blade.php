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

    <!-- Featured Post -->
    <div class="featured-post mb-5">
        <div class="row g-0">
            <div class="col-lg-6">
                <div class="featured-image">
                    <img src="https://via.placeholder.com/800x500" alt="Featured Post" class="img-fluid">
                    <span class="badge-featured">Nổi bật</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="featured-content">
                    <div class="post-meta mb-3">
                        <span class="post-category">Đánh giá</span>
                        <span class="post-date"><i class="fa-regular fa-clock"></i> 2 ngày trước</span>
                    </div>
                    <h2 class="featured-title">Top 5 tai nghe không dây đáng mua nhất năm 2025</h2>
                    <p class="featured-excerpt">
                        Khám phá những mẫu tai nghe không dây được đánh giá cao nhất với chất lượng âm thanh tuyệt vời,
                        thời lượng pin ấn tượng và thiết kế hiện đại. So sánh chi tiết từ AirPods Pro đến Sony WH-1000XM5...
                    </p>
                    <a href="#" class="btn btn-primary">
                        Đọc tiếp <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

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
        <!-- Post 1 -->
        <div class="col-lg-4 col-md-6">
            <article class="blog-card">
                <div class="blog-card-image">
                    <img src="https://via.placeholder.com/400x250" alt="Blog Post" class="img-fluid">
                    <span class="post-badge review">Đánh giá</span>
                </div>
                <div class="blog-card-content">
                    <div class="post-meta">
                        <span class="post-date"><i class="fa-regular fa-clock"></i> 3 ngày trước</span>
                        <span class="post-views"><i class="fa-regular fa-eye"></i> 1.2K</span>
                    </div>
                    <h3 class="post-title">
                        <a href="#">AirPods Pro 2023 - Đáng đồng tiền bát gạo?</a>
                    </h3>
                    <p class="post-excerpt">
                        Trải nghiệm chi tiết về AirPods Pro thế hệ mới với chip H2, chống ồn tốt hơn 2 lần...
                    </p>
                    <div class="post-footer">
                        <div class="post-author">
                            <i class="fa-solid fa-user-circle"></i> Admin
                        </div>
                        <a href="#" class="read-more">Đọc tiếp <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Post 2 -->
        <div class="col-lg-4 col-md-6">
            <article class="blog-card">
                <div class="blog-card-image">
                    <img src="https://via.placeholder.com/400x250" alt="Blog Post" class="img-fluid">
                    <span class="post-badge news">Tin tức</span>
                </div>
                <div class="blog-card-content">
                    <div class="post-meta">
                        <span class="post-date"><i class="fa-regular fa-clock"></i> 5 ngày trước</span>
                        <span class="post-views"><i class="fa-regular fa-eye"></i> 856</span>
                    </div>
                    <h3 class="post-title">
                        <a href="#">Samsung ra mắt Galaxy Watch 6 với nhiều tính năng sức khỏe mới</a>
                    </h3>
                    <p class="post-excerpt">
                        Galaxy Watch 6 mới nhất của Samsung tích hợp cảm biến đo đường huyết không xâm lấn...
                    </p>
                    <div class="post-footer">
                        <div class="post-author">
                            <i class="fa-solid fa-user-circle"></i> Admin
                        </div>
                        <a href="#" class="read-more">Đọc tiếp <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Post 3 -->
        <div class="col-lg-4 col-md-6">
            <article class="blog-card">
                <div class="blog-card-image">
                    <img src="https://via.placeholder.com/400x250" alt="Blog Post" class="img-fluid">
                    <span class="post-badge guide">Hướng dẫn</span>
                </div>
                <div class="blog-card-content">
                    <div class="post-meta">
                        <span class="post-date"><i class="fa-regular fa-clock"></i> 1 tuần trước</span>
                        <span class="post-views"><i class="fa-regular fa-eye"></i> 2.1K</span>
                    </div>
                    <h3 class="post-title">
                        <a href="#">Cách chọn tai nghe phù hợp với nhu cầu của bạn</a>
                    </h3>
                    <p class="post-excerpt">
                        Hướng dẫn chi tiết giúp bạn lựa chọn tai nghe phù hợp dựa trên mục đích sử dụng...
                    </p>
                    <div class="post-footer">
                        <div class="post-author">
                            <i class="fa-solid fa-user-circle"></i> Admin
                        </div>
                        <a href="#" class="read-more">Đọc tiếp <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Post 4 -->
        <div class="col-lg-4 col-md-6">
            <article class="blog-card">
                <div class="blog-card-image">
                    <img src="https://via.placeholder.com/400x250" alt="Blog Post" class="img-fluid">
                    <span class="post-badge tech">Công nghệ</span>
                </div>
                <div class="blog-card-content">
                    <div class="post-meta">
                        <span class="post-date"><i class="fa-regular fa-clock"></i> 1 tuần trước</span>
                        <span class="post-views"><i class="fa-regular fa-eye"></i> 945</span>
                    </div>
                    <h3 class="post-title">
                        <a href="#">Công nghệ chống ồn chủ động (ANC) hoạt động như thế nào?</a>
                    </h3>
                    <p class="post-excerpt">
                        Tìm hiểu về công nghệ chống ồn chủ động và vì sao nó quan trọng với tai nghe cao cấp...
                    </p>
                    <div class="post-footer">
                        <div class="post-author">
                            <i class="fa-solid fa-user-circle"></i> Admin
                        </div>
                        <a href="#" class="read-more">Đọc tiếp <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Post 5 -->
        <div class="col-lg-4 col-md-6">
            <article class="blog-card">
                <div class="blog-card-image">
                    <img src="https://via.placeholder.com/400x250" alt="Blog Post" class="img-fluid">
                    <span class="post-badge review">Đánh giá</span>
                </div>
                <div class="blog-card-content">
                    <div class="post-meta">
                        <span class="post-date"><i class="fa-regular fa-clock"></i> 2 tuần trước</span>
                        <span class="post-views"><i class="fa-regular fa-eye"></i> 1.8K</span>
                    </div>
                    <h3 class="post-title">
                        <a href="#">So sánh Apple Watch Series 9 vs Samsung Galaxy Watch 6</a>
                    </h3>
                    <p class="post-excerpt">
                        Đánh giá chi tiết và so sánh hai chiếc smartwatch hàng đầu hiện nay về tính năng...
                    </p>
                    <div class="post-footer">
                        <div class="post-author">
                            <i class="fa-solid fa-user-circle"></i> Admin
                        </div>
                        <a href="#" class="read-more">Đọc tiếp <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Post 6 -->
        <div class="col-lg-4 col-md-6">
            <article class="blog-card">
                <div class="blog-card-image">
                    <img src="https://via.placeholder.com/400x250" alt="Blog Post" class="img-fluid">
                    <span class="post-badge guide">Hướng dẫn</span>
                </div>
                <div class="blog-card-content">
                    <div class="post-meta">
                        <span class="post-date"><i class="fa-regular fa-clock"></i> 2 tuần trước</span>
                        <span class="post-views"><i class="fa-regular fa-eye"></i> 1.5K</span>
                    </div>
                    <h3 class="post-title">
                        <a href="#">Cách bảo quản và vệ sinh tai nghe đúng cách</a>
                    </h3>
                    <p class="post-excerpt">
                        Hướng dẫn chi tiết cách vệ sinh và bảo quản tai nghe để kéo dài tuổi thọ sản phẩm...
                    </p>
                    <div class="post-footer">
                        <div class="post-author">
                            <i class="fa-solid fa-user-circle"></i> Admin
                        </div>
                        <a href="#" class="read-more">Đọc tiếp <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Blog pagination">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <span class="page-link"><i class="fa-solid fa-chevron-left"></i></span>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item">
                <a class="page-link" href="#"><i class="fa-solid fa-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
</div>
@endsection