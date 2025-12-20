@extends('layouts.admin')

@section('title', 'Chi tiết giao dịch')
@section('page-title', 'Chi tiết giao dịch #' . $transaction->transactionId)

@php
    $id = $transaction->transactionId;
    $amount = $transaction->amount;
    $status = $transaction->status;
    $type = $transaction->type;
    $code = $transaction->code;
    $createdAt = $transaction->createdAt;
    $updatedAt = $transaction->updatedAt;

    // Status badges similar to index
    $statusLabels = [
        '1' => ['label' => 'Khởi tạo', 'class' => 'info-subtle text-info'], // INITIATED
        '2' => ['label' => 'Đang chờ', 'class' => 'warning-subtle text-warning'], // PENDING
        '3' => ['label' => 'Thành công', 'class' => 'success-subtle text-success'], // SUCCESS
        '4' => ['label' => 'Thất bại', 'class' => 'danger-subtle text-danger'], // FAILED
        '5' => ['label' => 'Đã hủy', 'class' => 'secondary-subtle text-secondary'], // CANCELLED
        '6' => ['label' => 'Hết hạn', 'class' => 'dark-subtle text-dark'], // EXPIRED
    ];

    $statusInfo = $statusLabels[(string) $status] ?? ['label' => 'Không xác định', 'class' => 'secondary-subtle text-secondary'];
@endphp

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Giao dịch #{{ $id }}</h1>
                <p class="page-subtitle">
                    @if($createdAt)
                        Tạo lúc {{ \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i') }}
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Quay lại
                </a>
                @if($transaction->orderId)
                    <a href="{{ route('admin.orders.show', $transaction->orderId) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-shopping-bag me-1"></i> Xem đơn hàng
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-4">
            {{-- Main Info --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa fa-info-circle me-2"></i> Thông tin chung
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="small text-muted d-block">Mã giao dịch</label>
                                <span class="fw-bold fs-5">#{{ $id }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted d-block">Trạng thái</label>
                                <span class="badge bg-{{ $statusInfo['class'] }} fs-6">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="small text-muted d-block">Số tiền</label>
                                <span class="fw-bold text-success fs-4">{{ number_format($amount) }} ₫</span>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted d-block">Cổng thanh toán / Mã</label>
                                <span class="badge bg-light text-dark border">{{ $code ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 text-muted">
                                <small>Phương thức: {{ $type }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($transaction->content))
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-file-alt me-2"></i> Nội dung giao dịch
                        </div>
                        <div class="card-body">
                            <p class="mb-0 bg-light p-3 rounded" style="font-family: monospace;">
                                {{ $transaction->content }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                {{-- User Info --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa fa-user me-2"></i> Thông tin khách hàng
                    </div>
                    <div class="card-body">
                        @php
                            $userId = $transaction->order['userId'] ?? null;
                            $firstName = $transaction->order['user']['firstName'] ?? $transaction->order['firstName'] ?? '';
                            $lastName = $transaction->order['user']['lastName'] ?? $transaction->order['lastName'] ?? '';
                            $email = $transaction->order['user']['email'] ?? $transaction->order['email'] ?? '';
                            $customerName = trim("$firstName $lastName") ?: ($userId ? "Khách #$userId" : null);
                        @endphp
                        @if($userId)
                            <div class="mb-3">
                                <small class="text-muted d-block">Khách hàng</small>
                                <a href="{{ route('admin.customers.show', $userId) }}"
                                    class="fw-bold text-primary text-decoration-none fs-5">
                                    {{ $customerName }}
                                </a>
                            </div>
                            @if($email)
                                <div class="mb-2">
                                    <small class="text-muted d-block"><i class="fa fa-envelope me-1"></i> Email</small>
                                    <span>{{ $email }}</span>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3 text-muted">
                                Không có thông tin khách hàng
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Timestamps --}}
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-clock me-2"></i> Thời gian
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted d-block">Ngày tạo</small>
                            <span>{{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y H:i:s') : 'N/A' }}</span>
                        </div>
                        @if($updatedAt && $updatedAt != $createdAt)
                            <div>
                                <small class="text-muted d-block">Cập nhật lần cuối</small>
                                <span>{{ \Carbon\Carbon::parse($updatedAt)->format('d/m/Y H:i:s') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection