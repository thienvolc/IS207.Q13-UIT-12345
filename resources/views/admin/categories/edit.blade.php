@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục - Admin')
@section('page-title', 'Chỉnh sửa danh mục')

@php
    $id = $category->categoryId;
    $title = $category->title;
    $slug = $category->slug;
    $metaTitle = $category->metaTitle ?? '';
    $desc = $category->desc ?? '';
    $parentId = $category->parentId;
    $level = $category->level;
@endphp

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Chỉnh sửa danh mục</h1>
                <p class="page-subtitle">{{ $title }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.categories.update', $id) }}" method="POST">
            @csrf
            @method('PUT')

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
                                    name="title" value="{{ old('title', $title) }}" required
                                    placeholder="Nhập tên danh mục">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                    name="slug" value="{{ old('slug', $slug) }}" placeholder="tu-dong-tao-neu-de-trong">
                                <small class="text-muted">URL friendly. Để trống để tự động tạo từ tên.</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Meta Title --}}
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title" value="{{ old('meta_title', $metaTitle) }}"
                                    placeholder="Tiêu đề SEO">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="desc" class="form-label">Mô tả</label>
                                <textarea class="form-control @error('desc') is-invalid @enderror" id="desc" name="desc"
                                    rows="4" placeholder="Mô tả danh mục...">{{ old('desc', $desc) }}</textarea>
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
                                    @if($cat->categoryId != $id) {{-- Cannot be its own parent --}}
                                        <option value="{{ $cat->categoryId }}" @selected(old('parent_id', $parentId) == $cat->categoryId)>
                                            {{ str_repeat('— ', $cat->level) }}{{ $cat->title }}
                                        </option>
                                        @foreach($cat->children ?? [] as $child)
                                            @if($child->categoryId != $id)
                                                <option value="{{ $child->categoryId }}" @selected(old('parent_id', $parentId) == $child->categoryId)>
                                                    {{ str_repeat('— ', $child->level) }}{{ $child->title }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted mt-2 d-block">Chọn danh mục cha nếu đây là danh mục con.</small>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-info-circle me-2 text-info"></i>Thông tin
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">ID</small>
                                <div class="fw-medium">#{{ $id }}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Level</small>
                                <div>
                                    <span
                                        class="badge bg-{{ $level == 0 ? 'primary' : ($level == 1 ? 'info' : 'secondary') }}">
                                        Level {{ $level }}
                                    </span>
                                </div>
                            </div>
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
                                    <i class="fa fa-save me-1"></i> Cập nhật
                                </button>
                                <button type="submit" name="action" value="save_and_continue"
                                    class="btn btn-outline-primary">
                                    <i class="fa fa-check me-1"></i> Lưu & Tiếp tục
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