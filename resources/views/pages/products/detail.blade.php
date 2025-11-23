{{-- resources/views/pages/products/detail.blade.php --}}
<?php
/**
 * @var App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO $product
 * @var App\Domains\Common\DTOs\OffsetPageResponseDTO<App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO> $related
 * */
?>

@extends('layouts.app')

@section('title', $product->title)

@section('content')
    <div class="grid">
        <!-- Breadcrumb -->
        <div class="mb-4">
            @include('partials.breadcrumb', [
            'items' => [['name' => 'Sản phẩm', 'url' => route('products.index')],],
            'current' => $product->title
            ])
        </div>

        <div class="row g-4 g-lg-5">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-lg-5">
                <div class="product-detail-image sticky-top" style="top: 90px;">
                    <div class="product-detail-image__main bg-light rounded-3 p-4 mb-3">
                        <img src="{{ $product->thumb }}"
                             alt="{{ $product->title }}"
                             class="w-100 rounded"
                             style="height: 400px; object-fit: contain;">
                    </div>

                    {{-- Gallery thumbnails --}}
                    {{--                    @if(isset($product->images) && count($product->images) > 0)--}}
                    {{--                        <div class="product-detail-gallery d-flex gap-2">--}}
                    {{--                            @foreach($product->images as $img)--}}
                    {{--                                <div class="product-detail-gallery__item bg-light rounded p-2 cursor-pointer"--}}
                    {{--                                     style="width: 80px; height: 80px;">--}}
                    {{--                                    <img src="{{ $img }}" alt="" class="w-100 h-100" style="object-fit: contain;">--}}
                    {{--                                </div>--}}
                    {{--                            @endforeach--}}
                    {{--                        </div>--}}
                    {{--                    @endif--}}
                </div>
            </div>

            <!-- Product info -->
            <div class="col-lg-7">
                <div class="product-detail-info">
                    <!-- name -->
                    <h1 class="product-detail-title h2 fw-bold mb-3">{{ $product->title }}</h1>

                    <!-- price -->
                    <div class="product-detail-price bg-light p-4 rounded-3 mb-4">
                        <div class="d-flex align-items-baseline gap-3 mb-2">
                            <span class="h1 text-danger fw-bold mb-0">{{ number_format($product->price - $product->discount) }}đ</span>
                            @if($product->discount != 0)
                                <del class="h5 text-muted mb-0">{{ number_format($product->price) }}đ</del>
                                <span class="badge bg-danger fs-5">-{{ number_format($product->discount / $product->price * 100) }}%</span>
                            @endif
                        </div>
                        @if($product->discount != 0)
                            <p class="text-muted mb-0">
                                <i class="fa-solid fa-tag me-1"></i>
                                Tiết kiệm:
                                <strong class="text-danger">{{ number_format($product->discount) }}đ</strong>
                            </p>
                        @endif
                    </div>

                    <!-- Thông số nổi bật -->
                    {{--                    @if(isset($product->highlights))--}}
                    {{--                        <div class="product-detail-highlights mb-4">--}}
                    {{--                            <h3 class="fs-5 fw-bold mb-3">Thông số nổi bật:</h3>--}}
                    {{--                            <ul class="list-unstyled fs-5">--}}
                    {{--                                @foreach($product->highlights ?? [] as $highlight)--}}
                    {{--                                    <li class="mb-2">--}}
                    {{--                                        <i class="fa-solid fa-circle-check text-success me-2 fs-5"></i>--}}
                    {{--                                        {{ $highlight }}--}}
                    {{--                                    </li>--}}
                    {{--                                @endforeach--}}
                    {{--                            </ul>--}}
                    {{--                        </div>--}}
                    {{--                    @endif--}}

                    <!-- Actions -->
                    <div class="product-detail-actions mb-4">
                        {{-- ============================================ --}}
                        {{-- QUANTITY SELECTOR --}}
                        {{-- ============================================ --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <label class="fw-bold fs-5">Số lượng:</label>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary" type="button" id="decrease-qty">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="product-quantity" value="1"
                                       min="1" max="{{ $product->quantity }}" style="appearance: textfield;">
                                <button class="btn btn-outline-secondary" type="button" id="increase-qty">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            <span class="text-muted">
                            <i class="fa-solid fa-box"></i>
                            Còn <strong>{{ $product->quantity }}</strong> sản phẩm
                        </span>
                        </div>

                        <div class="d-flex gap-3 mb-3">
                            <button class="btn btn-primary btn-lg flex-grow-1 px-4 py-3 fs-5 add-to-cart"
                                    data-product-id="{{ $product->productId }}">
                                <i class="fa-solid fa-cart-plus me-2 fs-5"></i>
                                Thêm vào giỏ hàng
                            </button>
                            <button class="btn btn-danger btn-lg px-4 py-3 fs-5 buy-now"
                                    data-product-id="{{ $product->productId }}">
                                <i class="fa-solid fa-bolt me-2 fs-5"></i>
                                Mua ngay
                            </button>
                        </div>
                    </div>

                    <!-- Chính sách -->
                    <div class="product-detail-policies border rounded-3 p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-shield-check text-primary fs-2"></i>
                                    <div>
                                        <div class="fw-bold fs-5">Bảo hành chính hãng</div>
                                        <div class="text-muted fs-5">12 tháng toàn quốc</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-truck text-success fs-2"></i>
                                    <div>
                                        <div class="fw-bold fs-5">Giao hàng miễn phí</div>
                                        <div class="text-muted fs-5">Toàn quốc từ 500k</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-arrow-repeat text-info fs-2"></i>
                                    <div>
                                        <div class="fw-bold fs-5">Đổi trả trong 7 ngày</div>
                                        <div class="text-muted fs-5">Nếu sản phẩm lỗi</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-patch-check text-warning fs-2"></i>
                                    <div>
                                        <div class="fw-bold fs-5">Hàng chính hãng 100%</div>
                                        <div class="text-muted fs-5">Cam kết từ nhà sản xuất</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs: Mô tả, Thông số -->
        <div class="mt-5">
            <ul class="nav nav-tabs nav-fill border-bottom mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold fs-5" id="desc-tab" data-bs-toggle="tab"
                            data-bs-target="#desc" type="button">
                        <i class="bi bi-file-text me-2 fs-4"></i>Mô tả sản phẩm
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold fs-5" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs"
                            type="button">
                        <i class="bi bi-cpu me-2 fs-4"></i>Thông số kỹ thuật
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Mô tả -->
                <div class="tab-pane fade show active" id="desc" role="tabpanel">
                    <div class="product-description bg-white p-4 rounded fs-5">
                        {!! $product->summary ?? $product->desc ?? '<p class="text-muted">Chưa có mô tả chi tiết.</p>' !!}
                    </div>
                </div>

                <!-- Thông số kỹ thuật -->
                <div class="tab-pane fade" id="specs" role="tabpanel">
                    <div class="product-specs bg-white rounded">
                        {{-- ============================================ --}}
                        {{-- ✅ SPECS THẬT - Từ product_metas (Aiven Cloud) --}}
                        {{-- ============================================ --}}
                        <table class="table table-striped table-hover mb-0 fs-5">
                            <tbody>
                            @if(isset($product->meta))
                                @foreach($product->meta as $productMeta)
                                    @if($productMeta['content'] && $productMeta['content'] !== 'N/A')
                                        <tr>
                                            <td class="fw-bold" style="width: 30%;">
                                                @switch($productMeta['key'])
                                                    @case('Thương hiệu')
                                                        <i class="fa-solid fa-tag me-2 text-primary fs-4"></i>
                                                        @break
                                                    @case('Công suất')
                                                        <i class="fa-solid fa-bolt me-2 text-warning fs-4"></i>
                                                        @break
                                                    @case('Thời lượng pin')
                                                        <i class="fa-solid fa-battery-full me-2 text-success fs-4"></i>
                                                        @break
                                                    @case('Chống nước')
                                                        <i class="fa-solid fa-droplet me-2 text-info fs-4"></i>
                                                        @break
                                                    @case('Bluetooth')
                                                        <i class="fa-brands fa-bluetooth me-2 text-primary fs-4"></i>
                                                        @break
                                                    @case('Bảo hành')
                                                        <i class="fa-solid fa-shield-halved me-2 text-danger fs-4"></i>
                                                        @break
                                                    @default
                                                        <i class="fa-solid fa-circle-info me-2 text-secondary fs-4"></i>
                                                @endswitch
                                                {{ $productMeta['key'] }}
                                            </td>
                                            <td>{!! $productMeta['content'] !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">
                                        Chưa có thông số kỹ thuật chi tiết.
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sản phẩm liên quan -->
        @if(isset($related) && $related->count > 0)
            <div class="mt-5 pt-5 mb-5 border-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="h4 fw-bold mb-0">Sản phẩm tương tự</h3>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-danger fw-bold">
                        Xem tất cả <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="grid-row">
                    @foreach($related->data as $item)
                        <div class="grid__col-2-4 product-col">
                            @include('components.product-card', ['product' => $item])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Ẩn nút tăng giảm mặc định của input number trên mọi trình duyệt */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pages/detail.css') }}">
    @endpush

    @push('scripts')
        @vite('resources/js/detail.js')
    @endpush
@endsection
