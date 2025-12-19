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
            <h1 class="checkout-title text-uppercase fw-bold text-center">
                <i class="bi bi-credit-card"></i> Thanh toán
            </h1>
        </div>

        <form id="checkout-form" method="POST" action="{{ route('cart.api.checkout') }}">
            @csrf
            <div class="row g-4">
                <!-- Left Column - Forms -->
                <div class="col-lg-8">
                    <!-- Shipping Info -->
                    <div class="checkout-card mb-4" >
                        <h3 class="checkout-card-title border-bottom pb-3 mb-4">
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
                        <h3 class="checkout-card-title border-bottom pb-3 mt-4 mb-4">
                            <i class="bi bi-wallet2"></i> Phương thức thanh toán
                        </h3>

                        <div class="payment-methods d-flex flex-column align-items-start">
                            <div class="payment-method active">
                                <input type="radio" name="payment_method" value="cod" id="pm-cod" checked>
                                <label for="pm-cod">
                                    <i class="bi bi-cash-coin fs-4"></i>
                                    <div>
                                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                                        <small>Thanh toán bằng tiền mặt khi nhận hàng</small>
                                    </div>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" name="payment_method" value="banking" id="pm-banking">
                                <label for="pm-banking">
                                    <i class="bi bi-bank fs-4"></i>
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
                <div class="col-lg-4 p-3" style="border: 1px solid #ff0000ff; border-radius: 10px;">
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
                                </div>
                                <div class="order-item-details">
                                    <h4 class="order-item-name">{{ $item->productName }}</h4>
                                    <div class="order-item-info-row">
                                        <span class="info-label fs-5">Số lượng:</span>
                                        <span class="info-value fw-bold">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="order-item-info-row">
                                        <span class="info-label fs-5">Đơn giá:</span>
                                        <span class="info-value fw-bold">{{ number_format($item->price, 0, ',', '.') }}₫</span>
                                    </div>
                                    <div class="order-item-info-row total-row">
                                        <span class="info-label fs-5">Thành tiền:</span>
                                        <span class="info-value price-highlight fw-bold">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Details --> 
                        <div class="price-details mt-5">
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
                        <div class="d-flex justify-content-around mt-4">
                        <!-- Back to Cart -->
                        <a href="{{ route('cart.page') }}" class="btn-back-to-cart btn btn-outline-primary me-3">
                            <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                        </a>

                        <!-- Place Order Button -->
                        <button type="submit" class="btn-place-order btn btn-primary" id="btn-place-order">
                            <i class="bi bi-bag-check"></i> Đặt hàng
                        </button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


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