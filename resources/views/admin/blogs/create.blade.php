@extends('layouts.admin')

@section('title', 'Tạo bài viết mới')
@section('page-title', 'Tạo bài viết')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <style>
        .EasyMDEContainer .CodeMirror {
            border-radius: 0 0 0.375rem 0.375rem;
            min-height: 350px;
        }

        .editor-toolbar {
            border-radius: 0.375rem 0.375rem 0 0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Tạo bài viết mới</h1>
                <p class="page-subtitle">Soạn nội dung với Markdown Editor</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        <form action="{{ route('admin.blogs.store') }}" method="POST" id="blog-form">
            @csrf

            <div class="row g-4">
                {{-- Left Column - Main Content --}}
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-edit me-2 text-primary"></i>Nội dung bài viết
                        </div>
                        <div class="card-body">
                            {{-- Title --}}
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Nhập tiêu đề bài viết..." value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label class="form-label">Slug (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted">/blog/</span>
                                    <input type="text" name="slug" class="form-control" placeholder="tieu-de-bai-viet"
                                        value="{{ old('slug') }}">
                                </div>
                                <small class="text-muted">Để trống sẽ tự động tạo từ tiêu đề</small>
                            </div>

                            {{-- Summary --}}
                            <div class="mb-3">
                                <label class="form-label">Tóm tắt</label>
                                <textarea name="summary" class="form-control" rows="3"
                                    placeholder="Mô tả ngắn về bài viết...">{{ old('summary') }}</textarea>
                            </div>

                            {{-- Content - Markdown Editor --}}
                            <div class="mb-3">
                                <label class="form-label">Nội dung (Markdown)</label>
                                <textarea name="content" id="markdown-editor">{{ old('content') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Settings --}}
                <div class="col-lg-4">
                    {{-- Publish Settings --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-cog me-2 text-primary"></i>Cài đặt
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Nháp</option>
                                    <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Xuất bản ngay</option>
                                    <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Lưu trữ</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện (URL)</label>
                                <input type="text" name="thumb" class="form-control"
                                    placeholder="https://example.com/image.jpg" value="{{ old('thumb') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Lưu bài viết
                                </button>
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-times me-1"></i> Hủy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const easyMDE = new EasyMDE({
                element: document.getElementById('markdown-editor'),
                spellChecker: false,
                autosave: {
                    enabled: true,
                    uniqueId: 'blog-create-content',
                    delay: 1000,
                },
                toolbar: [
                    'bold', 'italic', 'heading', '|',
                    'quote', 'unordered-list', 'ordered-list', '|',
                    'link', 'image', 'table', '|',
                    'preview', 'side-by-side', 'fullscreen', '|',
                    'guide'
                ],
                placeholder: 'Bắt đầu viết nội dung bài viết với Markdown...',
                minHeight: '350px',
            });
        });
    </script>
@endsection