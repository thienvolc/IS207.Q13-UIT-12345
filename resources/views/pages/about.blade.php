{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.app')

@section('title', 'PinkCapy - Giới thiệu')

@section('content')
<!-- Breadcrumb -->
<div class="grid mb-4">
    @include('partials.breadcrumb', [
    'items' => [],
    'current' => 'Giới thiệu'
    ])
</div>

<div class="grid pb-5">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-content text-center">
            <div class="hero-img mb-3">
                <img src="{{ asset('img/logo.svg') }}" alt="PinkCapyStore Logo">
            </div>
            <h1>PinkCapyStore</h1>
            <p class="col-lg-10 mx-auto">
                Chuyên cung cấp <strong>tai nghe, đồng hồ thông minh và phụ kiện</strong> công nghệ<br>
                chính hãng 100% - Giá tốt nhất thị trường
            </p>
        </div>
    </section>

    <!-- Tầm nhìn & Sứ mệnh -->
    <section class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="vision-mission-card">
                <div class="card-body">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3>Tầm nhìn</h3>
                    <p>
                        Trở thành kênh bán lẻ điện tử trực tuyến <strong>uy tín hàng đầu</strong> tại Việt Nam,
                        mang đến sản phẩm công nghệ chất lượng, giá cạnh tranh và trải nghiệm mua sắm hiện đại.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="vision-mission-card mission">
                <div class="card-body">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-flag"></i>
                    </div>
                    <h3>Sứ mệnh</h3>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fa-solid fa-check text-success"></i> Cung cấp sản phẩm công nghệ <strong>chính hãng</strong> với thông tin minh bạch</li>
                        <li><i class="fa-solid fa-check text-success"></i> Đem đến trải nghiệm mua sắm <strong>tiện lợi, nhanh chóng và an toàn</strong></li>
                        <li><i class="fa-solid fa-check text-success"></i> Góp phần thúc đẩy <strong>chuyển đổi số</strong> trong bán lẻ điện tử</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Giá trị cốt lõi -->
    <section class="core-values-section">
        <div class="text-center mb-4">
            <div class="section-icon">
                <i class="fa-solid fa-star"></i>
            </div>
            <h2 class="core-values-title">Giá trị cốt lõi</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="core-value-card">
                    <div class="icon-circle">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h5>Uy tín</h5>
                    <p>Cam kết hàng chính hãng, minh bạch thông tin</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="core-value-card">
                    <div class="icon-circle">
                        <i class="fa-solid fa-gem"></i>
                    </div>
                    <h5>Chất lượng</h5>
                    <p>Sản phẩm đạt chuẩn, bảo hành chính hãng</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="core-value-card">
                    <div class="icon-circle">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <h5>Khách hàng là trung tâm</h5>
                    <p>Trải nghiệm tối ưu, hỗ trợ tận tâm</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="core-value-card">
                    <div class="icon-circle">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h5>Đổi mới</h5>
                    <p>Cập nhật công nghệ, AI gợi ý sản phẩm</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sản phẩm chúng tôi bán -->
    <section class="products-section mb-5">
        <div class="text-center">
            <div class="section-icon">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h2 class="products-section-title">Chúng tôi chuyên cung cấp</h2>
            <p class="products-section-subtitle">Phụ kiện công nghệ chính hãng, chất lượng cao</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="product-category">
                    <div class="icon-box">
                        <i class="fa-solid fa-headphones"></i>
                    </div>
                    <h5>Tai nghe</h5>
                    <p>AirPods, Sony, Bose, JBL, Samsung...</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="product-category">
                    <div class="icon-box">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <h5>Đồng hồ thông minh</h5>
                    <p>Apple Watch, Samsung Galaxy Watch, Xiaomi...</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="product-category">
                    <div class="icon-box">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <h5>Phụ kiện</h5>
                    <p>Ốp lưng, sạc, cáp, pin dự phòng, kính cường lực...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-us-section mb-5">
        <div class="text-center mb-4">
            <div class="section-icon">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h2 class="section-title">Tại sao chọn PinkCapyStore?</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon bg-light-pink">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Sản phẩm chính hãng 100%</h4>
                        <p>Cam kết mọi sản phẩm đều có nguồn gốc xuất xứ rõ ràng, tem phiếu đầy đủ. Hỗ trợ kiểm tra trước khi thanh toán.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon bg-light-pink">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Giao hàng nhanh chóng</h4>
                        <p>Giao hàng toàn quốc, nhanh trong 24h tại nội thành. Hỗ trợ giao hàng COD, kiểm tra hàng trước khi nhận.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon bg-light-pink">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Bảo hành chính hãng</h4>
                        <p>Đầy đủ chế độ bảo hành theo nhà sản xuất. Hỗ trợ đổi trả trong 7 ngày nếu có lỗi từ nhà sản xuất.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon bg-light-pink">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Hỗ trợ 24/7</h4>
                        <p>Đội ngũ tư vấn nhiệt tình, am hiểu sản phẩm. Sẵn sàng hỗ trợ khách hàng mọi lúc, mọi nơi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section mb-5">
        <div class="text-center mb-4">
            <div class="section-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <h2 class="section-title">Con số ấn tượng</h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Khách hàng</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">240+</div>
                    <div class="stat-label">Sản phẩm</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Hài lòng</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Hỗ trợ</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="cta-section">
        <div class="cta-content text-center">
            <h2 class="cta-title">Bạn cần tư vấn thêm?</h2>
            <p class="cta-subtitle">Đội ngũ chúng tôi luôn sẵn sàng hỗ trợ bạn tìm sản phẩm phù hợp nhất</p>
            <div class="cta-buttons">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fa-solid fa-envelope me-2"></i>Liên hệ ngay
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fa-solid fa-shopping-bag me-2"></i>Xem sản phẩm
                </a>
            </div>
        </div>
    </section>
</div>
@endsection