@extends('layouts.app')
@section('title', 'PinkCapy - Thanh toán')

@section('content')
<div class="checkout-page-wrapper mb-5">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="mb-4">
            @include('partials.breadcrumb', [
                'items' => [['name' => 'Giỏ hàng', 'url' => route('cart.page')]],
                'current' => 'Thanh toán'
            ])
        </div>

        <div class="checkout-header mb-4">
            <h1 class="checkout-title">
                <i class="bi bi-credit-card"></i> Thanh toán
            </h1>
        </div>

        <form id="checkout-form" method="POST" action="{{ route('cart.api.checkout') }}">
            @csrf
            <div class="row g-4">
                <!-- Left Column - Forms -->
                <div class="col-lg-8">
                    <!-- Shipping Info -->
                    <div class="checkout-card mb-4">
                        <h3 class="checkout-card-title">
                            <i class="bi bi-geo-alt"></i> Thông tin giao hàng
                        </h3>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first_name"
                                       value="{{ $profile->first_name ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last_name"
                                       value="{{ $profile->last_name ?? '' }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone"
                                       value="{{ $user->phone ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                       value="{{ $user->email ?? '' }}" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="line1"
                                   placeholder="Số nhà, tên đường" required>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Địa chỉ bổ sung</label>
                            <input type="text" class="form-control" name="line2"
                                   placeholder="Tòa nhà, căn hộ, v.v.">
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select" name="province" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <option value="Ho Chi Minh">Hồ Chí Minh</option>
                                    <option value="Ha Noi">Hà Nội</option>
                                    <option value="Da Nang">Đà Nẵng</option>
                                    <option value="Hai Phong">Hải Phòng</option>
                                    <option value="Can Tho">Cần Thơ</option>
                                    <option value="Binh Duong">Bình Dương</option>
                                    <option value="Dong Nai">Đồng Nai</option>
                                    <option value="Khanh Hoa">Khánh Hòa</option>
                                    <option value="Other">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="city" 
                                       placeholder="Nhập quận/huyện" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="note" rows="3" 
                                      placeholder="Ghi chú cho đơn hàng (nếu có)"></textarea>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card">
                        <h3 class="checkout-card-title">
                            <i class="bi bi-wallet2"></i> Phương thức thanh toán
                        </h3>
                        
                        <div class="payment-methods">
                            <div class="payment-method active">
                                <input type="radio" name="payment_method" value="cod" id="pm-cod" checked>
                                <label for="pm-cod">
                                    <i class="bi bi-cash-coin"></i>
                                    <div>
                                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                                        <small>Thanh toán bằng tiền mặt khi nhận hàng</small>
                                    </div>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" name="payment_method" value="banking" id="pm-banking">
                                <label for="pm-banking">
                                    <i class="bi bi-bank"></i>
                                    <div>
                                        <strong>Chuyển khoản ngân hàng</strong>
                                        <small>Chuyển khoản qua tài khoản ngân hàng</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-lg-4">
                    <div class="checkout-summary-card">
                        <h3 class="summary-title">Đơn hàng của bạn</h3>
                        
                        <!-- Order Items -->
                        <div class="order-items">
                            @foreach($cart->items as $item)
                            @php
                                $imageUrl = $item->productImage 
                                    ? 'https://broad-snowflake-e396.ttt2042005.workers.dev/proxy?img=' . $item->productImage 
                                    : '/img/placeholder.png';
                                $subtotal = $item->price * $item->quantity;
                            @endphp
                            <input type="hidden" name="items[]" value="{{ $item->itemId }}">
                            <div class="order-item">
                                <div class="order-item-image">
                                    <img src="{{ $imageUrl }}" alt="{{ $item->productName }}">
                                    <span class="order-item-qty">{{ $item->quantity }}</span>
                                </div>
                                <div class="order-item-info">
                                    <p class="order-item-name">{{ $item->productName }}</p>
                                    <p class="order-item-price">{{ number_format($subtotal, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Details -->
                        <div class="price-details">
                            <div class="price-row">
                                <span>Tạm tính</span>
                                <span>{{ number_format($cart->totalPrice, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="price-row">
                                <span>Phí vận chuyển</span>
                                <span>Miễn phí</span>
                            </div>
                            <div class="price-row price-total">
                                <span>Tổng cộng</span>
                                <span class="total-value">{{ number_format($cart->totalPrice, 0, ',', '.') }}₫</span>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" class="btn-place-order" id="btn-place-order">
                            <i class="bi bi-bag-check"></i> Đặt hàng
                        </button>

                        <!-- Back to Cart -->
                        <a href="{{ route('cart.page') }}" class="btn-back-to-cart">
                            <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.checkout-page-wrapper {
    padding-top: 1rem;
}

.checkout-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #333;
}

.checkout-title i {
    color: #ff6f91;
}

.checkout-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.checkout-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.checkout-card-title i {
    color: #ff6f91;
}

/* Payment Methods */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.payment-method {
    border: 2px solid #eee;
    border-radius: 10px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-method:hover {
    border-color: #ff6f91;
}

.payment-method.active,
.payment-method:has(input:checked) {
    border-color: #ff6f91;
    background: #fff5f7;
}

.payment-method label {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    margin: 0;
}

.payment-method label i {
    font-size: 1.5rem;
    color: #ff6f91;
}

.payment-method label div {
    display: flex;
    flex-direction: column;
}

.payment-method label strong {
    font-size: 0.9375rem;
}

.payment-method label small {
    color: #666;
    font-size: 0.8125rem;
}

.payment-method input[type="radio"] {
    display: none;
}

/* Checkout Summary */
.checkout-summary-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    position: sticky;
    top: 100px;
}

.summary-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #eee;
}

.order-items {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 1rem;
}

.order-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f5f5f5;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-image {
    position: relative;
    width: 60px;
    height: 60px;
    flex-shrink: 0;
}

.order-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #eee;
}

.order-item-qty {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 22px;
    height: 22px;
    background: #ff6f91;
    color: white;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

.order-item-info {
    flex: 1;
}

.order-item-name {
    font-size: 0.875rem;
    font-weight: 500;
    margin: 0 0 0.25rem 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.order-item-price {
    font-size: 0.875rem;
    font-weight: 600;
    color: #ff6f91;
    margin: 0;
}

.price-details {
    padding: 1rem 0;
    border-top: 1px solid #eee;
}

.price-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    font-size: 0.9375rem;
}

.price-row.price-total {
    border-top: 2px solid #eee;
    margin-top: 0.5rem;
    padding-top: 0.75rem;
    font-weight: 600;
}

.price-row .total-value {
    font-size: 1.25rem;
    color: #ff6f91;
    font-weight: 700;
}

.btn-place-order {
    width: 100%;
    padding: 1rem;
    background: #ff6f91;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.btn-place-order:hover {
    background: #e85a7c;
    transform: translateY(-1px);
}

.btn-place-order:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.btn-back-to-cart {
    display: block;
    text-align: center;
    margin-top: 1rem;
    color: #666;
    text-decoration: none;
    font-size: 0.875rem;
}

.btn-back-to-cart:hover {
    color: #ff6f91;
}

@media (max-width: 991px) {
    .checkout-summary-card {
        position: static;
        margin-top: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btn-place-order');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
    
    try {
        const formData = new FormData(this);
        const data = {};
        
        // Convert FormData to object
        formData.forEach((value, key) => {
            if (key === 'items[]') {
                if (!data.items) data.items = [];
                data.items.push(parseInt(value));
            } else {
                data[key] = value;
            }
        });
        
        const response = await fetch('{{ route("cart.api.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            alert(result.message || 'Không thể đặt hàng. Vui lòng thử lại.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Checkout error:', error);
        alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

// Payment method selection
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
        this.classList.add('active');
        this.querySelector('input[type="radio"]').checked = true;
    });
});
</script>
@endpush
@endsection
