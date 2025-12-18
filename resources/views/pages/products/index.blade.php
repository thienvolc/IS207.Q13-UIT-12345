{{-- resources/views/pages/products/index.blade.php --}}
<?php
/**
 * @var App\Domains\Common\DTOs\OffsetPageResponseDTO<App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO> $searchProductsResponse
 * @var string|null $searchQuery
 */
?>


@extends('layouts.app')

@section('title', 'PinkCapy - Sản phẩm')

@section('content')
<div class="grid">

    {{-- ===========================
            Breadcrumb
        =========================== --}}
    <div class="mb-4">
        @include('partials.breadcrumb', [
        'items' => [],
        'current' => 'Sản phẩm'
        ])
    </div>

    {{-- ===========================
            Header & Filter Bar
        =========================== --}}
    <div class="products-header-wrapper mb-4">
        <!-- Search info -->
        @if(isset($searchQuery) && $searchQuery)
        <h1 class="title-lg fw-bold mb-3">
            Kết quả tìm kiếm cho: <span class="text-primary">"{{ $searchQuery }}"</span>
        </h1>
        <p class="text-muted">Tìm thấy {{ $searchProductsResponse->count }} sản phẩm</p>
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
                            <input type="hidden" id="price_min_input" name="price_min"
                                value="{{ request('price_min') }}">
                            <input type="hidden" id="price_max_input" name="price_max"
                                value="{{ request('price_max') }}">
                        </div>

                        <!-- Divider -->
                        <div class="vr d-none d-md-block"></div>

                        <!-- Sort Filter -->
                        <div class="filter-bar__group d-flex align-items-center gap-2">
                            <span class="small fw-bold text-muted">Sắp xếp:</span>
                            <select
                                class="form-select form-select-sm" name="sort" onchange="this.form.submit()"
                                style="width: auto; min-width: 150px;">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                    Mới nhất
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                    Giá: Thấp → Cao
                                </option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                    Giá: Cao → Thấp
                                </option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                                    Tên A-Z
                                </option>
                            </select>
                        </div>

                        <!-- Product Count -->
                        <div class="ms-auto text-muted">
                            <i class="fa-solid fa-box"></i>
                            <strong>{{ $searchProductsResponse->count }}</strong> sản phẩm
                        </div>

                        <!-- Clear Filter -->
                        @if(request()->hasAny(['category', 'price_min', 'price_max', 'sort']))
                        <div>
                            <a href="{{ route('products.index') }}"
                                class="btn btn-link btn-sm text-danger text-decoration-none">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Xóa bộ lọc
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($searchProductsResponse->count)
    <div class="grid-row">
        @foreach($searchProductsResponse->data as $product)
        <div class="grid__col-2-4 product-col">
            @include('components.product-card', ['product' => $product])
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($searchProductsResponse->total > $searchProductsResponse->limit)
    <div class="d-flex justify-content-center my-5">
        <nav aria-label="Product pagination">
            <ul class="pagination">
                @php
                $currentPage = (int) ceil(($searchProductsResponse->offset + 1) / $searchProductsResponse->limit);
                $totalPages = (int) ceil($searchProductsResponse->total / $searchProductsResponse->limit);
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                @endphp

                {{-- Previous button --}}
                @if($currentPage > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ route('products.index', array_merge(request()->except(['offset']), ['offset' => ($currentPage - 2) * $searchProductsResponse->limit])) }}">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa-solid fa-chevron-left"></i></span>
                </li>
                @endif

                {{-- First page --}}
                @if($startPage > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ route('products.index', array_merge(request()->except(['offset']), ['offset' => 0])) }}">1</a>
                </li>
                @if($startPage > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                @endif

                {{-- Page numbers --}}
                @for($i = $startPage; $i <= $endPage; $i++)
                    @if($i==$currentPage)
                    <li class="page-item active" aria-current="page">
                    <span class="page-link">{{ $i }}</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ route('products.index', array_merge(request()->except(['offset']), ['offset' => ($i - 1) * $searchProductsResponse->limit])) }}">{{ $i }}</a>
                    </li>
                    @endif
                    @endfor

                    {{-- Last page --}}
                    @if($endPage < $totalPages)
                        @if($endPage < $totalPages - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ route('products.index', array_merge(request()->except(['offset']), ['offset' => ($totalPages - 1) * $searchProductsResponse->limit])) }}">{{ $totalPages }}</a>
                        </li>
                        @endif

                        {{-- Next button --}}
                        @if($currentPage < $totalPages)
                            <li class="page-item">
                            <a class="page-link" href="{{ route('products.index', array_merge(request()->except(['offset']), ['offset' => $currentPage * $searchProductsResponse->limit])) }}">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fa-solid fa-chevron-right"></i></span>
                            </li>
                            @endif
            </ul>
        </nav>
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