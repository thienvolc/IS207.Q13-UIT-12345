<aside id="admin-sidebar" class="admin-sidebar" role="navigation" aria-label="Main admin navigation">
  <!-- <div class="sidebar-top p-3 d-flex align-items-center justify-content-between">
    <a href="{{ route('admin.dashboard') }}" class="brand d-flex align-items-center text-decoration-none">
      <span class="brand-mark me-2">PC</span>
      <span class="brand-text">PinkCapy Admin</span>
    </a>

    <button id="btn-sidebar-collapse" class="btn btn-icon d-md-none" aria-label="Đóng menu">
      <i class="fa fa-times"></i>
    </button>
  </div> -->

  <nav class="nav flex-column py-2 px-1" aria-label="Admin menu">
    <a href="{{ route('admin.dashboard') }}" class="nav-link px-3 d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <i class="fa fa-tachometer-alt me-2"></i> <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.products.index') }}" class="nav-link px-3 d-flex align-items-center {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
      <i class="fa fa-box me-2"></i> <span>Sản phẩm</span>
    </a>

    <a href="{{ route('admin.orders.index') }}" class="nav-link px-3 d-flex align-items-center {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
      <i class="fa fa-file-invoice-dollar me-2"></i> <span>Đơn hàng</span>
    </a>

    <a href="{{ route('admin.customers.index') }}" class="nav-link px-3 d-flex align-items-center {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
      <i class="fa fa-users me-2"></i> <span>Khách hàng</span>
    </a>

    <a href="{{ route('admin.posts.index') }}" class="nav-link px-3 d-flex align-items-center {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
      <i class="fa fa-newspaper me-2"></i> <span>Bài viết</span>
    </a>

    <div class="mt-3 px-2">
      <hr class="my-2" />
      <a href="{{ url('/') }}" class="nav-link px-3 small text-muted"><i class="fa fa-home me-2"></i> Về trang chính</a>
    </div>
  </nav>

  
</aside>
