<aside id="admin-sidebar" class="admin-sidebar" role="navigation" aria-label="Main admin navigation">

  {{-- Navigation --}}
  <nav class="sidebar-nav">
    <div class="sidebar-nav-section">
      <div class="sidebar-nav-label">Menu chính</div>

      <a href="{{ route('admin.dashboard') }}"
        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fa fa-chart-pie"></i>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('admin.products.index') }}"
        class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <i class="fa fa-box"></i>
        <span>Sản phẩm</span>
        @if(isset($productCount) && $productCount > 0)
          <span class="badge bg-primary">{{ $productCount }}</span>
        @endif
      </a>

      <a href="{{ route('admin.inventory.index') }}"
        class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
        <i class="fa fa-warehouse"></i>
        <span>Tồn kho</span>
      </a>

      <a href="{{ route('admin.orders.index') }}"
        class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <i class="fa fa-shopping-cart"></i>
        <span>Đơn hàng</span>
        @if(isset($pendingOrders) && $pendingOrders > 0)
          <span class="badge bg-warning text-dark">{{ $pendingOrders }}</span>
        @endif
      </a>

      <a href="{{ route('admin.customers.index') }}"
        class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <i class="fa fa-users"></i>
        <span>Khách hàng</span>
      </a>

      <a href="{{ route('admin.transactions.index') }}"
        class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
        <i class="fa fa-money-bill-wave"></i>
        <span>Giao dịch</span>
      </a>

      <a href="{{ route('admin.reports.index') }}"
        class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="fa fa-chart-line"></i>
        <span>Báo cáo</span>
      </a>
    </div>

    <div class="sidebar-nav-section">
      <div class="sidebar-nav-label">Nội dung</div>

      <a href="{{ route('admin.blogs.index') }}"
        class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
        <i class="fa fa-newspaper"></i>
        <span>Bài viết</span>
      </a>

      <a href="{{ route('admin.categories.index') }}"
        class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="fa fa-folder"></i>
        <span>Danh mục</span>
      </a>
    </div>

    <div class="sidebar-nav-section">
      <div class="sidebar-nav-label">Hệ thống</div>

      <a href="{{ route('admin.settings.index') }}"
        class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="fa fa-cog"></i>
        <span>Cài đặt</span>
      </a>

    </div>
  </nav>

</aside>

{{-- Mobile Overlay --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>