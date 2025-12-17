@extends('layouts.admin')

@section('title', 'Dashboard - Admin PinkCapy')
@section('page-title', 'Dashboard')

@section('content')
  <div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header">
      <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Tổng quan hoạt động kinh doanh</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.export.revenue-pdf') }}" class="btn btn-outline-secondary btn-sm"
          target="_blank">
          <i class="fa fa-download me-1"></i> Xuất báo cáo
        </a>
        <button class="btn btn-primary btn-sm">
          <i class="fa fa-plus me-1"></i> Thêm sản phẩm
        </button>
      </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
      {{-- Revenue --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card">
          <div class="kpi-card-icon primary">
            <i class="fa fa-dollar-sign"></i>
          </div>
          <div class="kpi-card-label">Doanh thu (30 ngày)</div>
          <div class="kpi-card-value">{{ isset($revenue) ? number_format($revenue, 0, ',', '.') . ' ₫' : '0 ₫' }}</div>
          <div class="kpi-card-trend up">
            <i class="fa fa-arrow-up"></i>
            <span>+12.5% so với tháng trước</span>
          </div>
        </div>
      </div>

      {{-- Orders --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card">
          <div class="kpi-card-icon primary">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <div class="kpi-card-label">Đơn hàng mới</div>
          <div class="kpi-card-value">{{ $newOrders ?? 0 }}</div>
          <div class="kpi-card-trend {{ ($pendingOrders ?? 0) > 0 ? 'neutral' : 'up' }}">
            <i class="fa fa-clock"></i>
            <span>{{ $pendingOrders ?? 0 }} đang chờ xử lý</span>
          </div>
        </div>
      </div>

      {{-- Customers --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card">
          <div class="kpi-card-icon primary">
            <i class="fa fa-users"></i>
          </div>
          <div class="kpi-card-label">Khách hàng</div>
          <div class="kpi-card-value">{{ $customers ?? 0 }}</div>
          <div class="kpi-card-trend up">
            <i class="fa fa-arrow-up"></i>
            <span>+8.1% tháng này</span>
          </div>
        </div>
      </div>

      {{-- Products --}}
      <div class="col-sm-6 col-xl-3">
        <div class="kpi-card">
          <div class="kpi-card-icon primary">
            <i class="fa fa-box"></i>
          </div>
          <div class="kpi-card-label">Tổng sản phẩm</div>
          <div class="kpi-card-value">{{ $totalProducts ?? 0 }}</div>
          <div class="kpi-card-trend neutral">
            <i class="fa fa-equals"></i>
            <span>Ổn định</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Charts & Tables Row --}}
    <div class="row g-3">
      {{-- Revenue Chart --}}
      <div class="col-xl-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fa fa-chart-line me-2 text-primary"></i>Doanh thu & Đơn hàng</span>
            <div class="btn-group btn-group-sm" role="group">
              <button type="button" class="btn btn-outline-secondary active" data-period="7">7 ngày</button>
              <button type="button" class="btn btn-outline-secondary" data-period="30">30 ngày</button>
              <button type="button" class="btn btn-outline-secondary" data-period="90">3 tháng</button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="height: 300px;">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      {{-- Orders by Status --}}
      <div class="col-xl-4">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-chart-pie me-2 text-primary"></i>Trạng thái đơn hàng
          </div>
          <div class="card-body">
            <div class="chart-container" style="height: 300px;">
              <canvas id="orderStatusChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Recent Data Row --}}
    <div class="row g-3 mt-1">
      {{-- Recent Products --}}
      <div class="col-xl-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fa fa-box me-2 text-primary"></i>Sản phẩm gần đây</span>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
              Xem tất cả <i class="fa fa-arrow-right ms-1"></i>
            </a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th style="width: 50px">#</th>
                    <th style="width: 60px">Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th style="width: 100px">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recentProducts ?? [] as $product)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        <img src="{{ $product->thumb ?? '/images/no-image.png' }}" alt="{{ $product->title ?? '' }}"
                          class="rounded" style="width: 48px; height: 48px; object-fit: cover;"
                          onerror="this.src='https://via.placeholder.com/48x48?text=No+Image'">
                      </td>
                      <td>
                        <div class="fw-medium">{{ Str::limit($product->title ?? 'N/A', 40) }}</div>
                        <small class="text-muted">{{ $product->slug ?? '' }}</small>
                      </td>
                      <td class="fw-medium">
                        {{ isset($product->price) ? number_format($product->price, 0, ',', '.') . ' ₫' : '-' }}
                      </td>
                      <td>
                        @php $qty = $product->quantity ?? 0; @endphp
                        <span class="{{ $qty < 10 ? 'text-danger' : 'text-success' }}">
                          {{ $qty }}
                        </span>
                      </td>
                      <td>
                        @if(($product->status ?? 0) == 1)
                          <span class="badge badge-status completed">Hiện</span>
                        @else
                          <span class="badge badge-status cancelled">Ẩn</span>
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('admin.products.edit', $product->productId ?? $product->product_id ?? $product->id) }}"
                          class="btn btn-sm btn-outline-primary">
                          <i class="fa fa-edit"></i>
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                        Chưa có sản phẩm nào
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Recent Orders --}}
      <div class="col-xl-4">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fa fa-shopping-bag me-2 text-primary"></i>Đơn hàng mới</span>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
              Xem tất cả
            </a>
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              @forelse($recentOrders ?? [] as $order)
                <li class="list-group-item d-flex justify-content-between align-items-start py-3">
                  <div>
                    <div class="fw-medium">#{{ $order->orderId ?? $order->order_id ?? $order->id ?? '?' }}</div>
                    <small class="text-muted">{{ $order->customer_name ?? 'Khách vãng lai' }}</small>
                  </div>
                  <div class="text-end">
                    <div class="fw-medium">
                      {{ isset($order->grandTotal) ? number_format($order->grandTotal, 0, ',', '.') : (isset($order->total) ? number_format($order->total, 0, ',', '.') : (isset($order->grand_total) ? number_format($order->grand_total, 0, ',', '.') : '-')) }}
                      ₫
                    </div>
                    @php
                      $status = $order->status ?? 'pending';
                      $statusClass = match ($status) {
                        'pending' => 'pending',
                        'processing' => 'processing',
                        'completed', 'delivered' => 'completed',
                        'cancelled' => 'cancelled',
                        default => 'pending'
                      };
                      $statusText = match ($status) {
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'completed', 'delivered' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                        default => ucfirst($status)
                      };
                    @endphp
                    <span class="badge badge-status {{ $statusClass }}">{{ $statusText }}</span>
                  </div>
                </li>
              @empty
                <li class="list-group-item text-center py-4 text-muted">
                  <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                  Chưa có đơn hàng
                </li>
              @endforelse
            </ul>
          </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card mt-3">
          <div class="card-header">
            <i class="fa fa-bolt me-2 text-warning"></i>Thao tác nhanh
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-2"></i>Thêm sản phẩm mới
              </a>
              <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-list me-2"></i>Quản lý đơn hàng
              </a>
              <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-users me-2"></i>Quản lý khách hàng
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Revenue Chart
      const revenueCtx = document.getElementById('revenueChart');
      if (revenueCtx) {
        new Chart(revenueCtx, {
          type: 'line',
          data: {
            labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
            datasets: [{
              label: 'Doanh thu (triệu ₫)',
              data: [12, 19, 15, 25, 22, 30, 28],
              borderColor: '#0066B3',
              backgroundColor: 'rgba(0, 102, 179, 0.1)',
              fill: true,
              tension: 0.4,
              borderWidth: 2,
              pointBackgroundColor: '#0066B3',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointRadius: 4
            }, {
              label: 'Đơn hàng',
              data: [5, 8, 6, 10, 9, 12, 11],
              borderColor: '#28A745',
              backgroundColor: 'transparent',
              borderWidth: 2,
              tension: 0.4,
              pointBackgroundColor: '#28A745',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointRadius: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  usePointStyle: true,
                  padding: 20
                }
              }
            },
            scales: {
              x: {
                grid: {
                  display: false
                }
              },
              y: {
                beginAtZero: true,
                grid: {
                  color: 'rgba(0,0,0,0.05)'
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index'
            }
          }
        });
      }

      // Order Status Chart
      const statusCtx = document.getElementById('orderStatusChart');
      if (statusCtx) {
        new Chart(statusCtx, {
          type: 'doughnut',
          data: {
            labels: ['Hoàn thành', 'Đang xử lý', 'Chờ xử lý', 'Đã hủy'],
            datasets: [{
              data: [{{ $completedOrders ?? 45 }}, {{ $processingOrders ?? 25 }}, {{ $pendingOrders ?? 20 }}, {{ $cancelledOrders ?? 10 }}],
              backgroundColor: [
                '#28A745',
                '#17A2B8',
                '#FFC107',
                '#DC3545'
              ],
              borderWidth: 0,
              hoverOffset: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  usePointStyle: true,
                  padding: 15
                }
              }
            }
          }
        });
      }
    });
  </script>
@endpush