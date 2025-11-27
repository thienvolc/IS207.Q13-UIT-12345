<header class="admin-header-gradient" role="banner">
  <div class="container-fluid d-flex align-items-center justify-content-between py-2">

    {{-- Left --}}
    <div class="d-flex align-items-center gap-3">
      <button id="btn-sidebar-toggle" class="btn btn-icon-white d-md-none" aria-label="Mở/Đóng menu">
        <i class="fa fa-bars"></i>
      </button>

      <div class="d-flex flex-column text-white">
        <span class="small opacity-75">PinkCapy</span>
        <h5 class="mb-0 fw-bold text-shadow">@yield('page-title','Dashboard')</h5>
      </div>
    </div>

    {{-- Right --}}
    <div class="d-flex align-items-center gap-3">

      {{-- Search --}}
      <form class="d-none d-md-flex" role="search" action="{{ route('admin.products.index') }}">
        <div class="input-group input-group-sm search-admin">
          <input name="q" class="form-control" type="search" placeholder="Tìm kiếm..."
                 aria-label="Tìm kiếm">
          <button class="btn btn-search-admin" type="submit"><i class="fa fa-search"></i></button>
        </div>
      </form>

      {{-- View site --}}
      <a class="btn btn-outline-light btn-sm rounded-pill d-none d-md-inline"
         href="{{ url('/') }}" target="_blank">
        <i class="fa fa-arrow-up-right-from-square me-1"></i> Website
      </a>

      {{-- User dropdown --}}
      <div class="dropdown">
        <button class="btn btn-user-admin dropdown-toggle d-flex align-items-center"
                type="button" id="adminUserMenu" data-bs-toggle="dropdown">
          <img src="{{ asset('img/LOGO_Admin.png') }}"
               class="rounded-circle me-2" width="34" height="34">
          <span class="text-white d-none d-sm-inline">Admin</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow">
          <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i> Hồ sơ</a></li>
          <li><a class="dropdown-item" href="#"><i class="fa fa-lock me-2"></i> Đổi mật khẩu</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('logout') }}" method="POST" class="m-0 px-3">
              @csrf
              <button type="submit" class="btn btn-link dropdown-item text-danger">
                Đăng xuất
              </button>
            </form>
          </li>
        </ul>
      </div>

    </div>

  </div>
</header>
