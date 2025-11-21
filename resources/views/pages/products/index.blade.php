{{-- resources/views/pages/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'PinkCapy - Sản phẩm')

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
        @if(isset($searchQuery) && $searchQuery)
        <h1 class="title-lg fw-bold mb-3">
            Kết quả tìm kiếm cho: <span class="text-primary">"{{ $searchQuery }}"</span>
        </h1>
        <p class="text-muted">Tìm thấy {{ count($products) }} sản phẩm</p>
        @else
        <h1 class="title-lg fw-bold mb-3">Tất cả sản phẩm</h1>
        @endif

        <!-- Filter Bar -->
        <div class="filter-bar card border-0 shadow-sm">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    <!-- Hidden inputs to preserve filters -->
                    @if(isset($searchQuery) && $searchQuery)
                    <input type="hidden" name="search" value="{{ $searchQuery }}">
                    @endif
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <!-- Filter Label -->
                        <div class="filter-bar__label fw-bold text-muted">
                            <i class="fa-solid fa-filter me-2"></i>Bộ lọc:
                        </div>

                        <!-- Price Filter -->
                        <div class="filter-bar__group d-flex align-items-center gap-2">
                            <span class="fw-bold text-muted">Giá:</span>
                            @php
                            $priceRanges = [
                            ['', '1000000', 'Dưới 1tr'],
                            ['1000000', '3000000', '1-3tr'],
                            ['3000000', '5000000', '3-5tr'],
                            ['5000000', '', 'Trên 5tr']
                            ];
                            @endphp
                            @foreach($priceRanges as [$min, $max, $label])
                            @php
                            $isActive = request('price_min') == $min && request('price_max') == $max;
                            @endphp
                            <button type="button" onclick="setPriceFilter('{{ $min }}', '{{ $max }}')"
                                class="btn btn-outline-secondary btn-sm {{ $isActive ? 'active' : '' }}">
                                {{ $label }}
                            </button>
                            @endforeach
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

    <!-- Nút Xem thêm nếu còn sản phẩm -->
    @if(isset($hasMore) && $hasMore)
    <div class="text-center my-4">
        <form method="GET" action="{{ route('products.index') }}">
            @foreach(request()->except(['offset']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="hidden" name="offset" value="{{ $offset + $limit }}">
            <input type="hidden" name="limit" value="{{ $limit }}">
            <button type="submit" class="btn-pagination">Xem thêm</button>
        </form>
    </div>
    @endif
    @else
    <p class="text-center text-uppercase py-5">Không có sản phẩm nào.</p>
    @endif
</div>

@push('scripts')
<script>
    function setPriceFilter(min, max) {
        document.getElementById('price_min_input').value = min;
        document.getElementById('price_max_input').value = max;
        document.getElementById('filter-form').submit();
    }
</script>
@endpush
@endsection