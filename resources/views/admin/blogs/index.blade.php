@extends('layouts.admin')

@section('title', 'Quản lý bài viết')
@section('page-title', 'Bài viết')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý bài viết</h1>
                <p class="page-subtitle">Quản lý nội dung blog và tin tức</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-1"></i> Thêm bài viết
                </a>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form action="{{ route('admin.blogs.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <label class="form-label small text-muted">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="query" class="form-control" placeholder="Tiêu đề bài viết..."
                                value="{{ request('query') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small text-muted">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Nháp</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Đã xuất bản</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Lưu trữ</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Posts Table --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="select-all">
                            </th>
                            <th style="width: 70px;">Ảnh</th>
                            <th>Bài viết</th>
                            <th style="width: 120px;">Trạng thái</th>
                            <th style="width: 130px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts->data as $post)
                            @php
                                $id = $post->blogpostId;
                                $title = $post->title;
                                $slug = $post->slug ?? '';
                                $thumb = $post->thumb ?? null;
                                $status = $post->status;
                                
                                $statusClass = match ($status) {
                                    1 => 'badge badge-status secondary', // Draft
                                    2 => 'badge badge-status completed', // Published
                                    3 => 'badge badge-status pending', // Archived
                                    default => 'badge bg-light text-dark border'
                                };
                                $statusText = match ($status) {
                                    1 => 'Nháp',
                                    2 => 'Đã xuất bản',
                                    3 => 'Lưu trữ',
                                    default => 'Unknown'
                                };
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
                                        <a href="{{ route('admin.blogs.edit', $id) }}"
                                            class="fw-medium text-dark text-decoration-none">
                                            {{ Str::limit($title, 50) }}
                                        </a>
                                        <small class="text-muted">{{ $slug }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.blogs.edit', $id) }}" class="btn btn-outline-primary"
                                            title="Chỉnh sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($slug)
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('/blog/' . $slug) }}" target="_blank">
                                                        <i class="fa fa-external-link-alt me-2"></i> Xem trên web
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                            @endif
                                            <li>
                                                <form action="{{ route('admin.blogs.destroy', $id) }}" method="POST"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa fa-trash me-2"></i> Xóa
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-file-alt fa-3x mb-3 opacity-50"></i>
                                        <p>Chưa có bài viết nào.</p>
                                        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus me-1"></i> Tạo bài viết đầu tiên
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($posts->total > 0)
                <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <div class="text-muted small">
                        Hiển thị {{ count($posts->data) }} / {{ $posts->total }} bài viết
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item {{ $posts->page <= 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ route('admin.blogs.index', array_merge(request()->all(), ['page' => $posts->page - 1])) }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            @for($i = max(1, $posts->page - 2); $i <= min(ceil($posts->total / $posts->size), $posts->page + 2); $i++)
                                <li class="page-item {{ $posts->page == $i ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('admin.blogs.index', array_merge(request()->all(), ['page' => $i])) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ !$posts->hasMore ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ route('admin.blogs.index', array_merge(request()->all(), ['page' => $posts->page + 1])) }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection