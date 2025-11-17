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
                            <img src="" alt="Avatar" style="display: none;">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h4 class="profile-name">Loading...</h5>
                    </div>
                    <nav class="profile-nav">
                        <a href="{{ route('account.profile') }}" class="profile-nav-item active">
                            <i class="bi bi-person"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                        <a href="/cart" class="profile-nav-item">
                            <i class="bi bi-box-seam"></i>
                            <span>Đơn hàng của tôi</span>
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
                        <h3>Thông tin cá nhân</h4>
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
                                            <img src="" alt="Avatar" id="avatarPreview" style="display: none;">
                                            <i class="bi bi-person-circle" id="avatarIcon"></i>
                                        </div>
                                        <div class="avatar-upload-info">
                                            <label for="avatar" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-camera"></i> Chọn ảnh
                                            </label>
                                            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                                            <p class="text-muted mt-2 mb-0">Dung lượng tối đa 2MB. Định dạng: JPG, PNG</p>
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
                                    <input type="text"
                                        name="first_name"
                                        id="first_name"
                                        class="form-control"
                                        placeholder="Nguyễn">
                                </div>

                                <div class="grid__col-4">
                                    <label for="middle_name" class="form-label">
                                        <i class="bi bi-person"></i> Tên đệm
                                    </label>
                                    <input type="text"
                                        name="middle_name"
                                        id="middle_name"
                                        class="form-control"
                                        placeholder="Văn">
                                </div>

                                <div class="grid__col-4">
                                    <label for="last_name" class="form-label">
                                        <i class="bi bi-person"></i> Tên
                                    </label>
                                    <input type="text"
                                        name="last_name"
                                        id="last_name"
                                        class="form-control"
                                        placeholder="A">
                                </div>
                            </div>

                            <!-- Email và Số điện thoại -->
                            <div class="grid-row mb-3">
                                <div class="grid__col-6">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <input type="email"
                                        id="email"
                                        class="form-control"
                                        readonly>
                                    <small class="text-muted">Email không thể thay đổi</small>
                                </div>

                                <div class="grid__col-6">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i> Số điện thoại
                                    </label>
                                    <input type="tel"
                                        name="phone"
                                        id="phone"
                                        class="form-control"
                                        placeholder="0123456789">
                                </div>
                            </div>

                            <!-- Giới thiệu -->
                            <div class="grid-row mb-3">
                                <div class="grid__col-12">
                                    <label for="profile" class="form-label">
                                        <i class="bi bi-card-text"></i> Giới thiệu
                                    </label>
                                    <textarea name="profile"
                                        id="profile"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Viết vài dòng về bản thân..."></textarea>
                                </div>
                            </div>

                            <div class="profile-form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Lưu thay đổi
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                                    <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
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
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarIcon = document.getElementById('avatarIcon');
        const resetBtn = document.getElementById('resetBtn');

        let originalData = {};
        let uploadedAvatarUrl = null;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Get auth token from localStorage or cookie
        const authToken = localStorage.getItem('auth_token') || getCookie('auth_token');

        // Load user profile
        async function loadProfile() {
            // Nếu không có token, hiển thị dữ liệu mẫu
            if (!authToken) {
                loadDemoData();
                return;
            }

            try {
                const response = await fetch('/api/me', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load profile');
                }

                const result = await response.json();
                const user = result.data;

                // Store original data
                originalData = {
                    first_name: user.first_name || '',
                    middle_name: user.middle_name || '',
                    last_name: user.last_name || '',
                    phone: user.phone || '',
                    profile: user.profile || '',
                    avatar: user.avatar || ''
                };

                // Fill form
                document.getElementById('first_name').value = user.first_name || '';
                document.getElementById('middle_name').value = user.middle_name || '';
                document.getElementById('last_name').value = user.last_name || '';
                document.getElementById('email').value = user.email || '';
                document.getElementById('phone').value = user.phone || '';
                document.getElementById('profile').value = user.profile || '';

                // Update sidebar name
                const fullName = [user.first_name, user.middle_name, user.last_name]
                    .filter(n => n).join(' ') || user.email;
                document.querySelector('.profile-name').textContent = fullName;

                // Set avatar
                if (user.avatar) {
                    avatarPreview.src = user.avatar;
                    avatarPreview.style.display = 'block';
                    avatarIcon.style.display = 'none';

                    // Update sidebar avatar
                    const sidebarAvatar = document.querySelector('.profile-avatar img');
                    const sidebarIcon = document.querySelector('.profile-avatar i');
                    if (sidebarAvatar) {
                        sidebarAvatar.src = user.avatar;
                        sidebarAvatar.style.display = 'block';
                    }
                    if (sidebarIcon) {
                        sidebarIcon.style.display = 'none';
                    }
                }

            } catch (error) {
                console.error('Error loading profile:', error);
                showAlert('Không thể tải thông tin profile. Hiển thị dữ liệu mẫu.', 'warning');
                loadDemoData();
            }
        }

        // Load demo data for preview
        function loadDemoData() {
            const demoUser = {
                first_name: 'Nguyễn',
                middle_name: 'Văn',
                last_name: 'A',
                email: 'demo@pinkcapy.com',
                phone: '0123456789',
                profile: 'Đây là trang profile demo',
                avatar: ''
            };

            originalData = demoUser;

            document.getElementById('first_name').value = demoUser.first_name;
            document.getElementById('middle_name').value = demoUser.middle_name;
            document.getElementById('last_name').value = demoUser.last_name;
            document.getElementById('email').value = demoUser.email;
            document.getElementById('phone').value = demoUser.phone;
            document.getElementById('profile').value = demoUser.profile;

            const fullName = [demoUser.first_name, demoUser.middle_name, demoUser.last_name]
                .filter(n => n).join(' ');
            document.querySelector('.profile-name').textContent = fullName;
        }

        // Avatar preview
        if (avatarInput) {
            avatarInput.addEventListener('change', async function(e) {
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
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                        avatarPreview.style.display = 'block';
                        avatarIcon.style.display = 'none';
                    };
                    reader.readAsDataURL(file);

                    // Upload to server (you need to implement upload endpoint)
                    await uploadAvatar(file);
                }
            });
        }

        // Upload avatar
        async function uploadAvatar(file) {
            const formData = new FormData();
            formData.append('avatar', file);

            try {
                // TODO: Replace with your actual upload endpoint
                // For now, we'll use a placeholder URL
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedAvatarUrl = e.target.result;
                };
                reader.readAsDataURL(file);

                showAlert('Ảnh đã được chọn. Nhấn "Lưu thay đổi" để cập nhật.', 'info');
            } catch (error) {
                console.error('Error uploading avatar:', error);
                showAlert('Không thể tải ảnh lên', 'danger');
            }
        }

        // Submit form
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';

            const formData = {
                first_name: document.getElementById('first_name').value.trim() || null,
                middle_name: document.getElementById('middle_name').value.trim() || null,
                last_name: document.getElementById('last_name').value.trim() || null,
                phone: document.getElementById('phone').value.trim() || null,
                profile: document.getElementById('profile').value.trim() || null,
            };

            // Add avatar if uploaded
            if (uploadedAvatarUrl) {
                formData.avatar = uploadedAvatarUrl;
            }

            // Nếu không có token, chỉ hiển thị thông báo demo
            if (!authToken) {
                setTimeout(() => {
                    showAlert('Chế độ demo - Dữ liệu không được lưu thực tế. Vui lòng đăng nhập để sử dụng đầy đủ tính năng.', 'info');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 1000);
                return;
            }

            try {
                const response = await fetch('/api/me', {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to update profile');
                }

                showAlert('Cập nhật thông tin thành công!', 'success');

                // Reload profile
                await loadProfile();
                uploadedAvatarUrl = null;

            } catch (error) {
                console.error('Error updating profile:', error);
                showAlert(error.message || 'Không thể cập nhật thông tin', 'danger');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Reset form
        resetBtn.addEventListener('click', function() {
            document.getElementById('first_name').value = originalData.first_name;
            document.getElementById('middle_name').value = originalData.middle_name;
            document.getElementById('last_name').value = originalData.last_name;
            document.getElementById('phone').value = originalData.phone;
            document.getElementById('profile').value = originalData.profile;

            if (originalData.avatar) {
                avatarPreview.src = originalData.avatar;
                avatarPreview.style.display = 'block';
                avatarIcon.style.display = 'none';
            } else {
                avatarPreview.style.display = 'none';
                avatarIcon.style.display = 'block';
            }

            avatarInput.value = '';
            uploadedAvatarUrl = null;
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

        // Helper function to get cookie
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        // Load profile on page load
        loadProfile();
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
@endpush
@endsection