@extends('layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="login-page-container">
    <div class="login-card-wrapper">
        <div class="login-card-left">
            <div class="login-logo-box">
                <img src="{{ asset('img/logo.svg') }}" alt="Logo Shop" class="login-logo" />
            </div>
        </div>
        <div class="login-card login-card-right">
            <div class="card-header text-center">
                <h4>Đăng ký tài khoản</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="first_name" class="form-label">Họ <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required placeholder="Nguyễn">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="last_name" class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required placeholder="Văn A">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="example@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0912345678">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Tối thiểu 8 ký tự">
                        <small class="text-muted">Mật khẩu phải có ít nhất 8 ký tự</small>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required placeholder="Nhập lại mật khẩu">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="agree_terms" id="agree_terms" class="form-check-input" required>
                        <label class="form-check-label" for="agree_terms">
                            Tôi đồng ý với <a href="/dieu-khoan" target="_blank" class="text-primary">Điều khoản sử dụng</a> và <a href="/chinh-sach-bao-mat" target="_blank" class="text-primary">Chính sách bảo mật</a>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-person-plus me-2"></i>Đăng ký
                    </button>
                </form>
                <div class="mt-4 text-center">
                    <span class="text-muted">Đã có tài khoản?</span>
                    <a href="{{ route('login') }}" class="text-primary fw-bold ms-1">Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection