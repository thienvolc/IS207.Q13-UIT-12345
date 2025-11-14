{{-- resources/views/pages/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tất cả sản phẩm')

@section('content')
<div class="grid">
    <!-- Breadcrumb -->
    <div class="mb-4">
        @include('partials.breadcrumb', [
        'items' => [],
        'current' => 'Sản phẩm'
        ])
    </div>

    <!-- Header & Filter Bar -->
    <div class="products-header-wrapper mb-4">
        <h1 class="title-lg fw-bold mb-3">Tất cả sản phẩm</h1>

        <!-- Filter Bar -->
        <div class="filter-bar card border-0 shadow-sm">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <!-- Filter Label -->
                        <div class="filter-bar__label fw-bold text-muted">
                            <i class="fa-solid fa-filter me-2"></i>Bộ lọc:
                        </div>

                        <!-- Category Filter -->
                        <div class="filter-bar__group d-flex align-items-center gap-2">
                            <span class="fw-bold text-muted">Danh mục:</span>
                            <select class="form-select form-select-sm" name="category" onchange="this.form.submit()" style="width: auto; min-width: 150px;">
                                <option value="">Tất cả</option>
                                <option value="tai-nghe" {{ request('category') == 'tai-nghe' ? 'selected' : '' }}>Tai nghe</option>
                                <option value="dong-ho" {{ request('category') == 'dong-ho' ? 'selected' : '' }}>Đồng hồ thông minh</option>
                                <option value="phu-kien" {{ request('category') == 'phu-kien' ? 'selected' : '' }}>Phụ kiện</option>
                            </select>
                        </div>

                        <!-- Divider -->
                        <div class="vr d-none d-md-block"></div>

                        <!-- Price Filter -->
                        <div class="filter-bar__group d-flex align-items-center gap-2">
                            <span class="fw-bold text-muted">Giá:</span>
                            <button type="button"
                                onclick="document.getElementById('price_min_input').value=''; document.getElementById('price_max_input').value='1000000'; this.form.submit();"
                                class="btn btn-outline-secondary btn-sm {{ request('price_max') == '1000000' && !request('price_min') ? 'active' : '' }}">
                                Dưới 1tr
                            </button>
                            <button type="button"
                                onclick="document.getElementById('price_min_input').value='1000000'; document.getElementById('price_max_input').value='3000000'; this.form.submit();"
                                class="btn btn-outline-secondary btn-sm {{ request('price_min') == '1000000' && request('price_max') == '3000000' ? 'active' : '' }}">
                                1-3tr
                            </button>
                            <button type="button"
                                onclick="document.getElementById('price_min_input').value='3000000'; document.getElementById('price_max_input').value='5000000'; this.form.submit();"
                                class="btn btn-outline-secondary btn-sm {{ request('price_min') == '3000000' && request('price_max') == '5000000' ? 'active' : '' }}">
                                3-5tr
                            </button>
                            <button type="button"
                                onclick="document.getElementById('price_min_input').value='5000000'; document.getElementById('price_max_input').value=''; this.form.submit();"
                                class="btn btn-outline-secondary btn-sm {{ request('price_min') == '5000000' && !request('price_max') ? 'active' : '' }}">
                                Trên 5tr
                            </button>
                            <input type="hidden" id="price_min_input" name="price_min" value="{{ request('price_min') }}">
                            <input type="hidden" id="price_max_input" name="price_max" value="{{ request('price_max') }}">
                        </div>

                        <!-- Divider -->
                        <div class="vr d-none d-md-block"></div>

                        <!-- Sort Filter -->
                        <div class="filter-bar__group d-flex align-items-center gap-2">
                            <span class="small fw-bold text-muted">Sắp xếp:</span>
                            <select class="form-select form-select-sm" name="sort" onchange="this.form.submit()" style="width: auto; min-width: 150px;">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            </select>
                        </div>

                        <!-- Product Count -->
                        <div class="ms-auto text-muted">
                            <i class="fa-solid fa-box"></i>
                            <strong>
                                {{ count($products) }}
                            </strong> sản phẩm
                        </div>

                        <!-- Clear Filter -->
                        @if(request()->hasAny(['category', 'price_min', 'price_max', 'sort']))
                        <div>
                            <a href="{{ route('products.index') }}" class="btn btn-link btn-sm text-danger text-decoration-none">
                                <i class="fa-solid fa-rotate-left me-1"></i>Xóa bộ lọc
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if(count($products))
    <div class="grid-row">
        @foreach($products as $product)
        <div class="grid__col-2-4 product-col">
            @include('components.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>

    <!-- Không có phân trang khi trả về mảng -->
    @else
    <p class="text-center text-muted py-5">Không có sản phẩm nào.</p>
    @endif
</div>

@push('styles')
@endpush
@endsection