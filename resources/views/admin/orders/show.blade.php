@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng #' . $order->orderId)

@php
  $orderId = $order->orderId;
  $total = $order->total;
  $status = $order->status;
  $shipping = $order->shipping ?? 0;
  $createdAt = $order->createdAt ?? null;
  $items = $order->items ?? [];
@endphp

@section('content')
  <div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header">
      <div>
        <h1 class="page-title">Đơn hàng #{{ $orderId }}</h1>
        <p class="page-subtitle">
          @if($createdAt)
            Đặt lúc {{ \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i') }}
          @endif
        </p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fa fa-arrow-left me-1"></i> Quay lại
        </a>
      </div>
    </div>

    <div class="row g-4">
      {{-- Order Summary --}}
      <div class="col-lg-8">
        {{-- Products list --}}
        <div class="card mb-4">
          <div class="card-header">
            <i class="fa fa-box me-2 text-primary"></i> Sản phẩm trong đơn ({{ count($items) }})
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th style="width: 100px;">Số lượng</th>
                    <th style="width: 130px;">Đơn giá</th>
                    <th style="width: 130px;">Thành tiền</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($items as $item)
                    @php
                      $productName = $item->product?->title ?? 'Sản phẩm #' . $item->productId;
                      $price = $item->price;
                      $quantity = $item->quantity;
                      $discount = $item->discount ?? 0;
                      $lineTotal = $price * $quantity * (1 - $discount / 100);
                    @endphp
                    <tr>
                      <td>
                        <div class="fw-medium">{{ $productName }}</div>
                        @if($discount > 0)
                          <small class="text-muted">Giảm {{ $discount }}%</small>
                        @endif
                      </td>
                      <td>{{ $quantity }}</td>
                      <td>{{ number_format($price) }} ₫</td>
                      <td class="fw-bold text-danger">{{ number_format($lineTotal) }} ₫</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center py-4 text-muted">
                        Không có sản phẩm nào
                      </td>
                    </tr>
                  @endforelse
                </tbody>
                <tfoot class="table-light">
                  <tr>
                    <td colspan="3" class="text-end fw-medium">Phí vận chuyển:</td>
                    <td class="fw-medium">{{ number_format($shipping) }} ₫</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                    <td class="fw-bold text-danger fs-5">{{ number_format($total) }} ₫</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Sidebar --}}
      <div class="col-lg-4">
        {{-- Order Status --}}
        <div class="card mb-4">
          <div class="card-header">
            <i class="fa fa-info-circle me-2 text-primary"></i> Trạng thái đơn hàng
          </div>
          <div class="card-body">
            @php
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
            @endphp
            <div class="mb-3">
              <span class="{{ $statusInfo['class'] }} fs-6">
                {{ $statusInfo['text'] }}
              </span>
            </div>

            <form action="{{ route('admin.orders.update', $orderId) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label class="form-label small text-muted">Cập nhật trạng thái</label>
                <select class="form-select" name="status">
                  <option value="1" @selected($status == 1)>Chờ thanh toán</option>
                  <option value="2" @selected($status == 2)>Đã thanh toán</option>
                  <option value="3" @selected($status == 3)>Đang xử lý</option>
                  <option value="4" @selected($status == 4)>Đang giao</option>
                  <option value="5" @selected($status == 5)>Đã giao</option>
                  <option value="6" @selected($status == 6)>Hoàn tiền</option>
                  <option value="7" @selected($status == 7)>Trả hàng</option>
                  <option value="8" @selected($status == 8)>Đã hủy</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Ghi chú</label>
                <textarea class="form-control" name="note" rows="2" placeholder="Ghi chú nội bộ..."></textarea>
              </div>

              <button type="submit" class="btn btn-primary w-100">
                <i class="fa fa-save me-1"></i> Cập nhật
              </button>
            </form>
          </div>
        </div>

        {{-- Order Info --}}
        <div class="card">
          <div class="card-header">
            <i class="fa fa-receipt me-2 text-primary"></i> Thông tin đơn hàng
          </div>
          <div class="card-body">
            <div class="mb-2">
              <small class="text-muted">Mã đơn hàng</small>
              <div class="fw-medium">#{{ $orderId }}</div>
            </div>
            <div class="mb-2">
              <small class="text-muted">Mã khách hàng</small>
              <div class="fw-medium">#{{ $order->userId }}</div>
            </div>
            @if($createdAt)
              <div class="mb-2">
                <small class="text-muted">Ngày đặt</small>
                <div class="fw-medium">{{ \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i') }}</div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection