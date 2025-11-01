{{-- resources/views/pages/products/detail.blade.php --}}
@extends('layouts.app')

@section('title', $product['name'])

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <div class="mb-4">
        @include('partials.breadcrumb', [
            'items' => [
                ['name' => 'Trang chủ', 'url' => route('home')],
                ['name' => 'Sản phẩm', 'url' => route('products.index')],
            ],
            'current' => $product['name']
        ])
    </div>

    <div class="row g-4">
        <!-- Hình ảnh -->
        <div class="col-lg-5">
            <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" class="w-100 rounded" style="height: 420px; object-fit: contain; background: #f8f9fa;">
        </div>

        <!-- Thông tin -->
        <div class="col-lg-7">
            <h1 class="h3 fw-bold mb-3">{{ $product['name'] }}</h1>

            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="text-warning">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa{{ $i <= $product['rating'] ? 's' : 'r' }} fa-star"></i>
                    @endfor
                </div>
                <span class="text-muted small">({{ $product['reviews_count'] }} đánh giá)</span>
            </div>

            <div class="bg-light p-3 rounded mb-4">
                <span class="h2 text-danger fw-bold">{{ number_format($product['price_sale']) }}đ</span>
                @if($product['price_sale'] < $product['price'])
                    <del class="text-muted ms-2">{{ number_format($product['price']) }}đ</del>
                    <span class="badge bg-danger ms-2">-{{ $product['discount'] }}%</span>
                @endif
            </div>

            <div class="d-flex gap-2 mb-4">
                <button class="btn btn-primary btn-lg flex-grow-1">Thêm vào giỏ</button>
                <button class="btn btn-danger btn-lg">Mua ngay</button>
            </div>

            <div class="border-top pt-3 small text-success">
                <p><i class="fa-solid fa-check me-2"></i>Bảo hành chính hãng 12 tháng</p>
                <p><i class="fa-solid fa-truck me-2"></i>Giao hàng miễn phí</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-5">
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#desc">Mô tả</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#specs">Thông số</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="desc">{!! $product['description'] !!}</div>
            <div class="tab-pane fade" id="specs">
                <table class="table table-bordered small">
                    <tr><td>Màn hình</td><td>{{ $product['screen'] }}</td></tr>
                    <tr><td>CPU</td><td>{{ $product['cpu'] }}</td></tr>
                    <tr><td>RAM</td><td>{{ $product['ram'] }}</td></tr>
                    <tr><td>Bộ nhớ</td><td>{{ $product['storage'] }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Liên quan -->
    <div class="mt-5">
        <h3 class="h4 fw-bold mb-4">Sản phẩm liên quan</h3>
        <div class="row g-3">
            @foreach($related as $item)
                <div class="col-6 col-md-3">
                    @include('components.product-card', ['product' => $item])
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection