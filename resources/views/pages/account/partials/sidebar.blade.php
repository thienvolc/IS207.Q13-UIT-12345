<div class="profile-sidebar">
    <div class="profile-user-info">
        <div class="profile-user-avatar">
            @if(Auth::user()->profile?->avatar)
                <img src="{{ Auth::user()->profile->avatar }}" alt="Avatar">
            @else
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=d70018&color=fff"
                    alt="Avatar">
            @endif
        </div>
        <div class="profile-user-name">
            {{ Auth::user()->profile?->first_name ?? Auth::user()->name }}
            {{ Auth::user()->profile?->last_name ?? '' }}
        </div>
        <div class="profile-user-email">
            {{ Auth::user()->email }}
        </div>
    </div>
    <div class="profile-usermenu">
        <ul class="profile-usermenu-nav">
            <li class="{{ request()->routeIs('account.profile') ? 'active' : '' }}">
                <a href="{{ route('account.profile') }}">
                    <i class="bi bi-person-badge"></i>
                    Thông tin cá nhân
                </a>
            </li>
            <li class="{{ request()->routeIs('account.orders*') ? 'active' : '' }}">
                <a href="{{ route('account.orders') }}">
                    <i class="bi bi-box-seam"></i>
                    Đơn hàng của tôi
                </a>
            </li>

            <li class="{{ request()->routeIs('account.password') ? 'active' : '' }}">
                <a href="{{ route('account.password') }}">
                    <i class="bi bi-shield-lock"></i>
                    Đổi mật khẩu
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="d-inline profile-logout-btn">
                    @csrf
                    <button type="submit">
                        <i class="bi bi-box-arrow-right"></i>
                        Đăng xuất
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>