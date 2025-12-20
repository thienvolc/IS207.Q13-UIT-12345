@extends('layouts.admin')

@section('title', 'Quản lý giao dịch')
@section('page-title', 'Giao dịch')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý giao dịch</h1>
                <p class="page-subtitle">Theo dõi dòng tiền và lịch sử thanh toán</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export.transactions') }}" class="btn btn-outline-secondary btn-sm"
                    target="_blank">
                    <i class="fa fa-download me-1"></i> Xuất Excel
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body py-3">
                <form action="{{ route('admin.transactions.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label small text-muted">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="query" class="form-control" placeholder="Mã giao dịch..."
                                value="{{ request('query') }}">
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <label class="form-label small text-muted">Mã đơn hàng</label>
                        <input type="number" name="order_id" class="form-control" placeholder="ID đơn"
                            value="{{ request('order_id') }}">
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <label class="form-label small text-muted">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Khởi tạo</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Đang chờ</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Thành công</option>
                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Thất bại</option>
                            <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>Hết hạn</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter me-1"></i>
                            Lọc</button>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Loại</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions->data as $transaction)
                            <tr>
                                <td><span class="fw-bold">#{{ $transaction->transactionId }}</span></td>
                                <td>
                                    @if($transaction->orderId)
                                        <a href="{{ route('admin.orders.show', $transaction->orderId) }}"
                                            class="text-primary text-decoration-none">
                                            #{{ $transaction->orderId }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $userId = $transaction->order['userId'] ?? null;
                                        $firstName = $transaction->order['user']['firstName'] ?? $transaction->order['firstName'] ?? '';
                                        $lastName = $transaction->order['user']['lastName'] ?? $transaction->order['lastName'] ?? '';
                                        $customerName = trim("$firstName $lastName") ?: ($userId ? "Khách #$userId" : '-');
                                    @endphp
                                    @if($userId)
                                        <a href="{{ route('admin.customers.show', $userId) }}"
                                            class="text-decoration-none text-primary fw-medium">
                                            {{ $customerName }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-success">
                                    {{ number_format($transaction->amount) }} ₫
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $transaction->code ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $transaction->type === 'payment' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }}">
                                        {{ $transaction->type }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusConfig = match ((int) $transaction->status) {
                                            1 => ['text' => 'Khởi tạo', 'class' => 'bg-info-subtle text-info', 'icon' => 'fa-hourglass-start'], // INITIATED
                                            2 => ['text' => 'Đang chờ', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'fa-clock'], // PENDING
                                            3 => ['text' => 'Thành công', 'class' => 'bg-success-subtle text-success', 'icon' => 'fa-check-circle'], // SUCCESS
                                            4 => ['text' => 'Thất bại', 'class' => 'bg-danger-subtle text-danger', 'icon' => 'fa-times-circle'], // FAILED
                                            5 => ['text' => 'Đã hủy', 'class' => 'bg-secondary-subtle text-secondary', 'icon' => 'fa-ban'], // CANCELLED
                                            6 => ['text' => 'Hết hạn', 'class' => 'bg-dark-subtle text-dark', 'icon' => 'fa-exclamation-circle'], // EXPIRED
                                            default => ['text' => 'Không xác định', 'class' => 'bg-light text-dark border', 'icon' => 'fa-question-circle']
                                        };
                                    @endphp
                                    <span class="badge {{ $statusConfig['class'] }}">
                                        <i class="fas {{ $statusConfig['icon'] }} me-1"></i> {{ $statusConfig['text'] }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($transaction->createdAt)->format('H:i d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $transaction->transactionId) }}"
                                        class="btn btn-sm btn-outline-primary" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3 opacity-50"></i>
                                        <p>Không tìm thấy giao dịch nào.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="text-muted small">
                    Hiển thị {{ count($transactions->data) }} / {{ $transactions->total }} giao dịch
                </div>
                @if($transactions->total > 0)
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item {{ $transactions->page <= 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ route('admin.transactions.index', array_merge(request()->all(), ['page' => $transactions->page - 1])) }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            @for($i = max(1, $transactions->page - 2); $i <= min(ceil($transactions->total / $transactions->size), $transactions->page + 2); $i++)
                                <li class="page-item {{ $transactions->page == $i ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('admin.transactions.index', array_merge(request()->all(), ['page' => $i])) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="page-item {{ !$transactions->hasMore ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ route('admin.transactions.index', array_merge(request()->all(), ['page' => $transactions->page + 1])) }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection