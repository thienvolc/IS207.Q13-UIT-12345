@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
    <div class="profile-page-container">
        <div class="container py-5">
            <div class="row">
                <!-- Sidebar Menu -->
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <div class="profile-sidebar">
                        @include('pages.account.partials.sidebar')
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
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
                                <div class="mb-3 position-relative">
                                    <label for="current_password" class="form-label">
                                        <i class="bi bi-lock"></i> Mật khẩu hiện tại
                                    </label>
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" required autocomplete="current-password"
                                        placeholder="Nhập mật khẩu hiện tại">
                                    <span class="toggle-password" toggle="#current_password"
                                        style="position:absolute;top:38px;right:16px;cursor:pointer;z-index:2;"><i
                                            class="bi bi-eye-slash"></i></span>
                                </div>
                                <div class="mb-3 position-relative">
                                    <label for="new_password" class="form-label">
                                        <i class="bi bi-lock-fill"></i> Mật khẩu mới
                                    </label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        required autocomplete="new-password"
                                        placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)">
                                    <span class="toggle-password" toggle="#new_password"
                                        style="position:absolute;top:38px;right:16px;cursor:pointer;z-index:2;"><i
                                            class="bi bi-eye-slash"></i></span>
                                    <small class="text-muted">Mật khẩu phải có ít nhất 8 ký tự</small>
                                </div>
                                <div class="mb-4 position-relative">
                                    <label for="new_password_confirmation" class="form-label">
                                        <i class="bi bi-lock-fill"></i> Nhập lại mật khẩu mới
                                    </label>
                                    <input type="password" class="form-control" id="new_password_confirmation"
                                        name="new_password_confirmation" required autocomplete="new-password"
                                        placeholder="Nhập lại mật khẩu mới">
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
</div>@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(function (el) {
            el.addEventListener('click', function () {
                const input = document.querySelector(this.getAttribute('toggle'));
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
        });
    </script>
@endpush