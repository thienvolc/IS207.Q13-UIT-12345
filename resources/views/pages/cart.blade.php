@extends('layouts.app')

@section('title', 'Giỏ hàng')

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
                <i class="bi bi-cart3"></i> Giỏ hàng của bạn
            </h1>
            <p class="cart-subtitle">Bạn có <strong id="cart-count">3</strong> sản phẩm trong giỏ hàng</p>
        </div>

        <div class="grid-row">
            <!-- Cart Items -->
            <div class="grid__col-8">
                <div class="cart-items-card">
                    <!-- Cart Item 1 -->
                    <div class="cart-item">
                        <div class="cart-item-checkbox">
                            <input type="checkbox" class="form-check-input" checked>
                        </div>
                        <div class="cart-item-image">
                            <img src="/img/hero1.png" alt="Tai nghe PinkCapy Pro">
                        </div>
                        <div class="cart-item-info">
                            <h4 class="cart-item-name">Tai nghe PinkCapy Pro - Chống ồn chủ động</h4>
                            <p class="cart-item-variant">Màu: Hồng | Bảo hành: 12 tháng</p>
                            <div class="cart-item-price">
                                <span class="price-current">1.990.000₫</span>
                                <span class="price-original">2.490.000₫</span>
                                <span class="price-discount">-20%</span>
                            </div>
                        </div>
                        <div class="cart-item-quantity">
                            <button class="qty-btn qty-minus">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="qty-input" value="1" min="1" max="10">
                            <button class="qty-btn qty-plus">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <div class="cart-item-total">
                            <span class="item-total-price">1.990.000₫</span>
                        </div>
                        <div class="cart-item-actions">
                            <button class="btn-icon btn-delete" title="Xóa">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Cart Item 2 -->
                    <div class="cart-item">
                        <div class="cart-item-checkbox">
                            <input type="checkbox" class="form-check-input" checked>
                        </div>
                        <div class="cart-item-image">
                            <img src="/img/hero2.png" alt="Loa Bluetooth PinkCapy">
                        </div>
                        <div class="cart-item-info">
                            <h4 class="cart-item-name">Loa Bluetooth PinkCapy Mini - Bass mạnh mẽ</h4>
                            <p class="cart-item-variant">Màu: Trắng | Bảo hành: 24 tháng</p>
                            <div class="cart-item-price">
                                <span class="price-current">890.000₫</span>
                                <span class="price-original">1.190.000₫</span>
                                <span class="price-discount">-25%</span>
                            </div>
                        </div>
                        <div class="cart-item-quantity">
                            <button class="qty-btn qty-minus">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="qty-input" value="2" min="1" max="10">
                            <button class="qty-btn qty-plus">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <div class="cart-item-total">
                            <span class="item-total-price">1.780.000₫</span>
                        </div>
                        <div class="cart-item-actions">
                            <button class="btn-icon btn-delete" title="Xóa">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Cart Item 3 -->
                    <div class="cart-item">
                        <div class="cart-item-checkbox">
                            <input type="checkbox" class="form-check-input" checked>
                        </div>
                        <div class="cart-item-image">
                            <img src="/img/hero3.png" alt="Cáp sạc PinkCapy">
                        </div>
                        <div class="cart-item-info">
                            <h4 class="cart-item-name">Cáp sạc nhanh PinkCapy Type-C 1.5m</h4>
                            <p class="cart-item-variant">Màu: Hồng | Bảo hành: 6 tháng</p>
                            <div class="cart-item-price">
                                <span class="price-current">149.000₫</span>
                            </div>
                        </div>
                        <div class="cart-item-quantity">
                            <button class="qty-btn qty-minus">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="qty-input" value="1" min="1" max="10">
                            <button class="qty-btn qty-plus">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <div class="cart-item-total">
                            <span class="item-total-price">149.000₫</span>
                        </div>
                        <div class="cart-item-actions">
                            <button class="btn-icon btn-delete" title="Xóa">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Cart Actions -->
                    <div class="cart-actions">
                        <div class="cart-select-all">
                            <input type="checkbox" class="form-check-input" id="selectAll" checked>
                            <label for="selectAll">Chọn tất cả (3)</label>
                        </div>
                        <button class="btn-text btn-delete-selected">
                            <i class="bi bi-trash"></i> Xóa đã chọn
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="grid__col-4">
                <div class="cart-summary-card">
                    <h3 class="summary-title">Thông tin đơn hàng</h3>
                    
                    <!-- Voucher -->
                    <div class="voucher-section">
                        <div class="voucher-input-group">
                            <input type="text" class="form-control" placeholder="Nhập mã giảm giá">
                            <button class="btn btn-apply">Áp dụng</button>
                        </div>
                        <div class="voucher-list">
                            <div class="voucher-item">
                                <i class="bi bi-ticket-perforated"></i>
                                <span>Giảm 100K cho đơn từ 500K</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="price-details">
                        <div class="price-row">
                            <span>Tạm tính</span>
                            <span class="price-value">3.919.000₫</span>
                        </div>
                        <div class="price-row">
                            <span>Giảm giá</span>
                            <span class="price-value text-danger">-0₫</span>
                        </div>
                        <div class="price-row">
                            <span>Phí vận chuyển</span>
                            <span class="price-value">30.000₫</span>
                        </div>
                        <div class="price-row price-total">
                            <span>Tổng cộng</span>
                            <span class="price-value">3.949.000₫</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('order.checkout') }}" class="btn btn-checkout">
                     Mua hàng
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

                <!-- Continue Shopping -->
                <a href="{{ route('products.index') }}" class="btn btn-continue-shopping">
                    <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            const value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateItemTotal(this.closest('.cart-item'));
            }
        });
    });

    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            const value = parseInt(input.value);
            const max = parseInt(input.max);
            if (value < max) {
                input.value = value + 1;
                updateItemTotal(this.closest('.cart-item'));
            }
        });
    });

    // Delete item
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                this.closest('.cart-item').remove();
                updateCartSummary();
            }
        });
    });

    // Select all
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.cart-item-checkbox input').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    function updateItemTotal(item) {
        const qty = parseInt(item.querySelector('.qty-input').value);
        const priceText = item.querySelector('.price-current').textContent;
        const price = parseInt(priceText.replace(/[^\d]/g, ''));
        const total = qty * price;
        item.querySelector('.item-total-price').textContent = total.toLocaleString('vi-VN') + '₫';
        updateCartSummary();
    }

    function updateCartSummary() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const totalText = item.querySelector('.item-total-price').textContent;
            const total = parseInt(totalText.replace(/[^\d]/g, ''));
            subtotal += total;
        });
        
        const shipping = 30000;
        const total = subtotal + shipping;
        
        document.querySelector('.price-details .price-row:nth-child(1) .price-value').textContent = 
            subtotal.toLocaleString('vi-VN') + '₫';
        document.querySelector('.price-total .price-value').textContent = 
            total.toLocaleString('vi-VN') + '₫';
        
        const count = document.querySelectorAll('.cart-item').length;
        document.getElementById('cart-count').textContent = count;
    }
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/cart.css') }}">
@endpush
@endsection
