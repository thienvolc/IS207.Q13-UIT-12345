@extends('layouts.admin')

@section('title', 'Thêm danh mục - Admin')
@section('page-title', 'Thêm danh mục mới')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Thêm danh mục mới</h1>
                <p class="page-subtitle">Tạo danh mục sản phẩm mới</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-folder me-2 text-primary"></i>Thông tin danh mục
                        </div>
                        <div class="card-body">
                            {{-- Title --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">Tên danh mục <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}" required placeholder="Nhập tên danh mục">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                    name="slug" value="{{ old('slug') }}" placeholder="tu-dong-tao-neu-de-trong">
                                <small class="text-muted">URL friendly. Để trống để tự động tạo từ tên.</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Meta Title --}}
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                                    placeholder="Tiêu đề SEO">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="desc" class="form-label">Mô tả</label>
                                <textarea class="form-control @error('desc') is-invalid @enderror" id="desc" name="desc"
                                    rows="4" placeholder="Mô tả danh mục...">{{ old('desc') }}</textarea>
                                @error('desc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Parent Category --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-sitemap me-2 text-info"></i>Danh mục cha
                        </div>
                        <div class="card-body">
                            <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id">
                                <option value="">Không có (Danh mục gốc)</option>
                                @foreach($allCategories ?? [] as $cat)
                                    <option value="{{ $cat->categoryId }}" @selected(old('parent_id') == $cat->categoryId)>
                                        {{ str_repeat('— ', $cat->level) }}{{ $cat->title }}
                                    </option>
                                    @foreach($cat->children ?? [] as $child)
                                        <option value="{{ $child->categoryId }}" @selected(old('parent_id') == $child->categoryId)>
                                            {{ str_repeat('— ', $child->level) }}{{ $child->title }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            <small class="text-muted mt-2 d-block">Chọn danh mục cha nếu đây là danh mục con.</small>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-save me-2 text-success"></i>Hành động
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" name="action" value="save" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Lưu danh mục
                                </button>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-outline-primary">
                                    <i class="fa fa-plus me-1"></i> Lưu & Thêm mới
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-times me-1"></i> Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
@endsection