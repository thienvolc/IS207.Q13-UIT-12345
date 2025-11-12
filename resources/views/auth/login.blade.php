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
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
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
@endsection