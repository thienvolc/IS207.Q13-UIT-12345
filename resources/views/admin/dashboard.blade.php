@extends('layouts.admin')

@section('title','Dashboard - Admin')
@section('page-title','Dashboard')

@section('content')
<div class="container-fluid">
  {{-- KPI cards --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <small class="text-muted">Tổng sản phẩm</small>
          <h3 class="mt-2">{{ $totalProducts ?? 0 }}</h3>
          <div class="text-success small">+4.2% so với tuần trước</div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <small class="text-muted">Đơn hàng</small>
          <h3 class="mt-2">{{ $newOrders ?? 0 }}</h3>
          <div class="text-warning small">Chờ xử lý: {{ $pendingOrders ?? 0 }}</div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <small class="text-muted">Khách hàng</small>
          <h3 class="mt-2">{{ $customers ?? 0 }}</h3>
          <div class="text-muted small">Hoạt động trong tháng</div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <small class="text-muted">Doanh thu (30d)</small>
          <h3 class="mt-2">{{ isset($revenue) ? number_format($revenue) . ' ₫' : '—' }}</h3>
          <div class="text-muted small">Ước tính</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Charts + Recent orders --}}
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h6>Doanh thu & truy cập</h6>
          <div id="chart-area" style="height:260px; display:flex; align-items:center; justify-content:center; color:var(--muted)">
            <!-- placeholder, front-end dev can replace by Chart.js -->
            <div class="text-center">Chart placeholder — tích hợp Chart.js / Recharts tại đây</div>
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-body">
          <h6>Danh sách sản phẩm gần đây</h6>
          <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
              <thead class="table-light">
                <tr><th>#</th><th>Ảnh</th><th>Tên</th><th>Giá</th><th>Trạng thái</th><th>Hành động</th></tr>
              </thead>
              <tbody>
                @forelse($recentProducts ?? [] as $p)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="{{ $p->image_url ?? '/images/no-image.png' }}" alt="" style="width:56px;height:40px;object-fit:cover;border-radius:6px"></td>
                    <td>{{ $p->name }}</td>
                    <td>{{ isset($p->price) ? number_format($p->price) . ' ₫' : '-' }}</td>
                    <td>{{ $p->status ? 'Hiện' : 'Ẩn' }}</td>
                    <td>
                      <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center">Không có sản phẩm</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <h6>Đơn hàng gần đây</h6>
          <ul class="list-group list-group-flush">
            @forelse($recentOrders ?? [] as $order)
              <li class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                  <strong>#{{ $order->id ?? '?' }}</strong><br>
                  <small class="text-muted">{{ $order->customer_name ?? 'Khách lạ' }}</small>
                </div>
                <div class="text-end">
                  <div>{{ isset($order->total) ? number_format($order->total) . ' ₫' : '-' }}</div>
                  <small class="badge bg-secondary">{{ $order->status ?? '—' }}</small>
                </div>
              </li>
            @empty
              <li class="list-group-item text-center">Không có đơn hàng</li>
            @endforelse
          </ul>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-body">
          <h6>Nhanh</h6>
          <div class="d-grid gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Thêm sản phẩm</a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Quản lý đơn hàng</a>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">Quản lý khách hàng</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
