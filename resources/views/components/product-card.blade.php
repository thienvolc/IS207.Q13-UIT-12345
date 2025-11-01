{{-- resources/views/components/product-card.blade.php --}}
<div class="product-item">
    @if($product['discount'] > 0)
        <span class="product-item__badge">-{{ $product['discount'] }}%</span>
    @endif

    <div class="product-item__category">
        <a href="#">{{ $product['category'] ?? 'Điện thoại' }}</a>
    </div>

    <h5 class="product-item__title">
        <a href="{{ route('products.show', $product['slug']) }}">
            {{ $product['name'] }}
        </a>
    </h5>

    <div class="product-item__image">
        <a href="{{ route('products.show', $product['slug']) }}">
            <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" class="img-fluid">
        </a>
    </div>

    <div class="product-item__footer">
        <div class="product-item__price">
            @if($product['price_sale'] < $product['price'])
                <span class="price-sale">{{ number_format($product['price_sale']) }}đ</span>
                <span class="price-old">{{ number_format($product['price']) }}đ</span>
            @else
                <span class="price">{{ number_format($product['price_sale']) }}đ</span>
            @endif
        </div>

        <div class="product-item__btn-list">
            <button class="product-item__btn-item btn-cart">
                <i class="bi bi-cart-plus"></i>
            </button>
        </div>

        <div class="product-item__hover-actions">
            <button class="product-item__btn-item btn-compare">
                <i class="bi bi-arrow-left-right"></i>
            </button>
            <button class="product-item__btn-item btn-wishlist">
                <i class="bi bi-heart"></i>
            </button>
        </div>
    </div>
</div>