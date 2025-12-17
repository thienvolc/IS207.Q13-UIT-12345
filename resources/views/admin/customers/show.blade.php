@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng - Admin')
@section('page-title', 'Chi tiết khách hàng')

@php
    $id = $customer->userId;
    $name = trim(($customer->firstName ?? '') . ' ' . ($customer->lastName ?? '')) ?: 'N/A';
    $email = $customer->email ?? '';
    $phone = $customer->phone ?? '-';
    $status = $customer->status ?? 1;
    $createdAt = $customer->createdAt ? \Illuminate\Support\Carbon::parse($customer->createdAt) : null;
@endphp

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">{{ $name }}</h1>
                <p class="page-subtitle">{{ $email }}</p>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="row g-4">
            {{-- Customer Info --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center py-4">
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white mb-3"
                            style="width: 80px; height: 80px; font-size: 32px;">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>
                        <h4 class="mb-1">{{ $name }}</h4>
                        <p class="text-muted mb-3">{{ $email }}</p>

                        @if($status == 1)
                            <span class="badge badge-status completed">Hoạt động</span>
                        @else
                            <span class="badge badge-status cancelled">Bị khóa</span>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <div class="text-muted small">Tổng chi tiêu</div>
                                <div class="fw-bold text-success">{{ number_format($totalSpent ?? 0, 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Đơn hàng</div>
                                <div class="fw-bold">{{ count($orders ?? []) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="card mt-4">
                    <div class="card-header">
                        <i class="fa fa-address-card me-2 text-primary"></i>Thông tin liên hệ
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Email</small>
                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Số điện thoại</small>
                            {{ $phone }}
                        </div>
                        <div>
                            <small class="text-muted d-block">Ngày đăng ký</small>
                            {{ $createdAt ? $createdAt->format('d/m/Y H:i') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order History --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-shopping-bag me-2 text-primary"></i>Lịch sử đơn hàng
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders ?? [] as $order)
                                    @php
                                        $orderId = $order->orderId;
                                        $orderStatus = $order->status ?? 1;
                                        
                                        // Map int status to class
                                        $statusClass = match ($orderStatus) {
                                            1 => 'warning', // Pending Payment
                                            2, 3 => 'info', // Paid/Processing
                                            4 => 'primary', // Shipped
                                            5 => 'success', // Delivered
                                            8 => 'danger', // Cancelled
                                            default => 'secondary'
                                        };

                                        $statusText = match ($orderStatus) {
                                            1 => 'Chờ thanh toán',
                                            2 => 'Đã thanh toán',
                                            3 => 'Đang xử lý',
                                            4 => 'Đã giao hàng',
                                            5 => 'Hoàn thành',
                                            6 => 'Hoàn tiền',
                                            7 => 'Trả hàng',
                                            8 => 'Đã hủy',
                                            default => 'Khác'
                                        };
                                    @endphp
                                    <tr>
                                        <td class="fw-medium">#{{ $orderId }}</td>
                                        <td>{{ $order->createdAt ? \Illuminate\Support\Carbon::parse($order->createdAt)->format('d/m/Y') : '-' }}</td>
                                        <td class="fw-medium">{{ number_format($order->total ?? 0, 0, ',', '.') }} ₫</td>
                                        <td>
                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $orderId) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Khách hàng chưa có đơn hàng nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection