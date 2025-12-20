@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
    <div class="profile-page-container">
        <div class="container py-5">
            <div class="row">
                <!-- Sidebar Menu -->
                <div class="col-lg-3 mb-4 mb-lg-0">
                    @include('pages.account.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="profile-content-card">
                        <div class="profile-card-header">
                            <h3>Đơn hàng của tôi</h3>
                            <p class="text-muted">Quản lý và theo dõi đơn hàng</p>
                        </div>

                        <div class="profile-card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @php
                                $pendingOrders = $orders->where('status', 1);
                                $processingOrders = $orders->where('status', 2);
                                $shippingOrders = $orders->where('status', 3);
                                $completedOrders = $orders->where('status', 4);
                                $cancelledOrders = $orders->where('status', 5);

                                // Define status texts and classes if not passed from controller
                                $statusTexts = $statusTexts ?? [
                                    1 => 'Chờ xác nhận',
                                    2 => 'Đang xử lý',
                                    3 => 'Đang giao',
                                    4 => 'Hoàn thành',
                                    5 => 'Đã hủy',
                                    6 => 'Hoàn tiền',
                                    7 => 'Trả hàng',
                                    8 => 'Đã hủy'
                                ];
                                $statusClasses = $statusClasses ?? [
                                    1 => 'bg-warning text-dark',
                                    2 => 'bg-info text-white',
                                    3 => 'bg-primary',
                                    4 => 'bg-success',
                                    5 => 'bg-danger',
                                    6 => 'bg-secondary',
                                    7 => 'bg-secondary',
                                    8 => 'bg-danger'
                                ];
                            @endphp

                            <!-- Order Status Tabs -->
                            <ul class="nav nav-pills mb-4" id="orderTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill"
                                        data-bs-target="#all-orders" type="button">
                                        Tất cả
                                        @if($orders->count() > 0)
                                            <span class="badge bg-secondary ms-1">{{ $orders->count() }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pending-tab" data-bs-toggle="pill"
                                        data-bs-target="#pending-orders" type="button">
                                        Chờ xác nhận
                                        @if($pendingOrders->count() > 0)
                                            <span class="badge bg-warning text-dark ms-1">{{ $pendingOrders->count() }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="processing-tab" data-bs-toggle="pill"
                                        data-bs-target="#processing-orders" type="button">
                                        Đang xử lý
                                        @if($processingOrders->count() > 0)
                                            <span class="badge bg-info ms-1">{{ $processingOrders->count() }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="shipping-tab" data-bs-toggle="pill"
                                        data-bs-target="#shipping-orders" type="button">
                                        Đang giao
                                        @if($shippingOrders->count() > 0)
                                            <span class="badge bg-primary ms-1">{{ $shippingOrders->count() }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="completed-tab" data-bs-toggle="pill"
                                        data-bs-target="#completed-orders" type="button">
                                        Hoàn thành
                                        @if($completedOrders->count() > 0)
                                            <span class="badge bg-success ms-1">{{ $completedOrders->count() }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="cancelled-tab" data-bs-toggle="pill"
                                        data-bs-target="#cancelled-orders" type="button">
                                        Đã hủy
                                        @if($cancelledOrders->count() > 0)
                                            <span class="badge bg-danger ms-1">{{ $cancelledOrders->count() }}</span>
                                        @endif
                                    </button>
                                </li>
                            </ul>

                            <!-- Order List -->
                            <div class="tab-content" id="orderTabsContent">
                                <div class="tab-pane fade show active" id="all-orders" role="tabpanel">
                                    @if($orders->isEmpty())
                                        <div class="orders-empty">
                                            <i class="bi bi-box"></i>
                                            <p>Bạn chưa có đơn hàng nào</p>
                                            <a href="{{ route('products.index') }}" class="btn btn-primary"
                                                style="background: linear-gradient(135deg, #ff6f91, #ff9671); border: none; color: #fff;">Mua
                                                sắm ngay</a>
                                        </div>
                                    @else
                                        @foreach($orders as $order)
                                            <div class="order-card mb-3 border rounded p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <span class="fw-bold">Đơn hàng #{{ $order->order_id }}</span>
                                                        <span
                                                            class="text-muted ms-2">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    <span
                                                        class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }}">{{ $statusTexts[$order->status] ?? 'Không xác định' }}</span>
                                                </div>
                                                <div class="order-items">
                                                    @foreach($order->items->take(2) as $item)
                                                        @php
                                                            $thumb = $item->product?->thumb;
                                                            if ($thumb && !str_starts_with($thumb, 'http')) {
                                                                $thumb = 'https://broad-snowflake-e396.ttt2042005.workers.dev/proxy?img=' . $thumb;
                                                            }
                                                        @endphp
                                                        <div class="d-flex align-items-center mb-2">
                                                            <img src="{{ $thumb ?? '/img/default-product.png' }}" alt=""
                                                                class="me-2 rounded"
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                            <div class="flex-grow-1">
                                                                <div class="fw-medium"
                                                                    style="line-height: 1.6; padding-top: 10px; padding-bottom: 2px;">
                                                                    {{ $item->product?->title ?? 'Sản phẩm' }}</div>
                                                                <small class="text-muted">x{{ $item->quantity }}</small>
                                                            </div>
                                                            <div class="text-primary fw-bold">
                                                                {{ number_format($item->price, 0, ',', '.') }}đ
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if($order->items->count() > 2)
                                                        <small class="text-muted">và {{ $order->items->count() - 2 }} sản phẩm
                                                            khác...</small>
                                                    @endif
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                                    <div>
                                                        <span class="text-muted">Tổng tiền:</span>
                                                        <span
                                                            class="text-danger fw-bold ms-2">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('account.orders.show', $order->order_id) }}"
                                                            class="btn btn-outline-primary btn-sm">Xem chi tiết</a>

                                                        @if($order->status == 1)
                                                            @if(($order->payment_method ?? '') !== 'cod')
                                                                <a href="{{ route('account.orders.repay', $order->order_id) }}"
                                                                    class="btn btn-primary btn-sm ms-2">Thanh toán lại</a>
                                                            @endif

                                                            <form action="/account/orders/{{ $order->order_id }}/cancel" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm ms-2"
                                                                    onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">Hủy
                                                                    đơn</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="pending-orders" role="tabpanel">
                                    @if($pendingOrders->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p>Không có đơn hàng chờ xác nhận</p>
                                        </div>
                                    @else
                                        @foreach($pendingOrders as $order)
                                            @include('pages.account.partials.order-card', ['order' => $order, 'statusClasses' => $statusClasses, 'statusTexts' => $statusTexts])
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="processing-orders" role="tabpanel">
                                    @if($processingOrders->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p>Không có đơn hàng đang xử lý</p>
                                        </div>
                                    @else
                                        @foreach($processingOrders as $order)
                                            @include('pages.account.partials.order-card', ['order' => $order, 'statusClasses' => $statusClasses, 'statusTexts' => $statusTexts])
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="shipping-orders" role="tabpanel">
                                    @if($shippingOrders->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p>Không có đơn hàng đang giao</p>
                                        </div>
                                    @else
                                        @foreach($shippingOrders as $order)
                                            @include('pages.account.partials.order-card', ['order' => $order, 'statusClasses' => $statusClasses, 'statusTexts' => $statusTexts])
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="completed-orders" role="tabpanel">
                                    @if($completedOrders->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p>Không có đơn hàng hoàn thành</p>
                                        </div>
                                    @else
                                        @foreach($completedOrders as $order)
                                            @include('pages.account.partials.order-card', ['order' => $order, 'statusClasses' => $statusClasses, 'statusTexts' => $statusTexts])
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="cancelled-orders" role="tabpanel">
                                    @if($cancelledOrders->isEmpty())
                                        <div class="orders-empty">
                                            <i class="bi bi-inbox"></i>
                                            <p>Không có đơn hàng đã hủy</p>
                                        </div>
                                    @else
                                        @foreach($cancelledOrders as $order)
                                            @include('pages.account.partials.order-card', ['order' => $order, 'statusClasses' => $statusClasses, 'statusTexts' => $statusTexts])
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
        <link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // loadOrders();
            });

            // async function loadOrders() {
            //     const container = document.getElementById('orders-container');
            //     const token = localStorage.getItem('auth_token');

            //     if (!token) {
            //         container.innerHTML = `
            //             <div class="orders-empty">
            //                 <i class="bi bi-box"></i>
            //                 <p>Vui lòng đăng nhập để xem đơn hàng</p>
            //                 <a href="{{ route('login') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #ff6f91, #ff9671); border: none; color: #fff;">Đăng nhập</a>
            //             </div>
            //         `;
            //         return;
            //     }

            //     try {
            //         const response = await fetch('/api/me/orders', {
            //             headers: {
            //                 'Authorization': `Bearer ${token}`,
            //                 'Accept': 'application/json'
            //             }
            //         });

            //         const result = await response.json();

            //         if (result.data && result.data.data && result.data.data.length > 0) {
            //             renderOrders(result.data.data);
            //         } else {
            //             container.innerHTML = `
            //                 <div class="text-center py-5">
            //                     <i class="bi bi-box-seam fs-1 text-muted"></i>
            //                     <p class="mt-3 text-muted">Bạn chưa có đơn hàng nào</p>
            //                     <a href="{{ route('products.index') }}" class="btn btn-primary">Mua sắm ngay</a>
            //                 </div>
            //             `;
            //         }
            //     } catch (error) {
            //         console.error('Error loading orders:', error);
            //         container.innerHTML = `
            //             <div class="text-center py-5">
            //                 <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
            //                 <p class="mt-3 text-muted">Không thể tải đơn hàng. Vui lòng thử lại sau.</p>
            //                 <button onclick="loadOrders()" class="btn btn-outline-primary">Thử lại</button>
            //             </div>
            //         `;
            //     }
            // }

            // function renderOrders(orders) {
            //     const container = document.getElementById('orders-container');

            //     const html = orders.map(order => `
            //         <div class="order-card mb-3 border rounded p-3">
            //             <div class="d-flex justify-content-between align-items-center mb-2">
            //                 <div>
            //                     <span class="fw-bold">Đơn hàng #${order.orderId}</span>
            //                     <span class="text-muted ms-2">${formatDate(order.createdAt)}</span>
            //                 </div>
            //                 <span class="badge ${getStatusBadgeClass(order.status)}">${getStatusText(order.status)}</span>
            //             </div>
            //             <div class="order-items">
            //                 ${order.items ? order.items.slice(0, 2).map(item => `
            //                     <div class="d-flex align-items-center mb-2">
            //                         <img src="${item.thumb || '/img/default-product.png'}" alt="" class="me-2 rounded" style="width: 50px; height: 50px; object-fit: cover;">
            //                         <div class="flex-grow-1">
            //                             <div class="fw-medium">${item.productTitle || 'Sản phẩm'}</div>
            //                             <small class="text-muted">x${item.quantity}</small>
            //                         </div>
            //                         <div class="text-primary fw-bold">${formatPrice(item.price)}đ</div>
            //                     </div>
            //                 `).join('') : ''}
            //                 ${order.items && order.items.length > 2 ? `<small class="text-muted">và ${order.items.length - 2} sản phẩm khác...</small>` : ''}
            //             </div>
            //             <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
            //                 <div>
            //                     <span class="text-muted">Tổng tiền:</span>
            //                     <span class="text-danger fw-bold ms-2">${formatPrice(order.grandTotal)}đ</span>
            //                 </div>
            //                 <div>
            //                     <a href="/account/orders/${order.orderId}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
            //                     ${order.status === 0 ? `<button onclick="cancelOrder(${order.orderId})" class="btn btn-outline-danger btn-sm ms-2">Hủy đơn</button>` : ''}
            //                 </div>
            //             </div>
            //         </div>
            //     `).join('');

            //     container.innerHTML = html;
            // }

            // function getStatusText(status) {
            //     const statuses = {
            //         0: 'Chờ thanh toán',
            //         1: 'Đang xử lý',
            //         2: 'Đang giao',
            //         3: 'Hoàn thành',
            //         4: 'Đã hủy'
            //     };
            //     return statuses[status] || 'Không xác định';
            // }

            // function getStatusBadgeClass(status) {
            //     const classes = {
            //         0: 'bg-warning',
            //         1: 'bg-info',
            //         2: 'bg-primary',
            //         3: 'bg-success',
            //         4: 'bg-danger'
            //     };
            //     return classes[status] || 'bg-secondary';
            // }

            // function formatPrice(price) {
            //     return new Intl.NumberFormat('vi-VN').format(price);
            // }

            // function formatDate(dateString) {
            //     return new Date(dateString).toLocaleDateString('vi-VN');
            // }

            // async function cancelOrder(orderId) {
            //     if (!confirm('Bạn có chắc muốn hủy đơn hàng này?')) return;

            //     const token = localStorage.getItem('auth_token');
            //     try {
            //         const response = await fetch(`/api/me/orders/${orderId}/cancel`, {
            //             method: 'DELETE',
            //             headers: {
            //                 'Authorization': `Bearer ${token}`,
            //                 'Accept': 'application/json'
            //             }
            //         });

            //         if (response.ok) {
            //             alert('Đã hủy đơn hàng thành công');
            //             loadOrders();
            //         } else {
            //             alert('Không thể hủy đơn hàng. Vui lòng thử lại.');
            //         }
            //     } catch (error) {
            //         console.error('Error cancelling order:', error);
            //         alert('Đã xảy ra lỗi. Vui lòng thử lại.');
            //     }
            // }
        </script>
    @endpush

@endsection