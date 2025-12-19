@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

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
                            <h4 class="profile-name">{{ Auth::user()->profile?->first_name ?? '' }}
                                {{ Auth::user()->profile?->last_name ?? '' }}</h4>
                        </div>
                        <nav class="profile-nav">
                            <a href="{{ route('account.profile') }}" class="profile-nav-item active">
                                <i class="bi bi-person"></i>
                                <span>Thông tin cá nhân</span>
                            </a>
                            <a href="{{ route('account.orders') }}" class="profile-nav-item">
                                <i class="bi bi-box-seam"></i>
                                <span>Đơn hàng của tôi</span>
                            </a>
                            <a href="{{ route('account.my-posts') }}" class="profile-nav-item">
                                <i class="bi bi-journal-text"></i>
                                <span>Bài viết của tôi</span>
                            </a>
                            <a href="{{ route('account.password') }}" class="profile-nav-item">
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
                            <h3>Thông tin cá nhân</h3>
                            <p class="text-muted">Quản lý thông tin cá nhân của bạn</p>
                        </div>

                        <div class="profile-card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form id="profileForm" enctype="multipart/form-data">
                                @csrf

                                <!-- Avatar Upload -->
                                <div class="grid-row mb-4">
                                    <div class="grid__col-12">
                                        <div class="avatar-upload-section">
                                            <div class="avatar-preview">
                                                @if(Auth::user()->profile?->avatar)
                                                    <img src="{{ Auth::user()->profile->avatar }}" alt="Avatar"
                                                        id="avatarPreview">
                                                @else
                                                    <img src="" alt="Avatar" id="avatarPreview" style="display: none;">
                                                    <i class="bi bi-person-circle" id="avatarIcon"></i>
                                                @endif
                                            </div>
                                            <div class="avatar-upload-info">
                                                <label for="avatar" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-camera"></i> Chọn ảnh
                                                </label>
                                                <input type="file" name="avatar" id="avatar" class="d-none"
                                                    accept="image/*">
                                                <p class="text-muted mt-2 mb-0">Dung lượng tối đa 2MB. Định dạng: JPG, PNG
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Họ, Tên đệm, Tên -->
                                <div class="grid-row mb-3">
                                    <div class="grid__col-4">
                                        <label for="first_name" class="form-label">
                                            <i class="bi bi-person"></i> Họ
                                        </label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            value="{{ Auth::user()->profile?->first_name ?? '' }}" placeholder="Nguyễn">
                                    </div>

                                    <div class="grid__col-4">
                                        <label for="middle_name" class="form-label">
                                            <i class="bi bi-person"></i> Tên đệm
                                        </label>
                                        <input type="text" name="middle_name" id="middle_name" class="form-control"
                                            value="{{ Auth::user()->profile?->middle_name ?? '' }}" placeholder="Văn">
                                    </div>

                                    <div class="grid__col-4">
                                        <label for="last_name" class="form-label">
                                            <i class="bi bi-person"></i> Tên
                                        </label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            value="{{ Auth::user()->profile?->last_name ?? '' }}" placeholder="A">
                                    </div>
                                </div>

                                <!-- Email và Số điện thoại -->
                                <div class="grid-row mb-3">
                                    <div class="grid__col-6">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope"></i> Email
                                        </label>
                                        <input type="email" id="email" class="form-control"
                                            value="{{ Auth::user()->email }}" readonly>
                                        <small class="text-muted">Email không thể thay đổi</small>
                                    </div>

                                    <div class="grid__col-6">
                                        <label for="phone" class="form-label">
                                            <i class="bi bi-telephone"></i> Số điện thoại
                                        </label>
                                        <input type="tel" name="phone" id="phone" class="form-control"
                                            value="{{ Auth::user()->phone ?? '' }}" placeholder="0123456789">
                                    </div>
                                </div>

                                <!-- Giới thiệu -->
                                <div class="grid-row mb-3">
                                    <div class="grid__col-12">
                                        <label for="profile" class="form-label">
                                            <i class="bi bi-card-text"></i> Giới thiệu
                                        </label>
                                        <textarea name="profile" id="profile" class="form-control" rows="3"
                                            placeholder="Viết vài dòng về bản thân...">{{ Auth::user()->profile?->bio ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="profile-form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('profileForm');
                const avatarInput = document.getElementById('avatar');
                const avatarPreview = document.getElementById('avatarPreview');
                const avatarIcon = document.getElementById('avatarIcon');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Store original data for reset functionality
                let originalData = {
                    first_name: document.getElementById('first_name').value,
                    middle_name: document.getElementById('middle_name').value,
                    last_name: document.getElementById('last_name').value,
                    phone: document.getElementById('phone').value,
                    profile: document.getElementById('profile').value,
                    avatar: avatarPreview.src || ''
                };

                // Avatar preview
                if (avatarInput) {
                    avatarInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (file) {
                            // Validate file size (2MB)
                            if (file.size > 2 * 1024 * 1024) {
                                showAlert('Kích thước ảnh không được vượt quá 2MB', 'danger');
                                avatarInput.value = '';
                                return;
                            }

                            // Preview
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                avatarPreview.src = e.target.result;
                                avatarPreview.style.display = 'block';
                                if (avatarIcon) {
                                    avatarIcon.style.display = 'none';
                                }
                            };
                            reader.readAsDataURL(file);

                            showAlert('Ảnh đã được chọn. Nhấn "Lưu thay đổi" để cập nhật.', 'info');
                        }
                    });
                }

                // Submit form
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';

                    const formData = new FormData(form);

                    try {
                        const response = await fetch('{{ route("account.profile.update") }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            throw new Error(result.message || 'Không thể cập nhật thông tin');
                        }

                        showAlert('Cập nhật thông tin thành công!', 'success');

                        // Update data from server response
                        if (result.data) {
                            // Update form fields with server data
                            if (result.data.first_name !== undefined) {
                                document.getElementById('first_name').value = result.data.first_name || '';
                            }
                            if (result.data.middle_name !== undefined) {
                                document.getElementById('middle_name').value = result.data.middle_name || '';
                            }
                            if (result.data.last_name !== undefined) {
                                document.getElementById('last_name').value = result.data.last_name || '';
                            }
                            if (result.data.phone !== undefined) {
                                document.getElementById('phone').value = result.data.phone || '';
                            }

                            // Update avatar from server response
                            if (result.data.avatar) {
                                avatarPreview.src = result.data.avatar;
                                avatarPreview.style.display = 'block';
                                if (avatarIcon) {
                                    avatarIcon.style.display = 'none';
                                }

                                // Update sidebar avatar
                                const sidebarAvatar = document.querySelector('.profile-avatar img');
                                const sidebarIcon = document.querySelector('.profile-avatar i');
                                if (sidebarAvatar) {
                                    sidebarAvatar.src = result.data.avatar;
                                    sidebarAvatar.style.display = 'block';
                                } else {
                                    // Create img element if not exists
                                    const sidebarAvatarContainer = document.querySelector('.profile-avatar');
                                    if (sidebarAvatarContainer) {
                                        const newImg = document.createElement('img');
                                        newImg.src = result.data.avatar;
                                        newImg.alt = 'Avatar';
                                        sidebarAvatarContainer.insertBefore(newImg, sidebarAvatarContainer.firstChild);
                                    }
                                }
                                if (sidebarIcon) {
                                    sidebarIcon.style.display = 'none';
                                }
                            }

                            // Update sidebar name
                            const fullName = [result.data.first_name, result.data.middle_name, result.data.last_name]
                                .filter(n => n).join(' ');
                            if (fullName) {
                                document.querySelector('.profile-name').textContent = fullName;
                            }
                        }

                        // Clear file input
                        avatarInput.value = '';

                    } catch (error) {
                        console.error('Error updating profile:', error);
                        showAlert(error.message || 'Không thể cập nhật thông tin', 'danger');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });

                // Helper function to show alerts
                function showAlert(message, type = 'info') {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                    alertDiv.innerHTML = `
                    <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                    const cardBody = document.querySelector('.profile-card-body');
                    cardBody.insertBefore(alertDiv, cardBody.firstChild);

                    // Auto dismiss after 5 seconds
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                }
            });
        </script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
    @endpush
@endsection