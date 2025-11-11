<!-- Newsletter Bar -->
<div class="newsletter-bar">
    <div class="container">
        <div class="newsletter-bar__inner">
            <div class="newsletter-bar__icon">
                <i class="bi bi-envelope"></i>
            </div>
            <div class="newsletter-bar__text">
                <h3>Đăng ký nhận tin</h3>
                <p>Nhận <strong>khuyến mãi</strong> đặc biệt</p>
            </div>
            <form class="newsletter-bar__form">
                <input type="email" placeholder="Email của bạn" required>
                <button type="submit">Đăng ký</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer Main -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- Column 1: Logo & Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-widget">
                    <div style="display: flex">
                        <a style="flex: 1;" class="footer-logo" href="/">
                            <img src="{{ asset('img/logo.png') }}" alt="PinkCapy">
                        </a>
                    </div>

                    <p class="footer-desc">Điện tử chất lượng cao, giá cả phải chăng cho mọi gia đình Việt Nam.</p>
                    <div class="footer-contact">
                        <p><i class="bi bi-telephone"></i> <strong>Hotline:</strong> 1900 1234</p>
                        <p><i class="bi bi-envelope"></i> <strong>Email:</strong> support@pinkcapy.com</p>
                    </div>
                    <div class="footer-social">
                        <a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Products -->
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-widget">
                    <h4 class="footer-title">Sản phẩm</h4>
                    <ul class="footer-menu">
                        <li><a href="/san-pham">Laptop</a></li>
                        <li><a href="/san-pham">Điện thoại</a></li>
                        <li><a href="/san-pham">Tablet</a></li>
                        <li><a href="#">Máy ảnh</a></li>
                        <li><a href="#">TV & Audio</a></li>
                        <li><a href="#">Phụ kiện</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 3: Customer Care -->
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-widget">
                    <h4 class="footer-title">Chăm sóc khách hàng</h4>
                    <ul class="footer-menu">
                        <li><a href="/lien-he">Liên hệ</a></li>
                        <li><a href="/hoi-dap">Hỏi đáp</a></li>
                        <li><a href="/chinh-sach-bao-hanh">Chính sách bảo hành</a></li>
                        <li><a href="/chinh-sach-doi-tra">Chính sách đổi trả</a></li>
                        <li><a href="/van-chuyen">Vận chuyển</a></li>
                        <li><a href="/thanh-toan">Thanh toán</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 4: Company Info -->
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-widget">
                    <h4 class="footer-title">Thông tin</h4>
                    <ul class="footer-menu">
                        <li><a href="/gioi-thieu">Về chúng tôi</a></li>
                        <li><a href="/tin-tuc">Tin tức</a></li>
                        <li><a href="/tuyen-dung">Tuyển dụng</a></li>
                        <li><a href="/dieu-khoan">Điều khoản</a></li>
                        <li><a href="/chinh-sach-bao-mat">Chính sách bảo mật</a></li>
                        <li><a href="/sitemap">Sitemap</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 5: Store Address -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-widget">
                    <h4 class="footer-title">Địa chỉ cửa hàng</h4>
                    <div class="footer-address">
                        <p><i class="bi bi-geo-alt"></i> Khu phố 6, Phường Linh Trung, TP. Thủ Đức, TP. HCM</p>
                        <p><strong>Giờ mở cửa:</strong><br>
                            T2 - T6: 8:00 - 20:00<br>
                            T7 - CN: 9:00 - 21:00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom__inner">
                <p class="copyright">© 2025 <strong>PinkCapy</strong>. Dự án IS207 - UIT.</p>
                <div class="payment-methods">
                    <img src="{{ asset('img/visa.jpg') }}" alt="Visa">
                    <img src="{{ asset('img/mater_card..jpg') }}" alt="Mastercard">
                    <img src="{{ asset('img/discover.jpg') }}" alt="Discover">
                    <img src="{{ asset('img/paypal.jpg') }}" alt="PayPal">
                    <img src="{{ asset('img/skrill.jpg') }}" alt="Skrill">
                </div>
            </div>
        </div>
    </div>
</footer>