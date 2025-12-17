@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="profile-page-container">
    <div class="grid py-5">
        <div class="grid-row">
            <!-- Sidebar Menu -->
            <div class="grid__col-3">
                <div class="profile-sidebar">
                    <div class="profile-avatar-section">
                        <div class="profile-avatar">
                            @if(Auth::user()->profile?->avatar)
                                <img src="{{ Auth::user()->profile->avatar }}" alt="Avatar">
                            @else
                                <i class="bi bi-person-circle"></i>
                            @endif
                        </div>
                        <h4 class="profile-name">{{ Auth::user()->profile?->first_name ?? '' }} {{ Auth::user()->profile?->last_name ?? '' }}</h4>
                    </div>
                    <nav class="profile-nav">
                        <a href="{{ route('account.profile') }}" class="profile-nav-item">
                            <i class="bi bi-person"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                        <a href="{{ route('account.orders') }}" class="profile-nav-item active">
                            <i class="bi bi-box-seam"></i>
                            <span>Đơn hàng của tôi</span>
                        </a>
                        <a href="{{ route('account.password') }}" class="profile-nav-item">
                            <i class="bi bi-shield-lock"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="profile-nav-item profile-nav-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Đăng xuất</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid__col-9">
                <div class="profile-content-card">
                    <div class="profile-card-header">
                        <h3>Đơn hàng của tôi</h3>
                        <p class="text-muted">Quản lý và theo dõi đơn hàng</p>
                    </div>

                    <div class="profile-card-body">
                        <!-- Order Status Tabs -->
                        <ul class="nav nav-pills mb-4" id="orderTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all-orders" type="button">
                                    Tất cả
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending-orders" type="button">
                                    Chờ xác nhận
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="processing-tab" data-bs-toggle="pill" data-bs-target="#processing-orders" type="button">
                                    Đang xử lý
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="pill" data-bs-target="#shipping-orders" type="button">
                                    Đang giao
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="completed-tab" data-bs-toggle="pill" data-bs-target="#completed-orders" type="button">
                                    Hoàn thành
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cancelled-tab" data-bs-toggle="pill" data-bs-target="#cancelled-orders" type="button">
                                    Đã hủy
                                </button>
                            </li>
                        </ul>

                        <!-- Order List -->
                        <div class="tab-content" id="orderTabsContent">
                            <div class="tab-pane fade show active" id="all-orders" role="tabpanel">
                                <div id="orders-container">
                                    <!-- Orders will be loaded here via JavaScript -->
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Đang tải...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Đang tải đơn hàng...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pending-orders" role="tabpanel">
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Không có đơn hàng chờ xác nhận</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="processing-orders" role="tabpanel">
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Không có đơn hàng đang xử lý</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="shipping-orders" role="tabpanel">
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Không có đơn hàng đang giao</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="completed-orders" role="tabpanel">
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Không có đơn hàng hoàn thành</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="cancelled-orders" role="tabpanel">
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p>Không có đơn hàng đã hủy</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
});

async function loadOrders() {
    const container = document.getElementById('orders-container');
    const token = localStorage.getItem('auth_token');
    
    if (!token) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-box-seam fs-1 text-muted"></i>
                <p class="mt-3">Vui lòng đăng nhập để xem đơn hàng</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
            </div>
        `;
        return;
    }

    try {
        const response = await fetch('/api/me/orders', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.data && result.data.data && result.data.data.length > 0) {
            renderOrders(result.data.data);
        } else {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Bạn chưa có đơn hàng nào</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                <p class="mt-3 text-muted">Không thể tải đơn hàng. Vui lòng thử lại sau.</p>
                <button onclick="loadOrders()" class="btn btn-outline-primary">Thử lại</button>
            </div>
        `;
    }
}

function renderOrders(orders) {
    const container = document.getElementById('orders-container');
    
    const html = orders.map(order => `
        <div class="order-card mb-3 border rounded p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="fw-bold">Đơn hàng #${order.orderId}</span>
                    <span class="text-muted ms-2">${formatDate(order.createdAt)}</span>
                </div>
                <span class="badge ${getStatusBadgeClass(order.status)}">${getStatusText(order.status)}</span>
            </div>
            <div class="order-items">
                ${order.items ? order.items.slice(0, 2).map(item => `
                    <div class="d-flex align-items-center mb-2">
                        <img src="${item.thumb || '/img/default-product.png'}" alt="" class="me-2 rounded" style="width: 50px; height: 50px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="fw-medium">${item.productTitle || 'Sản phẩm'}</div>
                            <small class="text-muted">x${item.quantity}</small>
                        </div>
                        <div class="text-primary fw-bold">${formatPrice(item.price)}đ</div>
                    </div>
                `).join('') : ''}
                ${order.items && order.items.length > 2 ? `<small class="text-muted">và ${order.items.length - 2} sản phẩm khác...</small>` : ''}
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div>
                    <span class="text-muted">Tổng tiền:</span>
                    <span class="text-danger fw-bold ms-2">${formatPrice(order.grandTotal)}đ</span>
                </div>
                <div>
                    <a href="/account/orders/${order.orderId}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                    ${order.status === 0 ? `<button onclick="cancelOrder(${order.orderId})" class="btn btn-outline-danger btn-sm ms-2">Hủy đơn</button>` : ''}
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

function getStatusText(status) {
    const statuses = {
        0: 'Chờ thanh toán',
        1: 'Đang xử lý',
        2: 'Đang giao',
        3: 'Hoàn thành',
        4: 'Đã hủy'
    };
    return statuses[status] || 'Không xác định';
}

function getStatusBadgeClass(status) {
    const classes = {
        0: 'bg-warning',
        1: 'bg-info',
        2: 'bg-primary',
        3: 'bg-success',
        4: 'bg-danger'
    };
    return classes[status] || 'bg-secondary';
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('vi-VN');
}

async function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc muốn hủy đơn hàng này?')) return;
    
    const token = localStorage.getItem('auth_token');
    try {
        const response = await fetch(`/api/me/orders/${orderId}/cancel`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('Đã hủy đơn hàng thành công');
            loadOrders();
        } else {
            alert('Không thể hủy đơn hàng. Vui lòng thử lại.');
        }
    } catch (error) {
        console.error('Error cancelling order:', error);
        alert('Đã xảy ra lỗi. Vui lòng thử lại.');
    }
}
</script>
@endpush

@endsection
