{{-- resources/views/pages/blog/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Chỉnh sửa bài viết - PinkCapy')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow-lg w-100" style="max-width:600px;">
        <div class="card-body p-4">
            <h1 class="mb-4 text-center text-primary fw-bold">Chỉnh sửa bài viết</h1>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('blog.update', $post->blogpostId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Tiêu đề bài viết</label>
                    <input type="text" class="form-control rounded-3" id="title" name="title" required placeholder="Nhập tiêu đề..." value="{{ old('title', $post->title) }}">
                </div>
                <div class="mb-3">
                    <label for="summary" class="form-label fw-semibold">Tóm tắt</label>
                    <textarea class="form-control rounded-3" id="summary" name="summary" rows="2" placeholder="Tóm tắt ngắn gọn...">{{ old('summary', $post->summary) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Nội dung</label>
                    <textarea class="form-control rounded-3" id="content" name="content" rows="8" required placeholder="Nội dung bài viết...">{{ old('content', $post->content) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="thumb" class="form-label fw-semibold">Ảnh đại diện</label>
                    @if($post->thumb)
                        <div class="mb-2">
                            <img src="{{ $post->thumb }}" alt="Current thumbnail" style="max-width: 200px; border-radius: 8px;">
                            <p class="text-muted small mt-1">Ảnh hiện tại (tải ảnh mới để thay đổi)</p>
                        </div>
                    @endif
                    <input type="file" class="form-control rounded-3" id="thumb" name="thumb" accept="image/*">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg rounded-3 shadow-sm">
                        <i class="fa-solid fa-save me-2"></i>Cập nhật bài viết
                    </button>
                    <a href="{{ route('account.my-posts') }}" class="btn btn-secondary btn-lg rounded-3">
                        <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 18px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 2px #0d6efd33;
    }

    .btn-success {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>
@endpush
