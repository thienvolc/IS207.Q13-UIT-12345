@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết')
@section('page-title', 'Chỉnh sửa bài viết')

@php
    $id = $post->blogpostId;
    $title = $post->title;
    $slug = $post->slug;
    $summary = $post->summary ?? '';
    $content = $post->content ?? '';
    $thumb = $post->thumb ?? '';
    $status = $post->status;
@endphp

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Chỉnh sửa bài viết</h1>
                <p class="page-subtitle">{{ Str::limit($title, 60) }}</p>
            </div>
            <div class="d-flex gap-2">
                @if($slug)
                    <a href="{{ url('/blog/' . $slug) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="fa fa-external-link-alt me-1"></i> Xem trên web
                    </a>
                @endif
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        <form action="{{ route('admin.blogs.update', $id) }}" method="POST" id="blog-form">
            @csrf
            @method('PUT')

            {{-- Main Content - Full Width --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fa fa-edit me-2 text-primary"></i>Nội dung bài viết
                </div>
                <div class="card-body">
                    {{-- Title, Slug, Status Row --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Nhập tiêu đề bài viết..." value="{{ old('title', $title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Slug (URL)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted">/blog/</span>
                                <input type="text" name="slug" class="form-control" placeholder="tieu-de-bai-viet"
                                    value="{{ old('slug', $slug) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $status) == 1 ? 'selected' : '' }}>Nháp</option>
                                <option value="2" {{ old('status', $status) == 2 ? 'selected' : '' }}>Đã xuất bản</option>
                                <option value="3" {{ old('status', $status) == 3 ? 'selected' : '' }}>Lưu trữ</option>
                            </select>
                        </div>
                    </div>

                    {{-- Summary & Thumbnail Row --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Tóm tắt</label>
                            <textarea name="summary" class="form-control" rows="3"
                                placeholder="Mô tả ngắn về bài viết...">{{ old('summary', $summary) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="hidden" name="thumb" id="thumb-url" value="{{ old('thumb', $thumb) }}">
                            <div id="thumb-preview" class="border rounded p-2 text-center position-relative"
                                style="min-height: 100px;">
                                @if($thumb)
                                    <img src="{{ $thumb }}" alt="Thumbnail" class="img-fluid" style="max-height: 90px;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                        onclick="clearThumb()"><i class="fa fa-times"></i></button>
                                @else
                                    <div class="py-3">
                                        <i class="fa fa-image fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small mb-0">Chưa có ảnh</p>
                                    </div>
                                @endif
                            </div>
                            <label class="btn btn-outline-primary btn-sm w-100 mt-2 mb-0" for="thumb-upload">
                                <i class="fa fa-upload me-1"></i> Upload ảnh đại diện
                            </label>
                            <input type="file" id="thumb-upload" class="d-none" accept="image/*">
                        </div>
                    </div>

                    {{-- Content - Markdown Editor --}}
                    <div class="mb-0">
                        <label class="form-label">Nội dung (Markdown)</label>
                        <textarea name="content" id="markdown-editor">{{ old('content', $content) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Image Gallery Card --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fa fa-images me-2 text-primary"></i>Ảnh minh họa cho bài viết</span>
                    <small class="text-muted">Click vào ảnh để copy URL Markdown</small>
                </div>
                <div class="card-body">
                    <div id="image-gallery" class="d-flex flex-wrap gap-2 mb-3">
                        {{-- Placeholder until images load --}}
                        <span id="no-images-msg" class="text-muted small align-self-center">Đang tải ảnh...</span>
                    </div>

                    {{-- Upload Zone --}}
                    <div id="upload-dropzone" class="border border-2 border-dashed rounded p-3 text-center"
                        style="cursor: pointer; transition: all 0.2s;">
                        <i class="fa fa-cloud-upload-alt fa-lg text-muted me-2"></i>
                        <span class="text-muted">Kéo thả ảnh vào đây hoặc click để upload thêm</span>
                        <input type="file" id="content-image-upload" class="d-none" accept="image/*" multiple>
                    </div>
                </div>
            </div>

            {{-- Form Actions Bar --}}
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times me-1"></i> Hủy
                        </a>
                        <span class="text-muted small">ID: #{{ $id }}</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="fa fa-trash"></i>
                        </button>
                        <button type="submit" name="action" value="save_and_continue" class="btn btn-outline-primary">
                            <i class="fa fa-save me-1"></i> Lưu & Tiếp tục sửa
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check me-1"></i> Cập nhật
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Delete Confirmation Modal --}}
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger">
                            <i class="fa fa-exclamation-triangle me-2"></i>Xác nhận xóa
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <p class="mb-1">Bạn có chắc chắn muốn xóa bài viết:</p>
                        <p class="fw-bold">{{ $title }}</p>
                        <small class="text-muted">Hành động này không thể hoàn tác.</small>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="{{ route('admin.blogs.destroy', $id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash me-1"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <style>
        .EasyMDEContainer .CodeMirror {
            border-radius: 0 0 0.375rem 0.375rem;
            min-height: 400px;
            font-size: 14px;
        }

        .editor-toolbar {
            border-radius: 0.375rem 0.375rem 0 0;
            background: #f8f9fa;
        }

        .editor-toolbar button {
            color: #495057 !important;
        }

        .editor-toolbar button:hover,
        .editor-toolbar button.active {
            background: #e9ecef;
        }

        .EasyMDEContainer .editor-preview {
            padding: 20px;
            background: #fafafa;
        }

        .EasyMDEContainer .editor-preview h1,
        .EasyMDEContainer .editor-preview h2,
        .EasyMDEContainer .editor-preview h3 {
            border-bottom: 1px solid #eee;
            padding-bottom: 0.3em;
            margin-top: 1em;
        }

        .EasyMDEContainer .editor-preview pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 1em;
            border-radius: 5px;
        }

        .EasyMDEContainer .editor-preview code {
            background: #f0f0f0;
            padding: 0.2em 0.4em;
            border-radius: 3px;
        }

        .EasyMDEContainer .editor-preview pre code {
            background: transparent;
            color: inherit;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('markdown-editor');
            if (!textarea) return;

            const easyMDE = new EasyMDE({
                element: textarea,
                spellChecker: false,
                autosave: {
                    enabled: true,
                    uniqueId: 'blog-edit-{{ $id }}',
                    delay: 1000,
                },
                toolbar: [
                    'bold', 'italic', 'heading', '|',
                    'quote', 'unordered-list', 'ordered-list', '|',
                    'link', 'image', 'table', 'horizontal-rule', '|',
                    'preview', 'side-by-side', 'fullscreen', '|',
                    'guide'
                ],
                placeholder: 'Viết nội dung bài viết với Markdown...',
                minHeight: '400px',
                status: ['autosave', 'lines', 'words'],
                renderingConfig: {
                    singleLineBreaks: false,
                    codeSyntaxHighlighting: true,
                },
            });

            // Clear autosave when form is submitted
            document.getElementById('blog-form').addEventListener('submit', function () {
                if (easyMDE.isAutosaved()) {
                    easyMDE.clearAutosavedValue();
                }
            });

            // =====================
            // IMAGE UPLOAD HANDLERS
            // =====================
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Upload function using Cloudinary API
            async function uploadImage(file) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('folder', 'blog');

                const response = await fetch('/api/upload/image', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Upload failed');
                }

                const data = await response.json();
                return data.data; // { url, publicId, ... }
            }

            // Show toast notification
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `alert alert-${type} position-fixed`;
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 200px;';
                toast.innerHTML = `<i class="fa fa-${type === 'success' ? 'check' : 'exclamation-triangle'} me-2"></i>${message}`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

            // Copy to clipboard
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast('Đã copy URL!');
                });
            }

            // Delete image from Cloudinary
            async function deleteImage(publicId) {
                const response = await fetch(`/api/upload/image/${encodeURIComponent(publicId)}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });
                return response.ok;
            }

            // =====================
            // THUMBNAIL UPLOAD
            // =====================
            const thumbUpload = document.getElementById('thumb-upload');
            const thumbUrl = document.getElementById('thumb-url');
            const thumbPreview = document.getElementById('thumb-preview');

            // Global function to clear thumbnail
            window.clearThumb = function () {
                thumbUrl.value = '';
                thumbPreview.innerHTML = `
                                    <div class="py-3">
                                        <i class="fa fa-image fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small mb-0">Chưa có ảnh</p>
                                    </div>
                                `;
            };

            thumbUpload.addEventListener('change', async function (e) {
                const file = e.target.files[0];
                if (!file) return;

                thumbPreview.innerHTML = '<div class="py-3"><div class="spinner-border spinner-border-sm text-primary"></div><p class="small mb-0">Đang upload...</p></div>';

                try {
                    const result = await uploadImage(file);
                    thumbUrl.value = result.url;
                    thumbPreview.innerHTML = `
                                        <img src="${result.url}" alt="Thumbnail" class="img-fluid" style="max-height: 90px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                            onclick="clearThumb()"><i class="fa fa-times"></i></button>
                                    `;
                    showToast('Upload thumbnail thành công!');
                } catch (error) {
                    thumbPreview.innerHTML = '<div class="py-3 text-danger"><i class="fa fa-exclamation-circle"></i><p class="small mb-0">Upload thất bại!</p></div>';
                    showToast('Upload thất bại!', 'danger');
                }
            });

            // =====================
            // IMAGE GALLERY
            // =====================
            const dropzone = document.getElementById('upload-dropzone');
            const contentUpload = document.getElementById('content-image-upload');
            const imageGallery = document.getElementById('image-gallery');
            const noImagesMsg = document.getElementById('no-images-msg');

            // Extract images from markdown content
            function extractImagesFromContent(content) {
                const images = [];
                // Match ![alt](url) pattern
                const regex = /!\[([^\]]*)\]\(([^)]+)\)/g;
                let match;
                while ((match = regex.exec(content)) !== null) {
                    images.push({ alt: match[1], url: match[2] });
                }
                // Also check thumb
                const currentThumb = thumbUrl.value;
                if (currentThumb && !images.find(img => img.url === currentThumb)) {
                    images.unshift({ alt: 'thumbnail', url: currentThumb, isThumb: true });
                }
                return images;
            }

            // Create image card
            function createImageCard(url, publicId = null) {
                const card = document.createElement('div');
                card.className = 'position-relative';
                card.style.cssText = 'width: 100px; height: 100px;';
                card.innerHTML = `
                                    <img src="${url}" class="img-thumbnail w-100 h-100" style="object-fit: cover; cursor: pointer;" 
                                        title="Click để copy Markdown">
                                    <div class="position-absolute top-0 end-0 d-flex gap-1 m-1">
                                        <button type="button" class="btn btn-xs btn-danger p-1" style="font-size: 10px; line-height: 1;" 
                                            title="Xóa ảnh"><i class="fa fa-times"></i></button>
                                    </div>
                                    <small class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white text-center" 
                                        style="font-size: 9px; padding: 2px;">Copy URL</small>
                                `;

                // Click to copy
                card.querySelector('img').onclick = () => copyToClipboard(`![image](${url})`);

                // Delete button
                card.querySelector('.btn-danger').onclick = async (e) => {
                    e.stopPropagation();
                    if (confirm('Xóa ảnh này?')) {
                        if (publicId) {
                            await deleteImage(publicId);
                        }
                        card.remove();
                        if (imageGallery.children.length === 0) {
                            imageGallery.innerHTML = '<span class="text-muted small align-self-center">Chưa có ảnh nào</span>';
                        }
                        showToast('Đã xóa ảnh!');
                    }
                };

                return card;
            }

            // Load existing images from content
            function loadExistingImages() {
                const content = document.getElementById('markdown-editor').value;
                const images = extractImagesFromContent(content);

                imageGallery.innerHTML = '';

                if (images.length === 0) {
                    imageGallery.innerHTML = '<span class="text-muted small align-self-center">Chưa có ảnh nào</span>';
                    return;
                }

                images.forEach(img => {
                    // Try to extract publicId from Cloudinary URL
                    let publicId = null;
                    if (img.url.includes('cloudinary.com')) {
                        const parts = img.url.split('/upload/');
                        if (parts[1]) {
                            publicId = parts[1].replace(/\.[^.]+$/, ''); // Remove extension
                        }
                    }
                    imageGallery.appendChild(createImageCard(img.url, publicId));
                });
            }

            // Load images on page load
            loadExistingImages();

            // Click to upload
            dropzone.addEventListener('click', () => contentUpload.click());

            // Drag & Drop
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.style.borderColor = '#0d6efd';
                dropzone.style.background = '#f0f7ff';
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.style.borderColor = '';
                dropzone.style.background = '';
            });

            dropzone.addEventListener('drop', async (e) => {
                e.preventDefault();
                dropzone.style.borderColor = '';
                dropzone.style.background = '';

                const files = e.dataTransfer.files;
                for (const file of files) {
                    if (file.type.startsWith('image/')) {
                        await handleContentImageUpload(file);
                    }
                }
            });

            // File input change
            contentUpload.addEventListener('change', async function (e) {
                for (const file of e.target.files) {
                    await handleContentImageUpload(file);
                }
                this.value = ''; // Reset input
            });

            async function handleContentImageUpload(file) {
                // Remove "no images" message
                const noMsg = imageGallery.querySelector('.text-muted');
                if (noMsg) noMsg.remove();

                // Create placeholder
                const placeholder = document.createElement('div');
                placeholder.className = 'border rounded d-flex align-items-center justify-content-center';
                placeholder.style.cssText = 'width: 100px; height: 100px;';
                placeholder.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div>';
                imageGallery.appendChild(placeholder);

                try {
                    const result = await uploadImage(file);

                    // Replace placeholder with image card
                    const card = createImageCard(result.url, result.publicId);
                    placeholder.replaceWith(card);

                    showToast('Upload thành công!');
                } catch (error) {
                    placeholder.remove();
                    showToast('Upload thất bại!', 'danger');
                }
            }
        });
    </script>
@endpush