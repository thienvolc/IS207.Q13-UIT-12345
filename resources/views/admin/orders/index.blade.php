@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng - Admin')
@section('page-title', 'Đơn hàng')

@section('content')
  <div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header">
      <div>
        <h1 class="page-title">Quản lý đơn hàng</h1>
        <p class="page-subtitle">Theo dõi và xử lý đơn hàng</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.export.orders') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fa fa-download me-1"></i> Xuất Excel
        </a>
      </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
      <div class="col-6 col-xl-3">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-card-label">Tổng đơn</div>
              <div class="kpi-card-value">{{ $totalOrders ?? 0 }}</div>
            </div>
            <div class="kpi-card-icon primary">
              <i class="fa fa-shopping-bag"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-card-label">Chờ xử lý</div>
              <div class="kpi-card-value text-warning">{{ $pendingOrders ?? 0 }}</div>
            </div>
            <div class="kpi-card-icon primary">
              <i class="fa fa-clock"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-card-label">Hoàn thành</div>
              <div class="kpi-card-value text-success">{{ $completedOrders ?? 0 }}</div>
            </div>
            <div class="kpi-card-icon primary">
              <i class="fa fa-check-circle"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="kpi-card">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="kpi-card-label">Đã hủy</div>
              <div class="kpi-card-value text-danger">{{ $cancelledOrders ?? 0 }}</div>
            </div>
            <div class="kpi-card-icon primary">
              <i class="fa fa-times-circle"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
      <div class="card-body py-3">
        <form method="GET" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label small text-muted">Tìm kiếm</label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
              <input type="text" name="q" class="form-control" placeholder="Mã đơn, tên khách..."
                value="{{ request('q') }}">
            </div>
          </div>

          <div class="col-md-2">
            <label class="form-label small text-muted">Trạng thái</label>
            <select class="form-select" name="status">
              <option value="">Tất cả</option>
              <option value="pending" @selected(request('status') == 'pending')>Chờ thanh toán</option>
              <option value="paid" @selected(request('status') == 'paid')>Đã thanh toán</option>
              <option value="processing" @selected(request('status') == 'processing')>Đang xử lý</option>
              <option value="shipped" @selected(request('status') == 'shipped')>Đang giao</option>
              <option value="completed" @selected(request('status') == 'completed')>Hoàn thành</option>
              <option value="refunded" @selected(request('status') == 'refunded')>Hoàn tiền</option>
              <option value="returned" @selected(request('status') == 'returned')>Trả hàng</option>
              <option value="cancelled" @selected(request('status') == 'cancelled')>Đã hủy</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label small text-muted">Từ ngày</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
          </div>

          <div class="col-md-2">
            <label class="form-label small text-muted">Đến ngày</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
          </div>

          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
              <i class="fa fa-filter me-1"></i> Lọc
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
              <i class="fa fa-times"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    {{-- Orders Table --}}
    <div class="card">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width: 80px;">Mã đơn</th>
              <th style="width: 150px;">Khách hàng</th>
              <th style="width: 140px;">Tổng tiền</th>
              <th style="width: 120px;">Ngày đặt</th>
              <th style="width: 130px;">Trạng thái</th>
              <th style="width: 100px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($orders ?? [] as $order)
              @php
                $orderId = $order->orderId;
                $status = $order->status ?? 1;
                $statusLabels = [
                  1 => ['text' => 'Chờ thanh toán', 'class' => 'badge badge-status pending'],
                  2 => ['text' => 'Đã thanh toán', 'class' => 'badge badge-status processing'],
                  3 => ['text' => 'Đang xử lý', 'class' => 'badge badge-status processing'],
                  4 => ['text' => 'Đang giao', 'class' => 'badge badge-status processing'],
                  5 => ['text' => 'Đã giao', 'class' => 'badge badge-status completed'],
                  6 => ['text' => 'Hoàn tiền', 'class' => 'badge badge-status secondary'],
                  7 => ['text' => 'Trả hàng', 'class' => 'badge badge-status secondary'],
                  8 => ['text' => 'Đã hủy', 'class' => 'badge badge-status cancelled'],
                ];
                $statusInfo = $statusLabels[$status] ?? ['text' => 'Không xác định', 'class' => 'badge badge-status pending'];
                $total = $order->total ?? 0;
                $createdAt = $order->createdAt ?? null;
              @endphp
              <tr>
                <td>
                  <a href="{{ route('admin.orders.show', $orderId) }}" class="fw-bold text-decoration-none">
                    #{{ $orderId }}
                  </a>
                </td>
                <td>
                  <div>
                    <a href="{{ route('admin.customers.show', $order->userId) }}"
                      class="fw-medium text-decoration-none text-primary">
                      Khách #{{ $order->userId }}
                    </a>
                  </div>
                </td>
                <td class="fw-bold text-success">
                  {{ number_format($total, 0, ',', '.') }} ₫
                </td>
                <td>
                  <small>{{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i') : '-' }}</small>
                </td>
                <td>
                  <span class="{{ $statusInfo['class'] }}">{{ $statusInfo['text'] }}</span>
                </td>
                <td>
                  <a href="{{ route('admin.orders.show', $orderId) }}" class="btn btn-sm btn-outline-primary"
                    title="Xem chi tiết">
                    <i class="fa fa-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5">
                  <div class="text-muted">
                    <i class="fa fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                    <p class="mb-0">Chưa có đơn hàng nào</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="text-muted small">
          Hiển thị {{ count($orders ?? []) }} / {{ $pagination['totalItems'] ?? 0 }} đơn hàng
        </div>
        <div>
          @if(isset($pagination) && ($pagination['total'] ?? 0) > 1)
            <nav>
              <ul class="pagination pagination-sm mb-0">
                @php
                  $currentPage = $pagination['current'] ?? 1;
                  $totalPages = $pagination['total'] ?? 1;
                @endphp
                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">‹</a>
                </li>
                @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                  <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                  </li>
                @endfor
                <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">›</a>
                </li>
              </ul>
            </nav>
          @endif
        </div>
      </div>
    </div>

  </div>
@endsection