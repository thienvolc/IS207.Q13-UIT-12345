@extends('layouts.admin')

@section('title', 'Quản lý danh mục - Admin')
@section('page-title', 'Danh mục')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý danh mục</h1>
                <p class="page-subtitle">Tổng cộng {{ $pagination['totalItems'] ?? 0 }} danh mục</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i> Thêm danh mục
                </a>
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
                            <input type="text" name="q" class="form-control" placeholder="Tên danh mục, slug..."
                                value="{{ request('q') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Cấp danh mục</label>
                        <select class="form-select" name="level">
                            <option value="">Tất cả</option>
                            <option value="0" @selected(request('level') == '0')>Cấp gốc (Level 0)</option>
                            <option value="1" @selected(request('level') == '1')>Cấp 1</option>
                            <option value="2" @selected(request('level') == '2')>Cấp 2</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fa fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Categories Table --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Tên danh mục</th>
                            <th style="width: 200px;">Slug</th>
                            <th style="width: 100px;">Cấp</th>
                            <th style="width: 150px;">Danh mục cha</th>
                            <th style="width: 130px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories ?? [] as $category)
                            @php
                                $id = $category->categoryId;
                                $title = $category->title;
                                $slug = $category->slug;
                                $level = $category->level;
                                $parentId = $category->parentId;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                            style="width: 36px; height: 36px; font-size: 14px;">
                                            <i class="fa fa-folder"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.categories.edit', $id) }}"
                                                class="fw-medium text-dark text-decoration-none">
                                                {{ $title }}
                                            </a>
                                            @if(count($category->children ?? []) > 0)
                                                <br><small class="text-muted">{{ count($category->children) }} danh mục con</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><code class="text-muted">{{ $slug }}</code></td>
                                <td>
                                    <span class="badge bg-{{ $level == 0 ? 'primary' : ($level == 1 ? 'info' : 'secondary') }}">
                                        Level {{ $level }}
                                    </span>
                                </td>
                                <td>
                                    @if($parentId)
                                        <span class="text-muted">#{{ $parentId }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.categories.edit', $id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
                                        <p class="mb-0">Chưa có danh mục nào</p>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">
                                            <i class="fa fa-plus me-1"></i> Tạo danh mục đầu tiên
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
                    Hiển thị {{ count($categories ?? []) }} / {{ $pagination['totalItems'] ?? 0 }} danh mục
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