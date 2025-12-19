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
        <h1 class="title-lg fw-bold mb-3 text-center">TẤT CẢ SẢN PHẨM</h1>
        @endif

        <!-- Filter Bar -->
        <div class="filter-wrapper">
            <h2 class="filter-label-header">Chọn theo tiêu chí</h2>

            <!-- Row 1: Main Button & Logic Filters -->
            <div class="filter-chips-list">
                <!-- Bộ lọc Main Button (Visual only or toggle) -->
                <button class="btn-filter-main">
                    <i class="fa-solid fa-filter"></i> Bộ lọc
                </button>

                <!-- Dynamic Price Filters (Chips) -->
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
                        $activeClass = $isActive ? 'active' : '';
                    @endphp
                    <button type="button" onclick="setPriceFilter('{{ $min }}', '{{ $max }}')"
                        class="btn-filter-chip no-arrow {{ $activeClass }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Row 2: Sort & Other Options (Static placeholders + Real Sort) -->
            <!-- Row 2: Sort Options (New Design) -->
            <div class="sort-bar-row">
                <div class="sort-label">Sắp xếp theo</div>
                <div class="sort-chips-group">
                    <!-- Phổ biến (Default/Newest for now) -->
                    <button type="button" onclick="setSortFilter('newest')"
                         class="btn-sort-chip {{ request('sort', 'newest') == 'newest' ? 'active' : '' }}">
                        <i class="fa-regular fa-star"></i> Phổ biến
                    </button>

                    <!-- Khuyến mãi HOT (Placeholder logic) -->
                    <button type="button" class="btn-sort-chip">
                        <i class="fa-solid fa-percent"></i> Khuyến mãi HOT
                    </button>
                    
                    <!-- Price Low-High -->
                    <button type="button" onclick="setSortFilter('price_asc')"
                        class="btn-sort-chip {{ request('sort') == 'price_asc' ? 'active' : '' }}">
                        <i class="fa-solid fa-arrow-down-short-wide"></i> Giá Thấp - Cao
                    </button>

                    <!-- Price High-Low -->
                    <button type="button" onclick="setSortFilter('price_desc')"
                        class="btn-sort-chip {{ request('sort') == 'price_desc' ? 'active' : '' }}">
                        <i class="fa-solid fa-arrow-down-wide-short"></i> Giá Cao - Thấp
                    </button>
                </div>
            </div>
            

            
        </div>
        
        <!-- Hidden Form for Logic Submission -->
        <form method="GET" action="{{ route('products.index') }}" id="filter-form" class="d-none">
             @if(isset($searchQuery) && $searchQuery)
            <input type="hidden" name="search" value="{{ $searchQuery }}">
            @endif
             @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="hidden" id="price_min_input" name="price_min" value="{{ request('price_min') }}">
            <input type="hidden" id="price_max_input" name="price_max" value="{{ request('price_max') }}">
            <input type="hidden" id="hiddenSort" name="sort" value="{{ request('sort', 'newest') }}">
        </form>

        <script>
            function setSortFilter(value) {
                document.getElementById('hiddenSort').value = value;
                document.getElementById('filter-form').submit();
            }
        </script>
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