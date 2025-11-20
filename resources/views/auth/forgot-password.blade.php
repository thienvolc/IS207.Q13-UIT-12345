@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="forgot-page-container">
    <div class="forgot-card-wrapper">
        <div class="forgot-card-left">
            <div class="forgot-logo-box">
                <img src="{{ asset('img/logo.svg') }}" alt="Logo Shop" class="forgot-logo" />
            </div>
        </div>
        <div class="forgot-card forgot-card-right">
            <div class="card-header text-center">
                <h4>Quên mật khẩu</h4>
            </div>
            <div class="card-body">
                @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first('email') }}
                </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Gửi yêu cầu đặt lại mật khẩu</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}">Quay lại đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection