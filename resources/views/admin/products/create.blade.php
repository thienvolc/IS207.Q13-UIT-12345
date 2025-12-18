@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới - Admin')
@section('page-title', 'Thêm sản phẩm')

@section('content')
  <div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header">
      <div>
        <h1 class="page-title">Thêm sản phẩm mới</h1>
        <p class="page-subtitle">Điền thông tin để tạo sản phẩm mới</p>
      </div>
      <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left me-1"></i> Quay lại
      </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
      @csrf

      <div class="row g-4">
        {{-- Left Column - Main Info --}}
        <div class="col-lg-8">
          {{-- Basic Information --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-info-circle me-2 text-primary"></i>Thông tin cơ bản
            </div>
            <div class="card-body">
              <div class="row g-3">
                {{-- Product Title --}}
                <div class="col-12">
                  <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                    placeholder="Nhập tên sản phẩm..." value="{{ old('title') }}" required>
                  @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Slug --}}
                <div class="col-12">
                  <label class="form-label">Slug (URL)</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white text-muted">/san-pham/</span>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                      placeholder="ten-san-pham" value="{{ old('slug') }}">
                  </div>
                  <small class="text-muted">Để trống sẽ tự động tạo từ tên sản phẩm</small>
                  @error('slug')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Summary --}}
                <div class="col-12">
                  <label class="form-label">Tóm tắt</label>
                  <input type="text" name="summary" class="form-control" placeholder="Mô tả ngắn về sản phẩm..."
                    value="{{ old('summary') }}" maxlength="255">
                  <small class="text-muted">Tối đa 255 ký tự</small>
                </div>

                {{-- Description --}}
                <div class="col-12">
                  <label class="form-label">Mô tả chi tiết</label>
                  <textarea name="desc" class="form-control" rows="6"
                    placeholder="Mô tả chi tiết về sản phẩm...">{{ old('desc') }}</textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- Pricing --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-tag me-2 text-primary"></i>Giá bán
            </div>
            <div class="card-body">
              <div class="row g-3">
                {{-- Price --}}
                <div class="col-md-4">
                  <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                      placeholder="0" value="{{ old('price', 0) }}" min="0" step="1000" required>
                    <span class="input-group-text">₫</span>
                  </div>
                  @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Discount --}}
                <div class="col-md-4">
                  <label class="form-label">Giảm giá</label>
                  <div class="input-group">
                    <input type="number" name="discount" class="form-control" placeholder="0"
                      value="{{ old('discount', 0) }}" min="0" max="100">
                    <span class="input-group-text">%</span>
                  </div>
                </div>

                {{-- Sale End Date --}}
                <div class="col-md-4">
                  <label class="form-label">Kết thúc giảm giá</label>
                  <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                </div>
              </div>

              {{-- Price Preview --}}
              <div class="mt-3 p-3 bg-light rounded">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-muted">Giá sau giảm:</span>
                  <span class="fs-5 fw-bold text-success" id="final-price">0 ₫</span>
                </div>
              </div>
            </div>
          </div>

          {{-- Images --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-images me-2 text-primary"></i>Hình ảnh
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Ảnh đại diện</label>
                <input type="file" name="thumb" class="form-control" accept="image/*" id="thumb-input">
              </div>

              {{-- Image Preview --}}
              <div id="image-preview" class="d-none">
                <img src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
              </div>

              <div class="upload-zone border-2 border-dashed rounded p-4 text-center" id="upload-zone">
                <i class="fa fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                <p class="mb-1">Kéo thả hoặc click để chọn ảnh</p>
                <small class="text-muted">PNG, JPG, WEBP tối đa 5MB</small>
              </div>
            </div>
          </div>
        </div>

        {{-- Right Column - Metadata --}}
        <div class="col-lg-4">
          {{-- Product Image --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-image me-2 text-primary"></i>Hình ảnh sản phẩm
            </div>
            <div class="card-body">
              @include('admin.partials.image-upload', [
                'name' => 'thumb',
                'value' => old('thumb'),
                'label' => 'Hình đại diện',
                'folder' => 'products'
              ])
            </div>
          </div>

          {{-- Status & Type --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-cog me-2 text-primary"></i>Thiết lập
            </div>
            <div class="card-body">
              {{-- Status --}}
              <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                  <option value="1" @selected(old('status', 1) == 1)>Đang bán (Active)</option>
                  <option value="2" @selected(old('status') == 2)>Hết hàng (Out of Stock)</option>
                  <option value="3" @selected(old('status') == 3)>Ngừng bán tạm thời (Inactive)</option>
                  <option value="4" @selected(old('status') == 4)>Ngừng kinh doanh (Discontinued)</option>
                  <option value="5" @selected(old('status') == 5)>Lưu trữ (Archive)</option>
                </select>
              </div>

              {{-- Type --}}
              <div class="mb-3">
                <label class="form-label">Loại sản phẩm</label>
                <select name="type" class="form-select">
                  <option value="">-- Chọn loại --</option>
                  <option value="simple" @selected(old('type') == 'simple')>Đơn giản</option>
                  <option value="digital" @selected(old('type') == 'digital')>Kỹ thuật số</option>
                  <option value="bundle" @selected(old('type') == 'bundle')>Combo</option>
                </select>
              </div>
            </div>
          </div>

          {{-- Inventory --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-warehouse me-2 text-primary"></i>Kho hàng
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" placeholder="0" value="{{ old('quantity', 0) }}"
                  min="0" required>
              </div>
            </div>
          </div>

          {{-- Categories --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-folder me-2 text-primary"></i>Danh mục
            </div>
            <div class="card-body">
              <div class="category-list" style="max-height: 200px; overflow-y: auto;">
                @forelse($categories ?? [] as $cat)
                  <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" name="categories[]" value="{{ $cat->categoryId }}"
                      id="cat-{{ $cat->categoryId }}">
                    <label class="form-check-label" for="cat-{{ $cat->categoryId }}">
                      {{ $cat->title }}
                    </label>
                  </div>
                @empty
                  <p class="text-muted small mb-0">Chưa có danh mục nào</p>
                @endforelse
              </div>
            </div>
          </div>

          {{-- Tags --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-tags me-2 text-primary"></i>Tags
            </div>
            <div class="card-body">
              <input type="text" name="tags" class="form-control" placeholder="Nhập tags, phân cách bằng dấu phẩy"
                value="{{ old('tags') }}">
              <small class="text-muted">Ví dụ: gaming, bluetooth, chống ồn</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Form Actions --}}
      <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-times me-1"></i> Hủy
          </a>
          <div class="d-flex gap-2">
            <button type="submit" name="action" value="save_and_new" class="btn btn-outline-primary">
              <i class="fa fa-plus me-1"></i> Lưu & Thêm mới
            </button>
            <button type="submit" name="action" value="save" class="btn btn-primary">
              <i class="fa fa-save me-1"></i> Lưu sản phẩm
            </button>
          </div>
        </div>
      </div>

    </form>

  </div>
@endsection

@push('styles')
  <style>
    .upload-zone {
      border-style: dashed !important;
      border-color: var(--gray-300) !important;
      cursor: pointer;
      transition: all 0.2s;
    }

    .upload-zone:hover {
      border-color: var(--primary) !important;
      background: var(--primary-soft);
    }

    .upload-zone.dragover {
      border-color: var(--primary) !important;
      background: var(--primary-soft);
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Price calculation
      const priceInput = document.querySelector('input[name="price"]');
      const discountInput = document.querySelector('input[name="discount"]');
      const finalPriceEl = document.getElementById('final-price');

      function updateFinalPrice() {
        const price = parseFloat(priceInput?.value) || 0;
        const discount = parseFloat(discountInput?.value) || 0;
        const finalPrice = price * (1 - discount / 100);
        if (finalPriceEl) {
          finalPriceEl.textContent = new Intl.NumberFormat('vi-VN').format(finalPrice) + ' ₫';
        }
      }

      priceInput?.addEventListener('input', updateFinalPrice);
      discountInput?.addEventListener('input', updateFinalPrice);
      updateFinalPrice();

      // Image preview
      const thumbInput = document.getElementById('thumb-input');
      const imagePreview = document.getElementById('image-preview');
      const uploadZone = document.getElementById('upload-zone');

      thumbInput?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            imagePreview.querySelector('img').src = e.target.result;
            imagePreview.classList.remove('d-none');
            uploadZone.classList.add('d-none');
          };
          reader.readAsDataURL(file);
        }
      });

      // Upload zone click
      uploadZone?.addEventListener('click', function () {
        thumbInput?.click();
      });

      // Drag and drop
      uploadZone?.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.classList.add('dragover');
      });

      uploadZone?.addEventListener('dragleave', function () {
        this.classList.remove('dragover');
      });

      uploadZone?.addEventListener('drop', function (e) {
        e.preventDefault();
        this.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
          thumbInput.files = e.dataTransfer.files;
          thumbInput.dispatchEvent(new Event('change'));
        }
      });

      // Auto-generate slug
      const titleInput = document.querySelector('input[name="title"]');
      const slugInput = document.querySelector('input[name="slug"]');
      let slugManuallyEdited = false;

      slugInput?.addEventListener('input', function () {
        slugManuallyEdited = this.value.length > 0;
      });

      titleInput?.addEventListener('input', function () {
        if (!slugManuallyEdited) {
          const slug = this.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
          slugInput.value = slug;
        }
      });
    });
  </script>
@endpush