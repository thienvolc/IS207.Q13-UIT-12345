@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_id)

@section('content')
    <div class="profile-page-container">
        <div class="container py-5">
            <div class="row">
                <!-- Sidebar Menu -->
                <div class="col-lg-3 mb-4 mb-lg-0">
                    @include('pages.account.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="profile-content-card">
                        <div class="profile-card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h3>Chi tiết đơn hàng #{{ $order->order_id }}</h3>
                                <p class="text-muted mb-0">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>

                        <div class="profile-card-body">
                            <!-- Order Status -->
                            @php
                                $statusTexts = [
                                    1 => 'Chờ thanh toán',
                                    2 => 'Đã thanh toán', 
                                    3 => 'Đang xử lý',
                                    4 => 'Đang giao',
                                    5 => 'Đã giao',
                                    6 => 'Hoàn tiền',
                                    7 => 'Trả hàng',
                                    8 => 'Đã hủy'
                                ];
                                $statusClasses = [
                                    1 => 'bg-warning text-dark',
                                    2 => 'bg-info text-white',
                                    3 => 'bg-primary',
                                    4 => 'bg-info',
                                    5 => 'bg-success',
                                    6 => 'bg-secondary',
                                    7 => 'bg-secondary',
                                    8 => 'bg-danger'
                                ];
                                $status = $order->status;
                            @endphp
                            
                            <div class="alert alert-light border mb-4">
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $statusClasses[$status] ?? 'bg-secondary' }} fs-6 me-2">
                                        {{ $statusTexts[$status] ?? 'Không xác định' }}
                                    </span>
                                    <span class="text-muted">
                                        Trạng thái đơn hàng
                                    </span>
                                </div>
                            </div>

                            <!-- Payment & Shipping Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Thông tin giao hàng</h5>
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body">
                                            <p class="mb-1"><strong>Người nhận:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                                            <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                                            <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                                            <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->line1 }}, {{ $order->line2 ? $order->line2 . ', ' : '' }}{{ $order->city }}, {{ $order->province }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Thanh toán</h5>
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body">
                                            <p class="mb-1"><strong>Phương thức:</strong> {{ $order->payment_method ?? 'COD' }}</p>
                                            <p class="mb-1"><strong>Trạng thái thanh toán:</strong> 
                                                @if($status == 2 || $status >= 5)
                                                    <span class="text-success fw-bold">Đã thanh toán</span>
                                                @else
                                                    <span class="text-warning fw-bold">Chưa thanh toán</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <h5 class="mb-3 mt-5 pt-3">Sản phẩm</h5>
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-center">Đơn giá</th>
                                                    <th class="text-center">SL</th>
                                                    <th class="text-end">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->items as $item)
                                                    @php
                                                        $thumb = $item->product?->thumb;
                                                        if ($thumb && !str_starts_with($thumb, 'http')) {
                                                            $thumb = 'https://broad-snowflake-e396.ttt2042005.workers.dev/proxy?img=' . $thumb;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $thumb ?? asset('img/default-product.png') }}"
                                                                    alt="{{ $item->product?->title }}"
                                                                    class="me-3 rounded"
                                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                                <div>
                                                                    <div class="fw-medium text-dark" style="line-height: 1.6; padding-top: 10px; padding-bottom: 2px;">{{ $item->product?->title }}</div>
                                                                    @if($item->product && $item->product->slug)
                                                                        <a href="{{ route('products.show', $item->product->slug) }}" class="small text-decoration-none text-primary">Xem sản phẩm</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                                        <td class="text-center">x{{ $item->quantity }}</td>
                                                        <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals -->
                            <div class="row justify-content-end">
                                <div class="col-md-5">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-end text-muted">Tạm tính:</td>
                                            <td class="text-end fw-medium">{{ number_format($order->subtotal ?? 0, 0, ',', '.') }}đ</td>
                                        </tr>
                                        @if($order->shipping > 0)
                                        <tr>
                                            <td class="text-end text-muted">Phí vận chuyển:</td>
                                            <td class="text-end fw-medium">{{ number_format($order->shipping, 0, ',', '.') }}đ</td>
                                        </tr>
                                        @endif
                                        @if($order->discount_total > 0)
                                        <tr>
                                            <td class="text-end text-success">Giảm giá:</td>
                                            <td class="text-end text-success fw-medium">-{{ number_format($order->discount_total, 0, ',', '.') }}đ</td>
                                        </tr>
                                        @endif
                                        <tr class="border-top">
                                            <td class="text-end fw-bold fs-5">Tổng cộng:</td>
                                            <td class="text-end fw-bold fs-5 text-danger">{{ number_format($order->grand_total, 0, ',', '.') }}đ</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 text-end">
                                @if($order->status == 1) <!-- Pending Payment -->
                                    @if($order->payment_method !== 'cod')
                                        <a href="{{ route('account.orders.repay', $order->order_id) }}" class="btn btn-primary btn-lg">Thanh toán ngay</a>
                                    @endif
                                     <a href="#" class="btn btn-outline-danger ms-2" onclick="return confirm('Bạn có chắc muốn hủy?')">Hủy đơn hàng</a>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
    @endpush
@endsection
