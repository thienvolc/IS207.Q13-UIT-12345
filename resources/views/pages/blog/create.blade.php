{{-- resources/views/pages/blog/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Đăng bài viết mới')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow-lg w-100" style="max-width:600px;">
        <div class="card-body p-4">
            <h1 class="mb-4 text-center text-primary fw-bold">Đăng bài viết mới</h1>
            <form id="blog-create-form" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Tiêu đề bài viết</label>
                    <input type="text" class="form-control rounded-3" id="title" name="title" required placeholder="Nhập tiêu đề...">
                </div>
                <div class="mb-3">
                    <label for="summary" class="form-label fw-semibold">Tóm tắt</label>
                    <textarea class="form-control rounded-3" id="summary" name="summary" rows="2" placeholder="Tóm tắt ngắn gọn..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Nội dung</label>
                    <textarea class="form-control rounded-3" id="content" name="content" rows="8" required placeholder="Nội dung bài viết..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="thumb" class="form-label fw-semibold">Ảnh đại diện</label>
                    <input type="file" class="form-control rounded-3" id="thumb" name="thumb" accept="image/*">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg rounded-3 shadow-sm">
                        <i class="fa-solid fa-paper-plane me-2"></i>Đăng bài
                    </button>
                </div>
            </form>
            <div id="blog-create-result" class="mt-4"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('blog-create-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        // TODO: Gọi API backend khi có
        document.getElementById('blog-create-result').innerHTML = '<div class="alert alert-info">Tính năng gửi bài sẽ hoạt động khi backend có API!</div>';
    });
</script>
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