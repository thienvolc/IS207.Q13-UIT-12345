@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng #' . $order->orderId)

@php
  $orderId = $order->orderId;
  $subtotal = $order->subtotal ?? 0;
  $tax = $order->tax ?? 0;
  $shipping = $order->shipping ?? 0;
  $discountTotal = $order->discount_total ?? $order->discount ?? 0;
  $grandTotal = $order->grand_total ?? $order->total ?? 0;
  $status = $order->status;
  $createdAt = $order->createdAt ?? null;
  $items = $order->items ?? [];

  // Customer info
  $firstName = $order->firstName ?? '';
  $lastName = $order->lastName ?? '';
  $phone = $order->phone ?? '';
  $email = $order->email ?? '';
  $address = implode(', ', array_filter([$order->line1 ?? '', $order->line2 ?? '', $order->city ?? '', $order->province ?? '']));

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

@section('content')
  <div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header">
      <div>
        <h1 class="page-title">Đơn hàng #{{ $orderId }}</h1>
        <p class="page-subtitle">
          <span class="{{ $statusInfo['class'] }}">{{ $statusInfo['text'] }}</span>
          @if($createdAt)
            <span class="text-muted ms-2">• Đặt lúc {{ \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i') }}</span>
          @endif
        </p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.transactions.index', ['order_id' => $orderId]) }}"
          class="btn btn-outline-success btn-sm">
          <i class="fa fa-credit-card me-1"></i> Giao dịch
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fa fa-arrow-left me-1"></i> Quay lại
        </a>
      </div>
    </div>

    {{-- Customer & Order Info Summary --}}
    <div class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="mb-3">
              <small class="text-muted d-block">Khách hàng</small>
              <div class="fw-medium">
                <a href="{{ route('admin.customers.show', $order->userId) }}" class="text-decoration-none text-primary">
                  {{ trim("$firstName $lastName") ?: 'Khách #' . $order->userId }}
                </a>
              </div>
            </div>
            <div class="mb-3">
              <small class="text-muted d-block">Điện thoại</small>
              <div class="fw-medium">{{ $phone ?: '-' }}</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <small class="text-muted d-block">Email</small>
              <div class="fw-medium">{{ $email ?: '-' }}</div>
            </div>
            <div class="mb-3">
              <small class="text-muted d-block">Địa chỉ giao hàng</small>
              <div class="fw-medium">{{ $address ?: '-' }}</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <small class="text-muted d-block">Ngày đặt hàng</small>
              <div class="fw-medium">{{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i') : '-' }}</div>
            </div>
            <div class="mb-3">
              <small class="text-muted d-block">Mã đơn hàng</small>
              <div class="fw-medium">#{{ $orderId }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Order Lines --}}
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fa fa-box me-2 text-primary"></i> Sản phẩm trong đơn ({{ count($items) }})</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Sản phẩm</th>
                <th style="width: 100px;" class="text-center">Số lượng</th>
                <th style="width: 150px;" class="text-end">Đơn giá</th>
                <th style="width: 120px;" class="text-end">Giảm giá</th>
                <th style="width: 150px;" class="text-end">Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              @forelse($items as $item)
                @php
                  $productName = $item->product?->title ?? 'Sản phẩm #' . $item->productId;
                  $productSku = $item->product?->sku ?? '';
                  $price = $item->price;
                  $quantity = $item->quantity;
                  $discount = $item->discount ?? 0;
                  $discountAmount = $price * $quantity * $discount / 100;
                  $lineTotal = $price * $quantity - $discountAmount;
                @endphp
                <tr>
                  <td>
                    <div class="d-flex align-items-start gap-2">
                      <span class="badge bg-secondary">#{{ $item->productId }}</span>
                      <div>
                        @if($item->product)
                          <a href="{{ route('admin.products.edit', $item->productId) }}"
                            class="fw-medium text-decoration-none text-primary">
                            {{ $item->product->title }}
                          </a>

                        @else
                          <span class="fw-medium text-muted">Sản phẩm đã bị xóa</span>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="text-center">{{ $quantity }}</td>
                  <td class="text-end">{{ number_format($price) }} ₫</td>
                  <td class="text-end text-danger">
                    @if($discount > 0)
                      -{{ number_format($discountAmount) }} ₫
                      <div class="small text-muted">({{ $discount }}%)</div>
                    @else
                      -
                    @endif
                  </td>
                  <td class="text-end fw-bold">{{ number_format($lineTotal) }} ₫</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted">
                    Không có sản phẩm nào
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Order Totals Footer --}}
      <div class="card-footer bg-white">
        <div class="row justify-content-end">
          <div class="col-md-4">
            <table class="table table-borderless table-sm mb-0">
              <tbody>
                <tr>
                  <td class="text-end text-muted">Tạm tính:</td>
                  <td class="text-end fw-medium" style="width: 150px;">{{ number_format($subtotal) }} ₫</td>
                </tr>
                @if($shipping > 0)
                  <tr>
                    <td class="text-end text-muted">Phí vận chuyển:</td>
                    <td class="text-end fw-medium">{{ number_format($shipping) }} ₫</td>
                  </tr>
                @endif
                @if($tax > 0)
                  <tr>
                    <td class="text-end text-muted">Thuế:</td>
                    <td class="text-end fw-medium">{{ number_format($tax) }} ₫</td>
                  </tr>
                @endif
                @if($discountTotal > 0)
                  <tr>
                    <td class="text-end text-success">Giảm giá:</td>
                    <td class="text-end text-success fw-medium">-{{ number_format($discountTotal) }} ₫</td>
                  </tr>
                @endif
                <tr class="border-top">
                  <td class="text-end fw-bold fs-5">Tổng cộng:</td>
                  <td class="text-end fw-bold fs-5 text-danger">{{ number_format($grandTotal) }} ₫</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Status Update Section --}}
    <div class="card">
      <div class="card-header">
        <i class="fa fa-edit me-2 text-primary"></i> Cập nhật trạng thái
      </div>
      <div class="card-body">
        <form action="{{ route('admin.orders.update', $orderId) }}" method="POST" class="row g-3 align-items-end">
          @csrf
          @method('PUT')

          <div class="col-md-4">
            <label class="form-label small text-muted">Trạng thái mới</label>
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

          <div class="col-md-5">
            <label class="form-label small text-muted">Ghi chú</label>
            <input type="text" class="form-control" name="note" placeholder="Ghi chú nội bộ...">
          </div>

          <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
              <i class="fa fa-save me-1"></i> Cập nhật
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection