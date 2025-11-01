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

        <!-- Horizontal Filter Bar -->
        <div class="filter-bar card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Filter Label -->
                    <div class="filter-bar__label fw-bold text-muted">
                        <i class="fa-solid fa-filter me-2"></i>Bộ lọc:
                    </div>

                    <!-- Price Filter -->
                    <div class="filter-bar__group d-flex align-items-center gap-2">
                        <span class="fw-bold text-muted">Giá:</span>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Dưới 10tr</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">10-20tr</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Trên 20tr</a>
                    </div>

                    <!-- Divider -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- Brand Filter -->
                    <div class="filter-bar__group d-flex align-items-center gap-2">
                        <span class="small fw-bold text-muted">Hãng:</span>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Apple</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Samsung</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Xiaomi</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">OPPO</a>
                    </div>

                    <!-- Divider -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- Sort Filter -->
                    <div class="filter-bar__group d-flex align-items-center gap-2">
                        <span class="small fw-bold text-muted">Sắp xếp:</span>
                        <select class="form-select form-select-sm" style="width: auto; min-width: 150px;">
                            <option>Mới nhất</option>
                            <option>Giá: Thấp → Cao</option>
                            <option>Giá: Cao → Thấp</option>
                            <option>Bán chạy</option>
                        </select>
                    </div>

                    <!-- Clear Filter -->
                    <div class="ms-auto">
                        <a href="#" class="btn btn-link btn-sm text-danger text-decoration-none">
                            <i class="fa-solid fa-rotate-left me-1"></i>Xóa bộ lọc
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count())
        <div class="grid-row">
            @foreach($products as $product)
                <div class="grid__col-3 product-col">
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