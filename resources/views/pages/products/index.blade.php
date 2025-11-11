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

        {{-- ============================================ --}}
        {{-- ✅ SEARCH & FILTER - Hoạt động với query params --}}
        {{-- ============================================ --}}

        <!-- Search Box -->
        <form method="GET" action="{{ route('products.index') }}" class="mb-3">
            <div class="input-group input-group-lg">
                <input type="text"
                    class="form-control"
                    name="q"
                    placeholder="Tìm kiếm sản phẩm..."
                    value="{{ request('q') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>

        <!-- Horizontal Filter Bar -->
        <div class="filter-bar card border-0 shadow-sm">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <!-- Filter Label -->
                        <div class="filter-bar__label fw-bold text-muted">
                            <i class="fa-solid fa-filter me-2"></i>Bộ lọc:
                        </div>

                        <!-- Preserve search query -->
                        @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <!-- Price Filter -->
                        <div class="filter-bar__group d-flex align-items-center gap-2">
                            <span class="fw-bold text-muted">Giá:</span>
                            <button type="submit" name="price_max" value="10000000"
                                class="btn btn-outline-secondary btn-sm {{ request('price_max') == '10000000' ? 'active' : '' }}">
                                Dưới 10tr
                            </button>
                            <button type="submit"
                                onclick="this.form.price_min.value='10000000'; this.form.price_max.value='20000000';"
                                class="btn btn-outline-secondary btn-sm {{ request('price_min') == '10000000' ? 'active' : '' }}">
                                10-20tr
                            </button>
                            <button type="submit" name="price_min" value="20000000"
                                class="btn btn-outline-secondary btn-sm {{ request('price_min') == '20000000' ? 'active' : '' }}">
                                Trên 20tr
                            </button>
                            <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                            <input type="hidden" name="price_max" value="{{ request('price_max') }}">
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
                            <strong>{{ $products->total() }}</strong> sản phẩm
                        </div>

                        <!-- Clear Filter -->
                        @if(request()->hasAny(['q', 'category', 'price_min', 'price_max', 'sort']))
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
    @if($products->count())
    <div class="grid-row">
        @foreach($products as $product)
        <div class="grid__col-2-4 product-col">
            @include('components.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>

    <div class="mt-5">
        {{ $products->links('components.pagination') }}
    </div>
    @else
    <p class="text-center text-muted py-5">Không có sản phẩm nào.</p>
    @endif
</div>

@push('styles')
@endpush
@endsection