@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

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
                        <a href="{{ route('account.orders') }}" class="profile-nav-item">
                            <i class="bi bi-box-seam"></i>
                            <span>Đơn hàng của tôi</span>
                        </a>
                        <a href="{{ route('account.password') }}" class="profile-nav-item active">
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
                        <h3>Đổi mật khẩu</h3>
                        <p class="text-muted">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
                    </div>

                    <div class="profile-card-body">
                        @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('account.password.update') }}" style="max-width: 500px;">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="bi bi-lock"></i> Mật khẩu hiện tại
                                </label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password" placeholder="Nhập mật khẩu hiện tại">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Mật khẩu mới
                                </label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required autocomplete="new-password" placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)">
                                <small class="text-muted">Mật khẩu phải có ít nhất 8 ký tự</small>
                            </div>
                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Nhập lại mật khẩu mới
                                </label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password" placeholder="Nhập lại mật khẩu mới">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Cập nhật mật khẩu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection