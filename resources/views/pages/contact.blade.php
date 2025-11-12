{{-- resources/views/pages/contact.blade.php --}}
@extends('layouts.app')

@section('title', 'Liên hệ - PinkCapy')

@section('content')
<div class="grid">
    <!-- Breadcrumb -->
    <div class="mb-4">
        @include('partials.breadcrumb', [
        'items' => [],
        'current' => 'Liên hệ'
        ])
    </div>

    <!-- Header Section -->
    <div class="contact-header mb-5">
        <h1 class="title-lg fw-bold mb-3 text-center">Liên hệ với chúng tôi</h1>
        <p class="text-center text-muted fs-5 mb-0">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</p>
    </div>

    <!-- Contact Info Cards -->
    <div class="grid-row mb-5">
        <div class="grid__col-3">
            <div class="contact-card">
                <div class="contact-card__icon contact-card__icon--primary">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h3 class="contact-card__title">Địa chỉ</h3>
                <p class="contact-card__text">
                    Linh Trung, Thủ Đức<br>
                    TP. Hồ Chí Minh
                </p>
            </div>
        </div>

        <div class="grid__col-3">
            <div class="contact-card">
                <div class="contact-card__icon contact-card__icon--success">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <h3 class="contact-card__title">Hotline</h3>
                <p class="contact-card__text">
                    <a href="tel:19006868">1900 6868</a><br>
                    <span class="text-muted small">8h - 21h hàng ngày</span>
                </p>
            </div>
        </div>

        <div class="grid__col-3">
            <div class="contact-card">
                <div class="contact-card__icon contact-card__icon--warning">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <h3 class="contact-card__title">Email</h3>
                <p class="contact-card__text">
                    <a href="mailto:support@pinkcapy.vn">support@pinkcapy.vn</a><br>
                    <span class="text-muted small">Phản hồi trong 24h</span>
                </p>
            </div>
        </div>

        <div class="grid__col-3">
            <div class="contact-card">
                <div class="contact-card__icon contact-card__icon--info">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <h3 class="contact-card__title">Giờ làm việc</h3>
                <p class="contact-card__text">
                    T2 - T6: 8:00 - 20:00<br>
                    T7 - Chủ nhật: 9:00 - 21:00
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Form Section (Full Width) -->
    <div class="contact-form-section mb-5">
        <div class="contact-form-wrapper">
            <h2 class="h3 fw-bold mb-4 text-center">Gửi tin nhắn cho chúng tôi</h2>
            <form id="contactForm" class="contact-form">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Nguyễn Văn A" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="example@email.com" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone" placeholder="0912 345 678">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Chủ đề</label>
                            <select class="form-control" name="subject">
                                <option value="">-- Chọn chủ đề --</option>
                                <option value="support">Hỗ trợ kỹ thuật</option>
                                <option value="order">Đơn hàng</option>
                                <option value="product">Sản phẩm</option>
                                <option value="warranty">Bảo hành</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="message" rows="5" placeholder="Nhập nội dung tin nhắn của bạn..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-send-fill me-2"></i>Gửi tin nhắn
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Map & Sidebar Section -->
    <div class="contact-bottom-section mb-5">
        <div class="row g-4">
            <!-- Map (Left - 8 cols) -->
            <div class="col-lg-8">
                <div class="contact-map-wrapper">
                    <h2 class="h3 fw-bold mb-4">Vị trí cửa hàng</h2>
                    <div class="contact-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.4544374621546!2d106.62420897570756!3d10.852432257778526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752bee0b0ef9e5%3A0x5096b3b61b0e2d3a!2sUniversity%20of%20Information%20Technology%20-%20VNUHCM!5e0!3m2!1sen!2s!4v1699000000000!5m2!1sen!2s"
                            width="100%"
                            height="500"
                            style="border:0; border-radius: 12px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right - 4 cols) -->
            <div class="col-lg-4">
                <!-- Social Media -->
                <div class="contact-sidebar-card mb-4">
                    <h3 class="h5 fw-bold mb-3">Kết nối với chúng tôi</h3>
                    <div class="social-links">
                        <a href="#" class="social-link social-link--facebook">
                            <i class="bi bi-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="social-link social-link--youtube">
                            <i class="bi bi-youtube"></i>
                            <span>YouTube</span>
                        </a>
                        <a href="#" class="social-link social-link--instagram">
                            <i class="bi bi-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="#" class="social-link social-link--tiktok">
                            <i class="bi bi-tiktok"></i>
                            <span>TikTok</span>
                        </a>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="contact-sidebar-card">
                    <h3 class="h5 fw-bold mb-3">Câu hỏi thường gặp</h3>
                    <div class="faq-list">
                        <a href="#" class="faq-item">
                            <i class="bi bi-question-circle-fill"></i>
                            <span>Chính sách đổi trả</span>
                        </a>
                        <a href="#" class="faq-item">
                            <i class="bi bi-question-circle-fill"></i>
                            <span>Hướng dẫn mua hàng</span>
                        </a>
                        <a href="#" class="faq-item">
                            <i class="bi bi-question-circle-fill"></i>
                            <span>Phương thức thanh toán</span>
                        </a>
                        <a href="#" class="faq-item">
                            <i class="bi bi-question-circle-fill"></i>
                            <span>Chính sách bảo hành</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Contact Form Handler
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        // Get form data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        // TODO: Send to API
        console.log('Form data:', data);

        // Show success message
        alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
        this.reset();
    });
</script>
@endpush
@endsection