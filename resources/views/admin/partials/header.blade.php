<header class="admin-header" role="banner">
  <div class="header-container">

    {{-- Left Side: Brand + Mobile Toggle --}}
    <div class="header-left">
      {{-- Mobile Menu Toggle --}}
      <button id="btn-sidebar-toggle" class="btn btn-ghost d-lg-none" aria-label="Toggle menu">
        <i class="fa fa-bars"></i>
      </button>

      {{-- Brand/Logo --}}
      <a href="{{ route('admin.dashboard') }}" class="header-brand">
        <div class="header-brand-logo">
          <i class="fa fa-bolt"></i>
        </div>
        <span class="header-brand-text">Pink<span>Capy</span></span>
      </a>

      {{-- Breadcrumb --}}
      <nav class="header-breadcrumb d-none d-md-flex">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fa fa-home"></i>
        </a>
        <span class="separator">/</span>
        <span class="current">@yield('page-title', 'Dashboard')</span>
      </nav>
    </div>

    {{-- Right Side: Search + Actions + User --}}
    <div class="header-right">
      {{-- Search --}}
      <form class="header-search d-none d-md-block" role="search" action="{{ route('admin.products.index') }}">
        <input type="search" name="q" placeholder="Tìm kiếm..." aria-label="Search">
        <span class="search-shortcut">Ctrl+K</span>
      </form>

      {{-- Notifications --}}
      <button class="header-notification" aria-label="Notifications">
        <i class="fa fa-bell"></i>
        @if(isset($notificationCount) && $notificationCount > 0)
          <span class="badge-dot"></span>
        @endif
      </button>

      {{-- View Website --}}
      <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm d-none d-lg-flex align-items-center gap-2"
        target="_blank">
        <i class="fa fa-external-link-alt"></i>
        <span>Website</span>
      </a>

      {{-- User Dropdown --}}
      <div class="dropdown">
        <button class="header-user dropdown-toggle" type="button" id="adminUserDropdown" data-bs-toggle="dropdown"
          aria-expanded="false">
          <img src="{{ asset('img/LOGO_Admin.png') }}" alt="Admin"
            onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=714B67&color=fff&size=32'">
          <span class="header-user-name d-none d-sm-inline">Admin</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="adminUserDropdown">
          <li>
            <a class="dropdown-item" href="#">
              <i class="fa fa-user"></i> Hồ sơ cá nhân
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="fa fa-lock"></i> Đổi mật khẩu
            </a>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="fa fa-sign-out-alt"></i> Đăng xuất
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>

  </div>
</header>