@extends('layouts.admin')

@section('title', 'Quản lý khách hàng - Admin')
@section('page-title', 'Khách hàng')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý khách hàng</h1>
                <p class="page-subtitle">Danh sách tất cả khách hàng đã đăng ký</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export.customers') }}" class="btn btn-outline-secondary btn-sm"
                    target="_blank">
                    <i class="fa fa-download me-1"></i> Xuất Excel
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="kpi-card-label">Tổng khách hàng</div>
                            <div class="kpi-card-value">{{ $totalCustomers ?? 0 }}</div>
                        </div>
                        <div class="kpi-card-icon primary">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="kpi-card-label">Đang hoạt động</div>
                            <div class="kpi-card-value text-success">{{ $activeCustomers ?? 0 }}</div>
                        </div>
                        <div class="kpi-card-icon primary">
                            <i class="fa fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="kpi-card-label">Mới trong tháng</div>
                            <div class="kpi-card-value text-info">{{ $newThisMonth ?? 0 }}</div>
                        </div>
                        <div class="kpi-card-icon primary">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" name="q" class="form-control" placeholder="Tên, email, số điện thoại..."
                                value="{{ request('q') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="">Tất cả</option>
                            <option value="1" @selected(request('status') == '1')>Hoạt động</option>
                            <option value="0" @selected(request('status') == '0')>Bị khóa</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fa fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Customers Table --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Khách hàng</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th style="width: 120px;">Ngày đăng ký</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th style="width: 100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers ?? [] as $customer)
                            @php
                                $id = $customer->userId;
                                $name = trim(($customer->firstName ?? '') . ' ' . ($customer->lastName ?? '')) ?: ($customer->email ?? 'N/A');
                                $email = $customer->email ?? '';
                                $phone = $customer->phone ?? '-';
                                $status = $customer->status ?? 1;
                                $createdAt = $customer->createdAt ?? null;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                            style="width: 36px; height: 36px; font-size: 14px;">
                                            {{ strtoupper(substr($name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $email }}" class="text-decoration-none">{{ $email }}</a>
                                </td>
                                <td>{{ $phone }}</td>
                                <td>
                                    <small>{{ $createdAt ? \Carbon\Carbon::parse($createdAt)->format('d/m/Y') : '-' }}</small>
                                </td>
                                <td>
                                    @if($status == 1)
                                        <span class="badge badge-status completed">Hoạt động</span>
                                    @else
                                        <span class="badge badge-status cancelled">Bị khóa</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $id) }}" class="btn btn-sm btn-outline-primary"
                                        title="Xem chi tiết">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa fa-users fa-3x mb-3 d-block opacity-50"></i>
                                        <p class="mb-0">Chưa có khách hàng nào</p>
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
                    Hiển thị {{ count($customers ?? []) }} / {{ $pagination['totalItems'] ?? 0 }} khách hàng
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
                                    <a class="page-link"
                                        href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">‹</a>
                                </li>
                                @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">›</a>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection