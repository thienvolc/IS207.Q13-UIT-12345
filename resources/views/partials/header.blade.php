<header class="header">
  <nav class="mbar d-lg-none">
    <button class="mbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mOffcanvas">
      <i class="fa-solid fa-bars"></i>
    </button>
    <a href="/" class="mbar__brand">
      <img src="{{ asset('img/logo_pinkcapy.svg') }}" alt="PinkCapy" class="mbar__brand-img">
    </a>
    <div class="mbar__actions">
      <button class="mbar__icon" type="button" data-bs-toggle="collapse" data-bs-target="#mSearchCollapse">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
      <a href="/account" class="mbar__icon"><i class="fa-regular fa-user"></i></a>
      <a href="/cart" class="mbar__icon position-relative">
        <i class="bi bi-handbag-fill"></i>
        <span class="mbar__badge">2</span>
      </a>
    </div>
  </nav>

  <!-- search collapse mobile -->
  <div class="collapse d-lg-none" id="mSearchCollapse">
    <div class="msearch">
      <input class="msearch__input" type="text" placeholder="Bạn muốn tìm gì hôm nay?">
      <button class="msearch__btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
  </div>
  <div class="header-main"></div>
    <!-- Header topbar -->
    <div class="header-topbar grid">
      <ul class="header-topbar-list">
        <li class="header-topbar-item"><a href="/">Chào mừng bạn đến với PinkCapy Store</a></li>
      </ul>
      <ul class="header-topbar-list">
        <li class="header-topbar-item"><i class="fa-solid fa-location-dot"></i>Thành phố Hồ Chí Minh</li>
        <li class="header-topbar-item"><i class="fa-solid fa-truck-fast"></i> Giao hàng nhanh chóng</li>
        <li class="header-topbar-item">
          <i class="fa-solid fa-language"></i>Ngôn ngữ
          <ul class="header-topbar-submenu">
            <li class="header-topbar-submenu-item"><a href="/en">Tiếng Anh</a></li>
            <li class="header-topbar-submenu-item"><a href="/vi">Tiếng Việt</a></li>
          </ul>
        </li>
      </ul>
    </div>
      <!-- Header body -->
    <div class="header-row grid">
      <a class="logo" href="/">
        <img src="{{ asset('img/logo_pinkcapy.svg') }}" alt="PinkCapy" class="logo-img">
      </a>
      <div class="header-search">
          <input type="text" class="header-search-input" placeholder="Bạn muốn tìm gì hôm nay?">
          <div class="header-search-category dropdown">
            <button class="btn dropdown-toggle search-cat-btn" type="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              Danh mục
            </button>
            <ul class="dropdown-menu header-search-category-menu">
              <li><a class="dropdown-item" href="/laptops">Laptops</a></li>
              <li><a class="dropdown-item" href="/smartwatches">Đồng hồ</a></li>
              <li><a class="dropdown-item" href="/accessories">Phụ kiện</a></li>
            </ul>
          </div>
          <button type="submit" class="header-search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
      <div class="header-options">
        <div class="header-cart">
          <a class="header-cart-item"><i class="bi bi-handbag-fill"></i></a>
          <span class="header-cart-badge">2</span>
        </div>
        <div class="header-auth"><a href="#">Đăng nhập</a> <span class="separate"></span> <a href="#">Đăng ký</a></div>
        <!-- Tài khoản -->
         <!-- <div class="nav-item dropdown header-user">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('img/LOGO_Admin.png') }}" alt="avatar" class="header-user-avt"> Admin
          </a>
          <ul class="dropdown-menu header-user-menu" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="/">Tài khoản của tôi</a></li>
            <li><a class="dropdown-item" href="/">Đơn mua</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/logout">Đăng xuất</a></li>
          </ul>
        </div> -->
      </div>
    </div>
    <!-- Header bottom -->
    <nav class="under-nav grid">
      <div class="under-nav-left">
        <div class="dropdown ">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
            <i class="fa-solid fa-bars"></i> Danh mục sản phẩm
          </button>
          <ul class="dropdown-menu">
            <li><a href="/laptops" class="dropdown-item">Laptops</a></li>
            <li><a href="/smartwatches" class="dropdown-item">Đồng hồ</a></li>
            <li><a href="/headphones" class="dropdown-item">Tai nghe</a></li>
            <li><a href="/speakers" class="dropdown-item">Loa</a></li>
            <li><a href="/webcams" class="dropdown-item">Webcam</a></li>
            <li><a href="/mice" class="dropdown-item">Chuột máy tính</a></li>
            <li><a href="/keyboards" class="dropdown-item">Bàn phím</a></li>
            <li><a href="/chargers" class="dropdown-item">Sạc</a></li>
            <li><a href="/hard-drives" class="dropdown-item">Ổ cứng</a></li>
            <li><a href="/network-devices" class="dropdown-item">Thiết bị mạng</a></li>
          </ul>
        </div>
          <a href="/" class="under-nav-item active">Trang chủ</a>
          <a href="/products" class="under-nav-item">Sản phẩm</a>
          <a href="/about" class="under-nav-item">Giới thiệu</a>
          <a href="/blog" class="under-nav-item">Blog</a>
          <a href="/contact" class="under-nav-item">Liên hệ</a>  
      </div>
      <div class="under-nav-right">
            <a href="/compare" class="under-nav-icons-compare"><i class="fa-solid fa-code-compare"></i></a>
            <a href="/wishlist" class="under-nav-icons-tym"><i class="bi bi-heart-fill"></i></a>
      </div>
    </nav>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mOffcanvas" aria-labelledby="mOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="mOffcanvasLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <nav>
      <a href="/" class="off-link">Trang chủ</a>
      <a href="/products" class="off-link">Sản phẩm</a>
      <a href="/about" class="off-link">Giới thiệu</a>
      <a href="/blog" class="off-link">Blog</a>
      <a href="/contact" class="off-link">Liên hệ</a>
      <hr>
      <h6 class="px-2 text-muted">Danh mục sản phẩm</h6>
      <a href="#" class="off-link">Laptops</a>
      <a href="#" class="off-link">Đồng hồ</a>
      <a href="#" class="off-link">Tai nghe</a>
      <a href="#" class="off-link">Loa</a>
      <a href="#" class="off-link">Webcam</a>
      <a href="#" class="off-link">Chuột máy tính</a>
      <a href="#" class="off-link">Bàn phím</a>
    </nav>
  </div>
</div>
