@extends('layouts.admin')

@section('title', 'Cài đặt - Admin')
@section('page-title', 'Cài đặt')

@section('content')
    <div class="container-fluid px-0">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Cài đặt hệ thống</h1>
                <p class="page-subtitle">Quản lý thông tin cửa hàng và các cấu hình</p>
            </div>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">

                    {{-- General Settings --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-store me-2 text-primary"></i>Thông tin cửa hàng
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tên cửa hàng <span class="text-danger">*</span></label>
                                    <input type="text" name="shop_name"
                                        class="form-control @error('shop_name') is-invalid @enderror"
                                        value="{{ old('shop_name', $settings['shop_name'] ?? '') }}" required>
                                    @error('shop_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="shop_email"
                                        class="form-control @error('shop_email') is-invalid @enderror"
                                        value="{{ old('shop_email', $settings['shop_email'] ?? '') }}" required>
                                    @error('shop_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="shop_phone" class="form-control"
                                        value="{{ old('shop_phone', $settings['shop_phone'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Logo URL</label>
                                    <input type="url" name="shop_logo" class="form-control" placeholder="https://..."
                                        value="{{ old('shop_logo', $settings['shop_logo'] ?? '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea name="shop_address" class="form-control"
                                        rows="2">{{ old('shop_address', $settings['shop_address'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Settings --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-credit-card me-2 text-success"></i>Thanh toán
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Đơn vị tiền tệ</label>
                                    <select name="currency" class="form-select">
                                        <option value="VND" @selected(($settings['currency'] ?? 'VND') == 'VND')>VND - Việt
                                            Nam Đồng</option>
                                        <option value="USD" @selected(($settings['currency'] ?? '') == 'USD')>USD - US Dollar
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phương thức thanh toán</label>
                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                        @php $methods = $settings['payment_methods'] ?? ['cod']; @endphp
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="payment_methods[]"
                                                value="cod" id="pm_cod" @checked(in_array('cod', $methods))>
                                            <label class="form-check-label" for="pm_cod">COD</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="payment_methods[]"
                                                value="bank_transfer" id="pm_bank" @checked(in_array('bank_transfer', $methods))>
                                            <label class="form-check-label" for="pm_bank">Chuyển khoản</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="payment_methods[]"
                                                value="vnpay" id="pm_vnpay" @checked(in_array('vnpay', $methods))>
                                            <label class="form-check-label" for="pm_vnpay">VNPay</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="payment_methods[]"
                                                value="momo" id="pm_momo" @checked(in_array('momo', $methods))>
                                            <label class="form-check-label" for="pm_momo">MoMo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Settings --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-truck me-2 text-warning"></i>Vận chuyển
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Phí vận chuyển mặc định</label>
                                    <div class="input-group">
                                        <input type="number" name="default_shipping_fee" class="form-control" min="0"
                                            value="{{ old('default_shipping_fee', $settings['default_shipping_fee'] ?? 30000) }}">
                                        <span class="input-group-text">₫</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Miễn phí ship từ</label>
                                    <div class="input-group">
                                        <input type="number" name="free_shipping_threshold" class="form-control" min="0"
                                            value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? 500000) }}">
                                        <span class="input-group-text">₫</span>
                                    </div>
                                    <small class="text-muted">Đặt 0 để tắt miễn phí ship</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEO Settings --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-search me-2 text-info"></i>SEO
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" maxlength="255"
                                    value="{{ old('meta_title', $settings['meta_title'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3"
                                    maxlength="500">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                                <small class="text-muted">Tối đa 500 ký tự</small>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">

                    {{-- Social Links --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa fa-share-alt me-2 text-primary"></i>Mạng xã hội
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-facebook text-primary me-1"></i> Facebook</label>
                                <input type="url" name="facebook_url" class="form-control"
                                    placeholder="https://facebook.com/..."
                                    value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-instagram text-danger me-1"></i>
                                    Instagram</label>
                                <input type="url" name="instagram_url" class="form-control"
                                    placeholder="https://instagram.com/..."
                                    value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-youtube text-danger me-1"></i> YouTube</label>
                                <input type="url" name="youtube_url" class="form-control"
                                    placeholder="https://youtube.com/..."
                                    value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-save me-2 text-success"></i>Hành động
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Lưu cài đặt
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-times me-1"></i> Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>
@endsection