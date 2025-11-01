{{-- resources/views/components/product-card.blade.php --}}
<div class="product-card border rounded overflow-hidden bg-white shadow-sm hover:shadow-md transition-all duration-300 h-100 d-flex flex-column">
    <a href="{{ route('products.show', $product['slug']) }}" class="text-decoration-none text-dark flex-grow-1">
        <div class="position-relative bg-light p-3">
            <img 
                src="{{ $product['thumbnail'] }}" 
                alt="{{ $product['name'] }}"
                class="w-100 h-auto"
                style="height: 180px; object-fit: contain;"
                loading="lazy"
            >

            @if($product['discount'] > 0)
                <span class="badge bg-danger position-absolute top-0 start-0 m-2 fw-bold">
                    -{{ $product['discount'] }}%
                </span>
            @endif

            @if($product['is_new'] ?? false)
                <span class="badge bg-success position-absolute top-0 end-0 m-2 fw-bold">Mới</span>
            @endif
        </div>

        <div class="p-3 flex-grow-1 d-flex flex-column">
            <h3 class="h6 fw-bold mb-2 line-clamp-2 text-dark">
                {{ $product['name'] }}
            </h3>

            <div class="d-flex align-items-center gap-1 mb-2 text-warning small">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa{{ $i <= ($product['rating'] ?? 0) ? 's' : 'r' }} fa-star"></i>
                @endfor
                <span class="text-muted ms-1">({{ $product['reviews_count'] ?? 0 }})</span>
            </div>

            <div class="mt-auto">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="h5 text-danger fw-bold mb-0">
                        {{ number_format($product['price_sale']) }}đ
                    </span>
                    @if($product['price_sale'] < $product['price'])
                        <del class="text-muted small">{{ number_format($product['price']) }}đ</del>
                    @endif
                </div>
            </div>
        </div>
    </a>

    <div class="p-3 pt-0">
        <button class="btn btn-primary w-100 btn-sm add-to-cart">
            <i class="fa-solid fa-cart-plus me-1"></i>
            Thêm vào giỏ
        </button>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>