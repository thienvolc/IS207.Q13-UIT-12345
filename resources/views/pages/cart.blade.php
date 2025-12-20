@extends('layouts.app')

@section('title', 'PinkCapy - Giỏ hàng')

@section('content')
<div class="cart-page-wrapper mb-5">
    <div class="grid">
        <!-- Breadcrumb -->
        <div class="mb-4">
            @include('partials.breadcrumb', [
            'items' => [],
            'current' => 'Giỏ hàng'
            ])
        </div>

        <div class="cart-header mb-4">
            <h1 class="cart-title">
                <i class="bi bi-bag"></i> Giỏ hàng của bạn
            </h1>
            <p class="cart-subtitle">Bạn có <strong id="cart-count">{{ count($cart->items) }}</strong> sản phẩm trong giỏ hàng</p>
        </div>

        @if(count($cart->items) === 0)
        <!-- Empty Cart State -->
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc;"></i>
            <h3 class="mt-3">Giỏ hàng trống</h3>
            <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-bag-plus"></i> Mua sắm ngay
            </a>
        </div>
        @else
        <!-- Cart Content -->
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items-card">
                    <div id="cart-items-list">
                        @foreach($cart->items as $item)
                        @php
                            $subtotal = $item->price * $item->quantity;
                        @endphp
                        <div class="cart-item" data-item-id="{{ $item->itemId }}" data-product-id="{{ $item->productId }}">
                            <div class="cart-item-checkbox">
                                <input type="checkbox" class="form-check-input item-checkbox" checked>
                            </div>
                            <div class="cart-item-image">
                                <a href="{{ route('products.show', $item->productSlug ?? 'product') }}">
                                    @php
                                        $imageUrl = $item->productImage 
                                            ? 'https://broad-snowflake-e396.ttt2042005.workers.dev/proxy?img=' . $item->productImage 
                                            : '/img/placeholder.png';
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $item->productName }}">
                                </a>
                            </div>
                            <div class="cart-item-info  ">
                                <h4 class="cart-item-name">
                                    <a href="{{ route('products.show', $item->productSlug ?? 'product') }}">{{ $item->productName ?? 'Sản phẩm #' . $item->productId }}</a>
                                </h4>
                                <div class="cart-item-price">
                                    @php
                                        $netPrice = $item->price - $item->discount;
                                    @endphp
                                    <span class="price-current">{{ number_format($netPrice, 0, ',', '.') }}₫</span>
                                    @if($item->discount > 0)
                                    <span class="price-original text-muted text-decoration-line-through me-2">{{ number_format($item->price, 0, ',', '.') }}₫</span>
                                    <span class="badge bg-danger">Giảm {{ number_format($item->discount, 0, ',', '.') }}₫</span>
                                    @endif
                                </div>
                            </div>
                            <div class="cart-item-quantity">
                                <button class="qty-btn qty-minus" onclick="updateQuantity({{ $item->itemId }}, {{ $item->productId }}, {{ $item->quantity - 1 }})">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="qty-input" value="{{ $item->quantity }}" min="1" max="99" 
                                       onchange="updateQuantity({{ $item->itemId }}, {{ $item->productId }}, this.value)">
                                <button class="qty-btn qty-plus" onclick="updateQuantity({{ $item->itemId }}, {{ $item->productId }}, {{ $item->quantity + 1 }})">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <div class="cart-item-total">
                                <span class="item-total-price">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="cart-item-actions">
                                <button class="btn-icon btn-delete" title="Xóa" onclick="removeItem({{ $item->itemId }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Cart Actions -->
                    <div class="cart-actions">
                        <div class="cart-select-all">
                            <input type="checkbox" class="form-check-input" id="selectAll" checked>
                            <label for="selectAll">Chọn tất cả (<span id="select-all-count">{{ count($cart->items) }}</span>)</label>
                        </div>
                        <button class="btn-text btn-clear-cart" id="btn-clear-cart" onclick="clearCart()">
                            <i class="bi bi-x-circle"></i> Xóa tất cả
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary-card">
                    <h3 class="summary-title">Thông tin đơn hàng</h3>

                    <!-- Voucher -->
                    <div class="voucher-section">
                        <div class="voucher-input-group">
                            <input type="text" class="form-control" placeholder="Nhập mã giảm giá" id="voucher-code">
                            <button class="btn btn-apply" id="btn-apply-voucher">Áp dụng</button>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="price-details">
                        <div class="price-row">
                            <span>Tạm tính (<span id="summary-items-count">{{ count($cart->items) }}</span> sản phẩm)</span>
                            <span class="price-value" id="subtotal">{{ number_format($cart->totalPrice + $cart->totalDiscount, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="price-row">
                            <span>Giảm giá</span>
                            <span class="price-value text-danger" id="discount">-{{ number_format($cart->totalDiscount, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="price-row">
                            <span>Phí vận chuyển</span>
                            <span class="price-value" id="shipping-fee">Tính khi thanh toán</span>
                        </div>
                        <div class="price-row price-total">
                            <span>Tổng cộng</span>
                            <span class="price-value" id="total">{{ number_format($cart->totalPrice, 0, ',', '.') }}₫</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout.page') }}" class="btn-checkout">
                        Tiến hành thanh toán
                    </a>

                    <!-- Benefits -->
                    <div class="cart-benefits">
                        <div class="benefit-item">
                            <i class="bi bi-shield-check"></i>
                            <span>Bảo hành chính hãng</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-truck"></i>
                            <span>Miễn phí vận chuyển từ 500K</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-arrow-clockwise"></i>
                            <span>Đổi trả trong 7 ngày</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Shopping -->
            <div class="col-12 mt-4">
                <a href="{{ route('products.index') }}" class="btn-continue-shopping">
                    <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    
    async function updateQuantity(itemId, productId, newQty) {
        if (newQty < 1) {
            removeItem(itemId);
            return;
        }
        
        try {
            const url = '{{ route("cart.api.update", ["id" => ":id"]) }}'.replace(':id', itemId);
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    quantity: parseInt(newQty)
                })
            });
            
            if (response.ok) {
                location.reload();
            } else {
                const data = await response.json();
                alert(data.message || 'Không thể cập nhật số lượng');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật số lượng');
        }
    }
    
    async function removeItem(itemId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
        
        try {
            const url = '{{ route("cart.api.remove", ["id" => ":id"]) }}'.replace(':id', itemId);
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    async function clearCart() {
        if (!confirm('Bạn có chắc muốn xóa tất cả sản phẩm?')) return;
        
        try {
            const response = await fetch('{{ route("cart.api.clear") }}', {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>
@endpush
@endsection
