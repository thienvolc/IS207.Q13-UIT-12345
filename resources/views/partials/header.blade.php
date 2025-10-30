<header class="header">
  <nav class="mbar d-lg-none">
    <button
      class="mbar__btn"
      type="button"
      data-bs-toggle="offcanvas"
      data-bs-target="#mOffcanvas"
    >
      <i class="fa-solid fa-bars"></i>
    </button>
    <a href="/" class="mbar__brand">PinkCapy<b>.</b></a>
    <div class="mbar__actions">
      <button
        class="mbar__icon"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mSearchCollapse"
      >
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
      <a href="/account" class="mbar__icon">
        <i class="fa-regular fa-user"></i>
      </a>
      <a href="/cart" class="mbar__icon position-relative">
        <i class="bi bi-handbag-fill"></i>
        <span class="mbar__badge">2</span>
      </a>
    </div>
  </nav>

  <!-- search collapse mobile -->
  <div class="collapse d-lg-none" id="mSearchCollapse">
    <div class="msearch">
      <input
        class="msearch__input"
        type="text"
        placeholder="Bạn muốn tìm gì hôm nay?"
      />
      <button class="msearch__btn">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>
  </div>

  <div class="grid">
    <!-- Header topbar -->
    <div class="header-topbar">
      <ul class="header-topbar-list">
        <li class="header-topbar-item">
          <a href="/">Chào mừng bạn đến với PinkCapy Store</a>
        </li>
      </ul>
      <ul class="header-topbar-list">
        <li class="header-topbar-item">
          <i class="fa-solid fa-location-dot"></i>Thành phố Hồ Chí Minh
        </li>
        <li class="header-topbar-item">
          <i class="fa-solid fa-truck-fast"></i> Giao hàng nhanh chóng
        </li>
        <li class="header-topbar-item">
          <i class="fa-solid fa-language"></i>Ngôn ngữ
          <ul class="header-topbar-submenu">
            <li class="header-topbar-submenu-item">
              <a href="/en">Tiếng Anh</a>
            </li>
            <li class="header-topbar-submenu-item">
              <a href="/vi">Tiếng Việt</a>
            </li>
          </ul>
        </li>

        <!-- Tài khoản -->
        <li class="nav-item dropdown header-topbar-item">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="userDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <img
              src="{{ asset('img/LOGO_Admin.png') }}"
              alt="avatar"
              class="user-avt"
            />
            Admin
          </a>
          <ul
            class="dropdown-menu header-topbar-dropdown-menu"
            aria-labelledby="userDropdown"
          >
            <li><a class="dropdown-item" href="/">Tài khoản của tôi</a></li>
            <li><a class="dropdown-item" href="/">Đơn mua</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="/logout">Đăng xuất</a></li>
          </ul>
        </li>
      </ul>
    </div>

    <!-- Header body -->
    <div class="header-row">
      <a class="logo" href="/">
        <img
          src="{{ asset('img/logo.svg') }}"
          alt="PinkCapy"
          class="header-logo"
        />
      </a>

      <div class="header-search">
        <input
          type="text"
          class="header-search-input"
          placeholder="Bạn muốn tìm gì hôm nay?"
        />
        <div class="header-search-category dropdown">
          <button
            class="btn dropdown-toggle search-cat-btn"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            Danh mục
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Tai nghe</a></li>
            <li><a class="dropdown-item" href="#">Đồng hồ</a></li>
            <li><a class="dropdown-item" href="#">Phụ kiện</a></li>
          </ul>
        </div>
        <button type="submit" class="header-search-button">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
      </div>

      <div class="header-cart">
        <a class="header-cart-item">
          <i class="bi bi-handbag-fill"></i>
        </a>
        <span class="header-cart-badge">2</span>
      </div>
    </div>

    <!-- Header bottom -->
    <nav class="under-nav">
      <div class="under-nav-left">
        <div class="dropdown">
          <button
            class="btn dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            data-bs-display="static"
            aria-expanded="false"
          >
            <i class="fa-solid fa-bars"></i> Danh mục sản phẩm
          </button>
          <ul class="dropdown-menu">
            <li><a href="" class="dropdown-item">Đồng hồ</a></li>
            <li><a href="" class="dropdown-item">Tai nghe</a></li>
            <li><a href="" class="dropdown-item">Ốp lưng điện thoại</a></li>
            <li><a href="" class="dropdown-item">Sạc dự phòng</a></li>
            <li><a href="" class="dropdown-item">Kính cường lực</a></li>
            <li><a href="" class="dropdown-item">Chuột máy tính</a></li>
            <li><a href="" class="dropdown-item">Bàn phím</a></li>
            <li><a href="" class="dropdown-item">Ổ cứng</a></li>
            <li><a href="" class="dropdown-item">Túi chống sốc</a></li>
            <li><a href="" class="dropdown-item">Thiết bị mạng</a></li>
          </ul>
        </div>

        <a href="/super-deal" class="under-nav-item">Super Deals</a>
        <a href="/products" class="under-nav-item">Sản phẩm</a>
        <a href="/about" class="under-nav-item">Giới thiệu</a>
        <a href="/blog" class="under-nav-item">Blog</a>
        <a href="/contact" class="under-nav-item">Liên hệ</a>
      </div>

      <div class="under-nav-right">
        <a href="/" class="under-nav-icons-compare">
          <i class="fa-solid fa-code-compare"></i>
        </a>
        <a href="/" class="under-nav-icons-tym">
          <i class="bi bi-heart-fill"></i>
        </a>
      </div>
    </nav>
  </div>
</header>

<!-- Mobile Offcanvas Menu -->
<div
  class="offcanvas offcanvas-start"
  tabindex="-1"
  id="mOffcanvas"
  aria-labelledby="mOffcanvasLabel"
>
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="mOffcanvasLabel">Menu</h5>
    <button
      type="button"
      class="btn-close"
      data-bs-dismiss="offcanvas"
      aria-label="Close"
    ></button>
  </div>

  <div class="offcanvas-body">
    <nav>
      <div class="offcanvas-body">
        <nav>@include('partials.menu')</nav>
      </div>
    </nav>
  </div>
</div>
