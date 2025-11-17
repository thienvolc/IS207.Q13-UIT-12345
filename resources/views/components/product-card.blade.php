{{-- resources/views/components/product-card.blade.php --}}
<div class="product-item" data-product-id="{{ $product['product_id'] ?? '' }}">
    @if($product['discount'] > 0)
    <span class="product-item__badge">-{{ $product['discount'] }}%</span>
    @endif

    <div class="product-item__image">
        <a href="{{ route('products.show', $product['slug']) }}">
            <img src="{{ $product['thumb'] ?? '' }}" alt="{{ $product['title'] ?? '' }}" class="img-fluid">
        </a>
    </div>

    <div class="product-item__content">
        <h5 class="product-item__title">
            <a href="{{ route('products.show', $product['slug']) }}">
                {{ $product['title'] ?? '' }}
            </a>
        </h5>

        <div class="d-flex align-items-center justify-content-between">
            <div class="product-item__price mb-0">
                <span class="price">{{ number_format($product['price']) }}đ</span>
            </div>
            <button class="btn-add-cart ms-3" title="Thêm vào giỏ" style="opacity:1; visibility:visible;">
                <i class="bi bi-cart-plus"></i>
            </button>
        </div>
    </div>
</div>