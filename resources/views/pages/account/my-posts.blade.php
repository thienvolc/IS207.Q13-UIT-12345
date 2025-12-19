@extends('layouts.app')

@section('title', 'Bài viết của tôi')

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
                        <a href="{{ route('account.my-posts') }}" class="profile-nav-item active">
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
                        <h3>Bài viết của tôi</h3>
                        <p class="text-muted">Quản lý các bài viết bạn đã đăng</p>
                    </div>

                    <div class="profile-card-body">
                        <!-- Create new post button -->
                        <div class="mb-4">
                            <a href="{{ route('blog.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tạo bài viết mới
                            </a>
                        </div>

                        <!-- Posts list -->
                            <!-- Posts list -->
                            @if(isset($posts) && count($posts) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tiêu đề</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tạo</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($posts as $post)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($post->thumb)
                                                    <img src="{{ $post->thumb }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $post->title }}</div>
                                                        @if($post->summary)
                                                        <small class="text-muted">{{ Str::limit($post->summary, 80) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                @if($post->status == 1)
                                                    <span class="badge bg-secondary">Nháp</span>
                                                @elseif($post->status == 2)
                                                    <span class="badge bg-success">Đã xuất bản</span>
                                                @elseif($post->status == 3)
                                                    <span class="badge bg-danger">Đã xóa</span>
                                                @elseif($post->status == 4)
                                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                                @else
                                                    <span class="badge bg-secondary">Không xác định</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">{{ \Carbon\Carbon::parse($post->createdAt)->format('d/m/Y') }}</td>
                                            <td class="align-middle">
                                                @if($post->status == 2)
                                                    <a href="/tin-tuc/{{ $post->slug }}" class="btn btn-outline-primary" target="_blank"><i class="bi bi-eye"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="bi bi-journal-x" style="font-size: 3rem; color: #ccc;"></i>
                                <h4 class="mt-3">Chưa có bài viết nào</h4>
                                <p class="text-muted">Bạn chưa đăng bài viết nào. Hãy tạo bài viết đầu tiên của bạn!</p>
                                <a href="{{ route('blog.create') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle"></i> Tạo bài viết mới
                                </a>
                            </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Fetch user's posts
    async function loadMyPosts() {
        const listContainer = document.getElementById('posts-list');
        
        try {
            const response = await fetch('/api/me/blogs', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'include'
            });
            
            if (!response.ok) {
                throw new Error('Failed to load posts');
            }
            
            const data = await response.json();
            const posts = data.data || [];
            
            if (posts.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x" style="font-size: 3rem; color: #ccc;"></i>
                        <h4 class="mt-3">Chưa có bài viết nào</h4>
                        <p class="text-muted">Bạn chưa đăng bài viết nào. Hãy tạo bài viết đầu tiên của bạn!</p>
                        <a href="${'{{ route("blog.create") }}'}" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle"></i> Tạo bài viết mới
                        </a>
                    </div>
                `;
                return;
            }
            
            // Render posts
            let html = '<div class="table-responsive"><table class="table table-hover">';
            html += '<thead><tr><th>Tiêu đề</th><th>Trạng thái</th><th>Ngày tạo</th><th>Hành động</th></tr></thead><tbody>';
            
            posts.forEach(post => {
                const statusBadge = getStatusBadge(post.status);
                const createdDate = new Date(post.createdAt).toLocaleDateString('vi-VN');
                
                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                ${post.thumb ? `<img src="${post.thumb}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">` : ''}
                                <div>
                                    <div class="fw-bold">${post.title}</div>
                                    ${post.summary ? `<small class="text-muted">${post.summary.substring(0, 80)}...</small>` : ''}
                                </div>
                            </div>
                        </td>
                        <td>${statusBadge}</td>
                        <td>${createdDate}</td>
                        <td>
                            ${post.status === 2 ? `<a href="/tin-tuc/${post.slug}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-eye"></i></a>` : ''}
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            listContainer.innerHTML = html;
            
        } catch (error) {
            console.error('Error loading posts:', error);
            listContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> Không thể tải danh sách bài viết. Vui lòng thử lại sau.
                </div>
            `;
        }
    }
    
    function getStatusBadge(status) {
        const statusMap = {
            1: '<span class="badge bg-secondary">Nháp</span>',
            2: '<span class="badge bg-success">Đã xuất bản</span>',
            3: '<span class="badge bg-danger">Đã xóa</span>',
            4: '<span class="badge bg-warning text-dark">Chờ duyệt</span>'
        };
        return statusMap[status] || '<span class="badge bg-secondary">Không xác định</span>';
    }
    
    // Load posts when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadMyPosts();
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
<style>
    .table td {
        vertical-align: middle;
    }
</style>
@endpush
@endsection
