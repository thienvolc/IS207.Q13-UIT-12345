@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm - Admin')
@section('page-title', 'Sản phẩm')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý sản phẩm</h1>
                <p class="page-subtitle">Quản lý tất cả sản phẩm trong cửa hàng</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export.products') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-download me-1"></i> Xuất Excel
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i> Thêm sản phẩm
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="kpi-card-label">Tổng sản phẩm</div>
                            <div class="kpi-card-value">{{ $totalProducts ?? count($products ?? []) }}</div>
                        </div>
                        <div class="kpi-card-icon primary">
                            <i class="fa fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="kpi-card-label">Đang hiển thị</div>
                            <div class="kpi-card-value text-success">{{ $activeProducts ?? 0 }}</div>
                        </div>
                        <div class="kpi-card-icon primary">
                            <i class="fa fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="kpi-card-label">Đang ẩn</div>
                            <div class="kpi-card-value text-muted">{{ $hiddenProducts ?? 0 }}</div>
                        </div>
                        <div class="kpi-card-icon primary">
                            <i class="fa fa-eye-slash"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="kpi-card-label">Sắp hết hàng</div>
                            <div class="kpi-card-value text-danger">{{ $lowStockProducts ?? 0 }}</div>
                        </div>
                        <div class="kpi-card-icon primary">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" class="row g-3 align-items-end">
                    {{-- Search --}}
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" name="q" class="form-control" placeholder="Tên sản phẩm, SKU..."
                                value="{{ request('q') }}">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Danh mục</label>
                        <select class="form-select" name="category">
                            <option value="">Tất cả</option>
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat->categoryId }}" @selected(request('category') == $cat->categoryId)>
                                    {{ $cat->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="">Tất cả</option>
                            <option value="1" @selected(request('status') == '1')>Đang bán</option>
                            <option value="2" @selected(request('status') == '2')>Hết hàng</option>
                            <option value="3" @selected(request('status') == '3')>Tạm ngưng</option>
                            <option value="4" @selected(request('status') == '4')>Ngừng KD</option>
                            <option value="5" @selected(request('status') == '5')>Lưu trữ</option>
                        </select>
                    </div>

                    {{-- Stock Status --}}
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Tồn kho</label>
                        <select class="form-select" name="stock">
                            <option value="">Tất cả</option>
                            <option value="in_stock" @selected(request('stock') == 'in_stock')>Còn hàng</option>
                            <option value="low_stock" @selected(request('stock') == 'low_stock')>Sắp hết</option>
                            <option value="out_of_stock" @selected(request('stock') == 'out_of_stock')>Hết hàng</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fa fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Products Table --}}
        <div class="card">
            {{-- Bulk Actions Bar --}}
            <div class="card-header d-flex justify-content-between align-items-center py-2" id="bulk-actions"
                style="display: none !important;">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted"><span id="selected-count">0</span> sản phẩm được chọn</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-success" id="bulk-activate">
                            <i class="fa fa-eye me-1"></i> Hiện
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="bulk-deactivate">
                            <i class="fa fa-eye-slash me-1"></i> Ẩn
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="bulk-delete">
                            <i class="fa fa-trash me-1"></i> Xóa
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="select-all">
                            </th>
                            <th style="width: 70px;">Ảnh</th>
                            <th>Sản phẩm</th>
                            <th style="width: 120px;">Giá</th>
                            <th style="width: 100px;">Tồn kho</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th style="width: 130px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products ?? [] as $product)
                            @php
                                $id = $product->productId;
                                $title = $product->title;
                                $slug = $product->slug;
                                $thumb = $product->thumb;
                                $price = $product->price;
                                $discount = $product->discount;
                                $quantity = $product->quantity;
                                $status = $product->status;
                            @endphp
                            <tr data-id="{{ $id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input row-checkbox" value="{{ $id }}">
                                </td>
                                <td>
                                    <img src="{{ $thumb ?? 'https://via.placeholder.com/60x60?text=No+Image' }}"
                                        alt="{{ $title }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
                                        onerror="this.src='https://via.placeholder.com/60x60?text=No+Image'">
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('admin.products.edit', $id) }}"
                                            class="fw-medium text-dark text-decoration-none">
                                            {{ Str::limit($title, 50) }}
                                        </a>
                                        <small class="text-muted">{{ $slug }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($discount > 0)
                                            <span class="text-decoration-line-through text-muted small">
                                                {{ number_format($price, 0, ',', '.') }} ₫
                                            </span>
                                            <span class="fw-medium text-danger">
                                                {{ number_format($price * (1 - $discount / 100), 0, ',', '.') }} ₫
                                            </span>
                                        @else
                                            <span class="fw-medium">{{ number_format($price, 0, ',', '.') }} ₫</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($quantity <= 0)
                                        <span class="badge badge-status cancelled">Hết hàng</span>
                                    @elseif($quantity < 10)
                                        <span class="badge badge-status pending">{{ $quantity }}</span>
                                    @else
                                        <span class="text-success fw-medium">{{ $quantity }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($status) {
                                            1 => 'badge badge-status completed', // Active
                                            2 => 'badge badge-status pending', // Out of Stock
                                            3 => 'badge badge-status secondary', // Inactive
                                            4 => 'badge badge-status cancelled', // Discontinued
                                            5 => 'badge badge-status dark', // Archive
                                            default => 'badge bg-light text-dark border'
                                        };
                                        $statusText = match ($status) {
                                            1 => 'Đang bán',
                                            2 => 'Hết hàng',
                                            3 => 'Tạm ngưng',
                                            4 => 'Ngừng KD',
                                            5 => 'Lưu trữ',
                                            default => 'Unknown'
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.products.edit', $id) }}" class="btn btn-outline-primary"
                                            title="Chỉnh sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/san-pham/' . $slug) }}" target="_blank">
                                                    <i class="fa fa-external-link-alt"></i> Xem trên web
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" onclick="duplicateProduct({{ $id }})">
                                                    <i class="fa fa-copy"></i> Nhân bản
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.products.destroy', $id) }}" method="POST"
                                                    class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                                        <p class="mb-2">Không có sản phẩm nào</p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus me-1"></i> Thêm sản phẩm đầu tiên
                                        </a>
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
                    {{-- Manual Pagination --}}
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

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p class="mb-0">Bạn có chắc chắn muốn xóa sản phẩm này?</p>
                    <small class="text-muted">Hành động này không thể hoàn tác.</small>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select all checkbox
            const selectAll = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCount = document.getElementById('selected-count');

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActions();
                });
            }

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkActions);
            });

            function updateBulkActions() {
                const checked = document.querySelectorAll('.row-checkbox:checked');
                if (bulkActions) {
                    bulkActions.style.display = checked.length > 0 ? 'flex' : 'none';
                }
                if (selectedCount) {
                    selectedCount.textContent = checked.length;
                }
            }

            // Delete confirmation
            const deleteForms = document.querySelectorAll('.delete-form');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let currentDeleteForm = null;

            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    currentDeleteForm = this;
                    deleteModal.show();
                });
            });

            document.getElementById('confirmDelete')?.addEventListener('click', function () {
                if (currentDeleteForm) {
                    currentDeleteForm.submit();
                }
            });
        });

        function duplicateProduct(id) {
            alert('Chức năng nhân bản sản phẩm #' + id + ' đang được phát triển');
        }
    </script>
@endpush