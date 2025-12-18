@extends('layouts.app')

@section('title', 'Đăng nhập')

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
                <h4>Đăng nhập</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first('email') }}
                </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
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
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
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