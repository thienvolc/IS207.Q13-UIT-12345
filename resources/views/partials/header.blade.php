<header class="header">
  <nav class="mbar d-lg-none">
    <button class="mbar__btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mOffcanvas">
      <i class="fa-solid fa-bars"></i>
    </button>
    <a href="/" class="mbar__brand">
      <img src="{{ asset('img/logo.svg') }}" alt="PinkCapy" class="mbar__brand-img">
    </a>
    <div class="mbar__actions">
      <button class="mbar__icon" type="button" data-bs-toggle="collapse" data-bs-target="#mSearchCollapse">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
      <a href="/account" class="mbar__icon"><i class="fa-regular fa-user"></i></a>
      <a href="{{ route('cart.page') }}" class="mbar__icon position-relative">
        <i class="bi bi-handbag-fill"></i>
        <span class="mbar__badge">2</span>
      </a>
    </div>
  </nav>

  <!-- search collapse mobile -->
  <div class="collapse d-lg-none" id="mSearchCollapse">
    <form action="{{ route('products.index') }}" method="GET" class="msearch">
      <input class="msearch__input" type="text" name="search" placeholder="Bạn muốn tìm gì hôm nay?" value="{{ request('search') }}">
      <button type="submit" class="msearch__btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
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
      <img src="{{ asset('img/logo.svg') }}" alt="PinkCapy" class="logo-img">
    </a>
    <form action="{{ route('products.index') }}" method="GET" class="header-search">
      <input type="text" name="search" class="header-search-input" placeholder="Bạn muốn tìm gì hôm nay?" value="{{ request('search') }}">
      <div class="header-search-category dropdown">
        <button class="btn dropdown-toggle search-cat-btn" type="button"
          data-bs-toggle="dropdown" aria-expanded="false">
          Danh mục
        </button>
        <ul class="dropdown-menu header-search-category-menu">
          <li><a class="dropdown-item" href="{{ route('products.index') }}">Tất cả</a></li>
          @foreach($globalCategories ?? [] as $cat)
          <li><a class="dropdown-item" href="{{ route('products.index') }}?category={{ $cat->slug }}">{{ $cat->title }}</a></li>
          @endforeach
        </ul>
      </div>
      <button type="submit" class="header-search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <div class="header-options">
      <div class="header-cart">
        <a href="{{ route('cart.page') }}" class="header-cart-item">
          <span class="header-cart-text">Giỏ hàng</span>
          <i class="bi bi-handbag-fill"></i>
        </a>
      </div>
      <!-- <div class="header-auth"><a href="/login">Đăng nhập</a> <span class="separate"></span> <a href="/register">Đăng ký</a></div> -->
      <!-- Tài khoản -->
      <div class="nav-item dropdown header-user">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
          data-bs-toggle="dropdown" aria-expanded="false">
          <img src="{{ asset('img/LOGO_Admin.png') }}" alt="avatar" class="header-user-avt"> Admin
        </a>
        <ul class="dropdown-menu header-user-menu" aria-labelledby="userDropdown">
          <li><a class="dropdown-item" href="{{ route('account.profile') }}">Tài khoản của tôi</a></li>
          <li><a class="dropdown-item" href="{{ route('cart.page') }}">Đơn mua</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item" href="/logout">Đăng xuất</a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Header bottom -->
  <nav class="under-nav grid">
    <div class="under-nav-left">
      <div class="dropdown">
        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
          <i class="fa-solid fa-bars"></i> Danh mục sản phẩm
        </button>
        <ul class="dropdown-menu">
          @foreach($globalCategories ?? [] as $cat)
          <li><a href="{{ route('products.index') }}?category={{ $cat->slug }}" class="dropdown-item">{{ $cat->title }}</a></li>
          @endforeach
        </ul>
      </div>

      <a href="/" class="under-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
      <a href="/san-pham" class="under-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">Sản phẩm</a>
      <a href="/khuyen-mai" class="under-nav-item {{ request()->routeIs('super-deal') ? 'active' : '' }}">Khuyến mãi</a>
      <a href="/gioi-thieu" class="under-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">Giới thiệu</a>
      <a href="{{ route('blog.index') }}" class="under-nav-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">Tin tức</a>
      <a href="/lien-he" class="under-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">Liên hệ</a>
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
      <a href="{{ route('home') }}" class="off-link">Trang chủ</a>
      <a href="{{ route('products.index') }}" class="off-link">Sản phẩm</a>
      <a href="{{ route('super-deal') }}" class="off-link">Khuyến mãi</a>
      <a href="{{ route('about') }}" class="off-link">Giới thiệu</a>
      <a href="{{ route('blog.index') }}" class="off-link">Tin tức</a>
      <a href="{{ route('contact') }}" class="off-link">Liên hệ</a>
      <hr>
      <h6 class="px-2 text-muted">Danh mục sản phẩm</h6>
      @foreach($globalCategories ?? [] as $cat)
      <a href="{{ route('products.index') }}?category={{ $cat->slug }}" class="off-link">{{ $cat->title }}</a>
      @endforeach
    </nav>
  </div>
</div>