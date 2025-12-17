@extends('layouts.admin')

@section('title', 'Quản lý tồn kho - Admin')
@section('page-title', 'Tồn kho')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý tồn kho</h1>
                <p class="page-subtitle">Theo dõi và điều chỉnh số lượng sản phẩm</p>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- KPI Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded bg-primary-soft p-3">
                            <i class="fa fa-box fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Tổng sản phẩm</div>
                            <div class="fs-4 fw-bold">{{ $pagination['totalItems'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded bg-primary-soft p-3">
                            <i class="fa fa-exclamation-triangle fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Sắp hết hàng</div>
                            <div class="fs-4 fw-bold text-warning">{{ $lowStockCount ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded bg-primary-soft p-3">
                            <i class="fa fa-times-circle fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Hết hàng</div>
                            <div class="fs-4 fw-bold text-danger">{{ $outOfStockCount ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" name="q" class="form-control" placeholder="Tên sản phẩm, SKU..."
                                value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Sắp xếp</label>
                        <select class="form-select" name="order">
                            <option value="asc" @selected(request('order', 'asc') == 'asc')>Tồn kho: Thấp → Cao</option>
                            <option value="desc" @selected(request('order') == 'desc')>Tồn kho: Cao → Thấp</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fa fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Inventory Table --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Sản phẩm</th>
                            <th style="width: 120px;" class="text-center">Tồn kho</th>
                            <th style="width: 120px;" class="text-center">Trạng thái</th>
                            <th style="width: 200px;" class="text-center">Điều chỉnh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products ?? [] as $product)
                            @php
                                $id = $product->productId;
                                $title = $product->title;
                                $thumb = $product->thumb;
                                $quantity = $product->quantity ?? 0;
                                $sku = $product->sku ?? $product->productId;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $thumb ?: 'https://via.placeholder.com/48x48?text=No+Image' }}"
                                            alt="{{ $title }}" class="rounded"
                                            style="width: 48px; height: 48px; object-fit: cover;"
                                            onerror="this.src='https://via.placeholder.com/48x48?text=No+Image'">
                                        <div>
                                            <a href="{{ route('admin.products.edit', $id) }}"
                                                class="fw-medium text-dark text-decoration-none">
                                                {{ Str::limit($title, 40) }}
                                            </a>
                                            <br><small class="text-muted">SKU: {{ $sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-5">{{ number_format($quantity) }}</span>
                                </td>
                                <td class="text-center">
                                    @if($quantity <= 0)
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @elseif($quantity < 10)
                                        <span class="badge bg-warning text-dark">Sắp hết</span>
                                    @else
                                        <span class="badge bg-success">Còn hàng</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.inventory.adjust', $id) }}" method="POST"
                                        class="d-inline-flex gap-1 align-items-center">
                                        @csrf
                                        <input type="number" name="amount" value="1" min="1"
                                            class="form-control form-control-sm" style="width: 70px;">
                                        <button type="submit" name="operation" value="increase" class="btn btn-sm btn-success"
                                            title="Nhập kho">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <button type="submit" name="operation" value="decrease" class="btn btn-sm btn-danger"
                                            title="Xuất kho" @if($quantity <= 0) disabled @endif>
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa fa-box-open fa-3x mb-3 d-block opacity-50"></i>
                                        <p class="mb-0">Chưa có sản phẩm nào</p>
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
                    Hiển thị {{ count($products ?? []) }} / {{ $pagination['totalItems'] ?? 0 }} sản phẩm
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

    <style>
        .bg-primary-soft {
            background-color: var(--primary-soft);
        }

        .bg-warning-soft {
            background-color: var(--warning-soft);
        }

        .bg-danger-soft {
            background-color: var(--danger-soft);
        }
    </style>
@endsection