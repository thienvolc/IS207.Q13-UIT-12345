@extends('layouts.app')
@section('title', 'Giỏ hàng')
@section('content')
<div class="cart-page-container" style="max-width:900px;margin:40px auto;padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(255,111,145,0.08);">
    <h2 class="cart-title" style="color:#ff6f91;font-weight:700;margin-bottom:32px;text-align:center;">Giỏ hàng của bạn</h2>
    <div class="cart-items">
        @if(isset($cartItems) && count($cartItems) > 0)
        @foreach($cartItems as $item)
        <div class="cart-item" style="display:flex;align-items:center;gap:24px;padding:18px 0;border-bottom:1px solid #ffe3ec;">
            <img src="{{ $item->product->image_url ?? '/img/no-image.png' }}" alt="{{ $item->product->name }}" style="width:90px;height:90px;object-fit:cover;border-radius:12px;border:2px solid #ff6f91;">
            <div style="flex:1;">
                <h4 style="margin:0 0 8px 0;font-size:1.1rem;font-weight:600;color:#ff6f91;">
                    <a href="{{ route('products.show', $item->product->slug) }}" style="color:#ff6f91;text-decoration:none;">{{ $item->product->name }}</a>
                </h4>
                <div style="font-size:0.98rem;color:#555;">Số lượng: <strong>{{ $item->quantity }}</strong></div>
                <div style="font-size:0.98rem;color:#555;">Giá: <strong>{{ number_format($item->product->price) }}₫</strong></div>
            </div>
            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                @csrf
                <button type="submit" class="btn btn-danger" style="background:#ff6f91;border:none;">Xóa</button>
            </form>
        </div>
        @endforeach
        @else
        <p style="text-align:center;color:#888;font-size:1.1rem;">Giỏ hàng của bạn đang trống.</p>
        @endif
    </div>
    <div class="cart-summary" style="margin-top:32px;text-align:right;">
        <h3 style="color:#ff6f91;font-weight:700;">Tổng cộng: {{ isset($total) ? number_format($total) : 0 }}₫</h3>
        <a href="{{ route('order.checkout') }}" class="btn btn-primary" style="background:linear-gradient(90deg,#ff6f91,#ff9671);border:none;font-weight:600;">Thanh toán</a>
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/cart.css') }}">
@endpush
@endsection