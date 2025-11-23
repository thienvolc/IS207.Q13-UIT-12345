<?php
/** @var App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO $product */
?>

{{-- Product card --}}
<div class="product-item" data-product-id="{{ $product->productId }}">

    {{-- Discount hint --}}
    @if($product->discount != 0)
        <span class="product-item__badge">-{{  number_format($product->discount / $product->price * 100) }}%</span>
    @endif

    {{-- Thumbnail --}}
    <div class="product-item__image">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ $product->thumb }}" alt="{{ $product->title }}" class="img-fluid">
        </a>
    </div>

    <div class="product-item__content">
        {{-- Name --}}
        <h5 class="product-item__title">
            <a href="{{ route('products.show', $product->slug) }}">
                {{ $product->title }}
            </a>
        </h5>

        <div class="d-flex align-items-center justify-content-between">
            {{-- Price --}}
            <div class="product-item__price mb-0">
                @if($product->discount != 0)
                    <del class="h5 text-muted mb-0">{{ number_format($product->price) }}đ</del>
                @endif
                <span class="price">{{ number_format($product->price - $product->discount) }}đ</span>
            </div>

            {{-- Button add to cart --}}
            <button class="btn-add-cart ms-3" title="Thêm vào giỏ" style="opacity:1; visibility:visible;">
                <i class="bi bi-cart-plus"></i>
            </button>
        </div>
    </div>
</div>
