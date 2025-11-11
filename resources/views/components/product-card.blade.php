{{-- resources/views/components/product-card.blade.php --}}
<div class="product-item" data-product-id="{{ $product['id'] }}">
    @if($product['discount'] > 0)
    <span class="product-item__badge">-{{ $product['discount'] }}%</span>
    @endif

    <div class="product-item__image">
        <a href="{{ route('products.show', $product['slug']) }}">
            <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}" class="img-fluid">
        </a>
        <button class="btn-add-floating" title="Thêm vào giỏ">
            <i class="bi bi-cart-plus"></i>
        </button>
    </div>

    <div class="product-item__content">
        <h5 class="product-item__title">
            <a href="{{ route('products.show', $product['slug']) }}">
                {{ $product['name'] }}
            </a>
        </h5>

        <div class="product-item__price">
            @if($product['price_sale'] < $product['price'])
                <span class="price-sale">{{ number_format($product['price_sale']) }}đ</span>
                <span class="price-old">{{ number_format($product['price']) }}đ</span>
                @else
                <span class="price">{{ number_format($product['price_sale']) }}đ</span>
                @endif
        </div>

        <div class="product-item__actions">
            <button class="btn-icon btn-add-cart" title="Thêm vào giỏ">
                <i class="bi bi-cart-plus"></i>
            </button>
            <button class="btn-icon btn-wishlist" title="Thích">
                <i class="bi bi-heart"></i>
            </button>
            <button class="btn-icon btn-compare" title="So sánh">
                <i class="bi bi-arrow-left-right"></i>
            </button>
        </div>
    </div>
</div>