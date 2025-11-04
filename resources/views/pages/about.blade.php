{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.app')

@section('title', 'Giới thiệu - PinkCapyStore')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <div class="mb-4">
        @include('partials.breadcrumb', [
        'items' => [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ],
        'current' => 'Giới thiệu'
        ])
    </div>

    <!-- Hero Section -->
    <section class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary mb-3">PinkCapyStore - Phụ kiện công nghệ chính hãng</h1>
        <p class="lead text-muted col-lg-8 mx-auto">
            Chúng tôi chuyên cung cấp <strong>tai nghe, đồng hồ thông minh và phụ kiện</strong> cho điện thoại, laptop –
            <span class="text-primary">chính hãng 100%</span>, giá tốt, giao nhanh.
        </p>
    </section>

    <!-- Tầm nhìn & Sứ mệnh -->
    <section class="row g-5 mb-5">
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa-solid fa-eye text-primary fa-2x me-3"></i>
                        <h3 class="h4 fw-bold mb-0">Tầm nhìn</h3>
                    </div>
                    <p class="text-muted">
                        Trở thành kênh bán lẻ điện tử trực tuyến <strong>uy tín hàng đầu</strong> tại Việt Nam,
                        mang đến sản phẩm công nghệ chất lượng, giá cạnh tranh và trải nghiệm mua sắm hiện đại.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa-solid fa-flag text-success fa-2x me-3"></i>
                        <h3 class="h4 fw-bold mb-0">Sứ mệnh</h3>
                    </div>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Cung cấp sản phẩm công nghệ <strong>chính hãng</strong> với thông tin minh bạch.</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Đem đến trải nghiệm mua sắm <strong>tiện lợi, nhanh chóng và an toàn</strong>.</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Góp phần thúc đẩy <strong>chuyển đổi số</strong> trong bán lẻ điện tử.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Giá trị cốt lõi -->
    <section class="mb-5">
        <h2 class="h3 fw-bold text-center mb-4">Giá trị cốt lõi</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-light rounded h-100">
                    <i class="fa-solid fa-shield-halved text-primary fa-3x mb-3"></i>
                    <h5 class="fw-bold">Uy tín</h5>
                    <p class="small text-muted">Cam kết hàng chính hãng, minh bạch thông tin</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-light rounded h-100">
                    <i class="fa-solid fa-gem text-success fa-3x mb-3"></i>
                    <h5 class="fw-bold">Chất lượng</h5>
                    <p class="small text-muted">Sản phẩm đạt chuẩn, bảo hành chính hãng</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-light rounded h-100">
                    <i class="fa-solid fa-heart text-danger fa-3x mb-3"></i>
                    <h5 class="fw-bold">Khách hàng là trung tâm</h5>
                    <p class="small text-muted">Trải nghiệm tối ưu, hỗ trợ tận tâm</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-light rounded h-100">
                    <i class="fa-solid fa-lightbulb text-warning fa-3x mb-3"></i>
                    <h5 class="fw-bold">Đổi mới</h5>
                    <p class="small text-muted">Cập nhật công nghệ, AI gợi ý sản phẩm</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sản phẩm chúng tôi bán -->
    <section class="bg-light py-5 rounded">
        <div class="text-center mb-4">
            <h2 class="h3 fw-bold">Chúng tôi chuyên cung cấp</h2>
            <p class="text-muted">Phụ kiện công nghệ chính hãng, chất lượng cao</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4">
                    <i class="fa-solid fa-headphones fa-4x text-primary mb-3"></i>
                    <h5>Tai nghe</h5>
                    <p class="small text-muted">AirPods, Sony, Bose, JBL...</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <i class="fa-solid fa-clock fa-4x text-success mb-3"></i>
                    <h5>Đồng hồ thông minh</h5>
                    <p class="small text-muted">Apple Watch, Samsung, Xiaomi...</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <i class="fa-solid fa-mobile-screen-button fa-4x text-warning mb-3"></i>
                    <h5>Phụ kiện</h5>
                    <p class="small text-muted">Ốp lưng, sạc, cáp, pin dự phòng...</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection