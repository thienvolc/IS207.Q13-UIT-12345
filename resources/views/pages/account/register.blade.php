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
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Họ</label>
                        <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Tên</label>
                        <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required style="padding-right: 50px !important;">
                            <button type="button" class="position-absolute" onclick="togglePassword('password', this)" style="right: 10px !important; top: 50% !important; transform: translateY(-50%) !important; border: none !important; background: none !important; padding: 8px !important; cursor: pointer !important; z-index: 999 !important; line-height: 1 !important;" tabindex="-1">
                                <i class="bi bi-eye" style="font-size: 1.3rem !important; color: #6c757d !important;"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required style="padding-right: 50px !important;">
                            <button type="button" class="position-absolute" onclick="togglePassword('password_confirmation', this)" style="right: 10px !important; top: 50% !important; transform: translateY(-50%) !important; border: none !important; background: none !important; padding: 8px !important; cursor: pointer !important; z-index: 999 !important; line-height: 1 !important;" tabindex="-1">
                                <i class="bi bi-eye" style="font-size: 1.3rem !important; color: #6c757d !important;"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}">Đã có tài khoản? Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
@endsection