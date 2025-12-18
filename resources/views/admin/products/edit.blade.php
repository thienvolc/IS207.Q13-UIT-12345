@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm - Admin')
@section('page-title', 'Chỉnh sửa sản phẩm')

@php
  $id = $product->productId;
  $title = $product->title;
  $slug = $product->slug;
  $summary = $product->summary ?? '';
  $desc = $product->desc ?? '';
  $price = $product->price;
  $discount = $product->discount;
  $endsAt = $product->endsAt ?? null;
  $quantity = $product->quantity;
  $status = $product->status;
  $type = $product->type ?? '';
  $thumb = $product->thumb;
  $productCategories = $product->categories ?? [];
  $productTags = $product->tags ?? [];
@endphp

@section('content')
  <div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header">
      <div>
        <h1 class="page-title">Chỉnh sửa sản phẩm</h1>
        <p class="page-subtitle">{{ Str::limit($title, 60) }}</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ url('/san-pham/' . $slug) }}" class="btn btn-outline-secondary" target="_blank">
          <i class="fa fa-external-link-alt me-1"></i> Xem trên web
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
          <i class="fa fa-arrow-left me-1"></i> Quay lại
        </a>
      </div>
    </div>

    <form action="{{ route('admin.products.update', $id) }}" method="POST" enctype="multipart/form-data"
      id="product-form">
      @csrf
      @method('PUT')

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
                    placeholder="Nhập tên sản phẩm..." value="{{ old('title', $title) }}" required>
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
                      placeholder="ten-san-pham" value="{{ old('slug', $slug) }}">
                  </div>
                  @error('slug')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Summary --}}
                <div class="col-12">
                  <label class="form-label">Tóm tắt</label>
                  <input type="text" name="summary" class="form-control" placeholder="Mô tả ngắn về sản phẩm..."
                    value="{{ old('summary', $summary) }}" maxlength="255">
                </div>

                {{-- Description --}}
                <div class="col-12">
                  <label class="form-label">Mô tả chi tiết</label>
                  <textarea name="desc" class="form-control" rows="6"
                    placeholder="Mô tả chi tiết về sản phẩm...">{{ old('desc', $desc) }}</textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- Pricing --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-tag me-2 text-success"></i>Giá bán
            </div>
            <div class="card-body">
              <div class="row g-3">
                {{-- Price --}}
                <div class="col-md-4">
                  <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                      placeholder="0" value="{{ old('price', $price) }}" min="0" step="1000" required>
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
                      value="{{ old('discount', $discount) }}" min="0" max="100">
                    <span class="input-group-text">%</span>
                  </div>
                </div>

                {{-- Sale End Date --}}
                <div class="col-md-4">
                  <label class="form-label">Kết thúc giảm giá</label>
                  <input type="datetime-local" name="ends_at" class="form-control"
                    value="{{ old('ends_at', $endsAt ? \Carbon\Carbon::parse($endsAt)->format('Y-m-d\TH:i') : '') }}">
                </div>
              </div>

              {{-- Price Preview --}}
              <div class="mt-3 p-3 bg-light rounded">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-muted">Giá sau giảm:</span>
                  <span class="fs-5 fw-bold text-success" id="final-price">
                    {{ number_format($price * (1 - $discount / 100), 0, ',', '.') }} ₫
                  </span>
                </div>
              </div>
            </div>
          </div>

          {{-- Images --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-images me-2 text-info"></i>Hình ảnh
            </div>
            <div class="card-body">
              @include('admin.partials.image-upload', [
                'name' => 'thumb',
                'value' => old('thumb', $thumb),
                'label' => 'Hình đại diện',
                'folder' => 'products'
              ])
            </div>
          </div>

          {{-- Product Metas --}}
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span><i class="fa fa-list-alt me-2 text-secondary"></i>Thông số kỹ thuật</span>
              <button type="button" class="btn btn-sm btn-outline-primary" id="add-meta">
                <i class="fa fa-plus me-1"></i> Thêm
              </button>
            </div>
            <div class="card-body">
              <div id="metas-container">
                @forelse($product->metas ?? [] as $meta)
                  <div class="row g-2 mb-2 meta-row">
                    <div class="col-md-4">
                      <input type="text" name="metas[{{ $loop->index }}][key]" class="form-control"
                        placeholder="Tên thuộc tính" value="{{ $meta->key ?? '' }}">
                    </div>
                    <div class="col-md-7">
                      <input type="text" name="metas[{{ $loop->index }}][value]" class="form-control" placeholder="Giá trị"
                        value="{{ $meta->value ?? '' }}">
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-outline-danger w-100 remove-meta">
                        <i class="fa fa-times"></i>
                      </button>
                    </div>
                  </div>
                @empty
                  <p class="text-muted small mb-0" id="no-metas">Chưa có thông số nào. Click "Thêm" để bổ sung.</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>

        {{-- Right Column - Metadata --}}
        <div class="col-lg-4">
          {{-- Status & Type --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-cog me-2 text-secondary"></i>Thiết lập
            </div>
            <div class="card-body">
              {{-- Status --}}
              <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                  <option value="1" @selected(old('status', $status) == 1)>Đang bán (Active)</option>
                  <option value="2" @selected(old('status', $status) == 2)>Hết hàng (Out of Stock)</option>
                  <option value="3" @selected(old('status', $status) == 3)>Ngừng bán tạm thời (Inactive)</option>
                  <option value="4" @selected(old('status', $status) == 4)>Ngừng kinh doanh (Discontinued)</option>
                  <option value="5" @selected(old('status', $status) == 5)>Lưu trữ (Archive)</option>
                </select>
              </div>

              {{-- Type --}}
              <div class="mb-3">
                <label class="form-label">Loại sản phẩm</label>
                <select name="type" class="form-select">
                  <option value="">-- Chọn loại --</option>
                  <option value="simple" @selected(old('type', $type) == 'simple')>Đơn giản</option>
                  <option value="digital" @selected(old('type', $type) == 'digital')>Kỹ thuật số</option>
                  <option value="bundle" @selected(old('type', $type) == 'bundle')>Combo</option>
                </select>
              </div>

              {{-- Product ID --}}
              <div class="text-muted small">
                <i class="fa fa-hashtag me-1"></i> ID: {{ $id }}
              </div>
            </div>
          </div>

          {{-- Inventory --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-warehouse me-2 text-warning"></i>Kho hàng
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Số lượng tồn kho</label>
                <input type="number" name="quantity" class="form-control" placeholder="0"
                  value="{{ old('quantity', $quantity) }}" min="0">
              </div>

              @if($quantity <= 0)
                <div class="alert alert-danger py-2 mb-0">
                  <i class="fa fa-exclamation-triangle me-1"></i> Hết hàng!
                </div>
              @elseif($quantity < 10)
                <div class="alert alert-warning py-2 mb-0">
                  <i class="fa fa-exclamation-circle me-1"></i> Sắp hết hàng
                </div>
              @endif
            </div>
          </div>

          {{-- Categories --}}
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa fa-folder me-2 text-primary"></i>Danh mục
            </div>
            <div class="card-body">
              <div class="category-list" style="max-height: 200px; overflow-y: auto;">
                @php
                  $selectedCats = collect($productCategories)->pluck('categoryId', 'categoryId')->toArray();
                @endphp
                @forelse($categories ?? [] as $cat)
                  @php $catId = $cat->categoryId; @endphp
                  <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" name="categories[]" value="{{ $catId }}"
                      id="cat-{{ $catId }}" @checked(isset($selectedCats[$catId]))>
                    <label class="form-check-label" for="cat-{{ $catId }}">
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
              <i class="fa fa-tags me-2 text-info"></i>Tags
            </div>
            <div class="card-body">
              @php
                $tagString = collect($productTags)->pluck('name')->implode(', ');
              @endphp
              <input type="text" name="tags" class="form-control" placeholder="Nhập tags, phân cách bằng dấu phẩy"
                value="{{ old('tags', $tagString) }}">
              <small class="text-muted">Ví dụ: gaming, bluetooth, chống ồn</small>
            </div>
          </div>

          {{-- Delete Zone --}}
          <div class="card border-danger">
            <div class="card-header bg-danger-subtle text-danger">
              <i class="fa fa-exclamation-triangle me-2"></i>Vùng nguy hiểm
            </div>
            <div class="card-body">
              <p class="small text-muted mb-3">Xóa sản phẩm này vĩnh viễn. Hành động không thể hoàn tác.</p>
              <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal"
                data-bs-target="#deleteModal">
                <i class="fa fa-trash me-1"></i> Xóa sản phẩm
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Form Actions --}}
      <div class="card mt-4">
        <div class="card-body d-flex justify-content-between align-items-center">
          <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-times me-1"></i> Hủy
          </a>
          <div class="d-flex gap-2">
            <button type="submit" name="action" value="save_and_continue" class="btn btn-outline-primary">
              <i class="fa fa-save me-1"></i> Lưu & Tiếp tục sửa
            </button>
            <button type="submit" name="action" value="save" class="btn btn-primary">
              <i class="fa fa-check me-1"></i> Cập nhật
            </button>
          </div>
        </div>
      </div>

    </form>

  </div>

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
          <p class="mb-1">Bạn có chắc chắn muốn xóa sản phẩm:</p>
          <p class="fw-bold">{{ $title }}</p>
          <small class="text-muted">Hành động này không thể hoàn tác.</small>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
          <form action="{{ route('admin.products.destroy', $id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
              <i class="fa fa-trash me-1"></i> Xóa vĩnh viễn
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

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

      // Image preview
      const thumbInput = document.getElementById('thumb-input');
      const imagePreview = document.getElementById('image-preview');

      thumbInput?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            imagePreview.querySelector('img').src = e.target.result;
            imagePreview.classList.remove('d-none');
          };
          reader.readAsDataURL(file);
        }
      });

      // Add/Remove metas
      const metasContainer = document.getElementById('metas-container');
      const addMetaBtn = document.getElementById('add-meta');
      let metaIndex = {{ count($product->metas ?? []) }};

      addMetaBtn?.addEventListener('click', function () {
        document.getElementById('no-metas')?.remove();

        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 meta-row';
        row.innerHTML = `
                  <div class="col-md-4">
                      <input type="text" name="metas[${metaIndex}][key]" class="form-control" placeholder="Tên thuộc tính">
                  </div>
                  <div class="col-md-7">
                      <input type="text" name="metas[${metaIndex}][value]" class="form-control" placeholder="Giá trị">
                  </div>
                  <div class="col-md-1">
                      <button type="button" class="btn btn-outline-danger w-100 remove-meta">
                          <i class="fa fa-times"></i>
                      </button>
                  </div>
              `;
        metasContainer.appendChild(row);
        metaIndex++;
      });

      metasContainer?.addEventListener('click', function (e) {
        if (e.target.closest('.remove-meta')) {
          e.target.closest('.meta-row').remove();
        }
      });
    });
  </script>
@endpush