<div class="order-card mb-3 border rounded p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <span class="fw-bold">Đơn hàng #{{ $order->order_id }}</span>
            <span class="text-muted ms-2">{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }}">{{ $statusTexts[$order->status] ?? 'Không xác định' }}</span>
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
            <small class="text-muted">và {{ $order->items->count() - 2 }} sản phẩm khác...</small>
        @endif
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
        <div>
            <span class="text-muted">Tổng tiền:</span>
            <span class="text-danger fw-bold ms-2">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
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
                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">Hủy đơn</button>
                </form>
            @endif
        </div>
    </div>
</div>
