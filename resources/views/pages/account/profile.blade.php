@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
    <div class="profile-page-container">
        <div class="container py-5">
            <div class="row">
                <!-- Sidebar Menu (Admin Style) -->
                <div class="col-lg-3 mb-4 mb-lg-0">
                    @include('pages.account.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="profile-content">
                        <div class="profile-header">
                            <h3><i class="bi bi-person-gear"></i> Thông tin cá nhân</h3>
                            <p class="text-muted mb-0">Quản lý và cập nhật thông tin tài khoản của bạn</p>
                        </div>

                        <div class="profile-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
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

                            <form id="profileForm" enctype="multipart/form-data" class="profile-form">
                                @csrf

                                <!-- Avatar Upload Box -->
                                <div class="avatar-upload-box">
                                    <img src="{{ Auth::user()->profile?->avatar ?? 'https://ui-avatars.com/api/?name=' . Auth::user()->name . '&background=d70018&color=fff' }}"
                                        alt="Avatar Preview" class="avatar-preview-small" id="avatarPreview">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">Ảnh đại diện</h5>
                                        <p class="text-muted small mb-2">Định dạng PNG, JPG. Tối đa 2MB.</p>
                                        <label for="avatar" class="btn btn-sm btn-outline-primary btn-upload-avatar">
                                            <i class="bi bi-cloud-upload"></i> Tải ảnh mới
                                        </label>
                                        <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="first_name" class="form-label">Họ</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="bi bi-person"></i></span>
                                            <input type="text" name="first_name" id="first_name"
                                                class="form-control border-start-0 ps-0"
                                                value="{{ Auth::user()->profile?->first_name ?? '' }}" placeholder="Nguyễn">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="middle_name" class="form-label">Tên đệm</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="bi bi-person"></i></span>
                                            <input type="text" name="middle_name" id="middle_name"
                                                class="form-control border-start-0 ps-0"
                                                value="{{ Auth::user()->profile?->middle_name ?? '' }}" placeholder="Văn">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="last_name" class="form-label">Tên</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="bi bi-person"></i></span>
                                            <input type="text" name="last_name" id="last_name"
                                                class="form-control border-start-0 ps-0"
                                                value="{{ Auth::user()->profile?->last_name ?? '' }}" placeholder="An">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                            <input type="email" id="email" class="form-control bg-light"
                                                value="{{ Auth::user()->email }}" readonly>
                                        </div>
                                        <div class="form-text text-muted"><i class="bi bi-info-circle"></i> Email không thể
                                            thay đổi</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="bi bi-telephone"></i></span>
                                            <input type="tel" name="phone" id="phone"
                                                class="form-control border-start-0 ps-0"
                                                value="{{ Auth::user()->phone ?? '' }}" placeholder="0912...">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="profile" class="form-label">Giới thiệu</label>
                                        <textarea name="profile" id="profile" class="form-control" rows="3"
                                            placeholder="Chia sẻ đôi điều về bạn...">{{ Auth::user()->profile?->bio ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-profile-save">
                                        <i class="bi bi-save"></i> Lưu thay đổi
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