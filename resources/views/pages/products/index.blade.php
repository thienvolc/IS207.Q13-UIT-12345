{{-- resources/views/pages/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tất cả sản phẩm')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <div class="mb-4">
        @include('partials.breadcrumb', [
            'items' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
            ],
            'current' => 'Sản phẩm'
        ])
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Bộ lọc</h5>
                    <div class="mb-3">
                        <h6 class="small fw-bold text-uppercase text-muted">Giá</h6>
                        <a href="#" class="btn btn-outline-secondary btn-sm me-1 mb-1">Dưới 10tr</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm me-1 mb-1">10-20tr</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm mb-1">Trên 20tr</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 fw-bold mb-0">Tất cả sản phẩm</h1>
                <select class="form-select w-auto form-select-sm">
                    <option>Mới nhất</option>
                    <option>Giá: Thấp → Cao</option>
                    <option>Giá: Cao → Thấp</option>
                </select>
            </div>

            @if($products->count())
                <div class="row g-3 g-md-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4 col-lg-4">
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
    </div>
</div>
@endsection