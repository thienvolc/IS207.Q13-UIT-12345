{{-- resources/views/pages/contact.blade.php --}}
@extends('layouts.app')

@section('title', 'Liên hệ - PinkCapyStore')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <div class="mb-4">
        @include('partials.breadcrumb', [
        'items' => [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ],
        'current' => 'Liên hệ'
        ])
    </div>

    <div class="row g-5">
        <!-- Thông tin liên hệ -->
        <div class="col-lg-5">
            <h2 class="h3 fw-bold mb-4">Liên hệ với chúng tôi</h2>
            <p class="text-muted mb-4">
                Bạn cần hỗ trợ? Hãy liên hệ ngay để được tư vấn nhanh chóng!
            </p>

            <ul class="list-unstyled">
                <li class="d-flex align-items-start mb-4">
                    <i class="fa-solid fa-location-dot text-primary fa-lg mt-1 me-3"></i>
                    <div>
                        <strong>Địa chỉ:</strong><br>
                        123 Nguyễn Văn Cừ, Quận 5, TP. Hồ Chí Minh
                    </div>
                </li>
                <li class="d-flex align-items-center mb-4">
                    <i class="fa-solid fa-phone text-success fa-lg me-3"></i>
                    <div>
                        <strong>Hotline:</strong><br>
                        <a href="tel:19006868" class="text-decoration-none">1900 6868</a> (8h-21h)
                    </div>
                </li>
                <li class="d-flex align-items-center mb-4">
                    <i class="fa-solid fa-envelope text-warning fa-lg me-3"></i>
                    <div>
                        <strong>Email:</strong><br>
                        <a href="mailto:support@techstore.vn" class="text-decoration-none">support@techstore.vn</a>
                    </div>
                </li>
                <li class="d-flex align-items-center mb-4">
                    <i class="fa-solid fa-clock text-info fa-lg me-3"></i>
                    <div>
                        <strong>Giờ làm việc:</strong><br>
                        Thứ 2 - Thứ 7: 8:00 - 21:00<br>
                        Chủ nhật: 9:00 - 18:00
                    </div>
                </li>
            </ul>

            <!-- Mạng xã hội -->
            <div class="mt-5">
                <h5 class="fw-bold mb-3">Kết nối với chúng tôi</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-primary rounded-circle p-2" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-outline-danger rounded-circle p-2" aria-label="YouTube">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                    <a href="#" class="btn btn-outline-info rounded-circle p-2" aria-label="Zalo">
                        <i class="fa-brands fa-zalo"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark rounded-circle p-2" aria-label="TikTok">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form liên hệ -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h3 class="h4 fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h3>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ tên *</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" placeholder="VD: Hỏi về tai nghe Sony">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nội dung *</label>
                                <textarea class="form-control" rows="5" required placeholder="Mô tả chi tiết câu hỏi hoặc yêu cầu của bạn..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa-solid fa-paper-plane me-2"></i>
                                    Gửi tin nhắn
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bản đồ -->
    <div class="mt-5">
        <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.447!2d106.680!3d10.762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38c5!2s123%20Nguy%E1%BB%85n%20V%C4%83n%20C%E1%BB%AB!5e0!3m2!1svi!2s!4v1234567890"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>
@endsection