<footer class="admin-footer" role="contentinfo">
    <div class="container-fluid">
        <div class="footer-content">
            {{-- Left --}}
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted">© {{ date('Y') }}</span>
                <strong class="text-primary">PinkCapy</strong>
                <span class="text-muted d-none d-sm-inline">• All rights reserved.</span>
            </div>

            {{-- Right --}}
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted d-none d-md-inline">
                    Made with <span class="heartbeat">❤️</span> by PinkCapy Team
                </span>
                <span class="badge-version">v3.2.0</span>
            </div>
        </div>
    </div>

    {{-- Flash Message Toast --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
        @php
            $type = session('success') ? 'success' : (session('error') ? 'danger' : (session('warning') ? 'warning' : 'info'));
            $title = session('success') ? 'Thành công' : (session('error') ? 'Lỗi' : (session('warning') ? 'Cảnh báo' : 'Thông tin'));
            $message = session('success') ?? session('error') ?? session('warning') ?? session('info');
        @endphp
        <div id="adminToast" class="admin-toast toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-{{ $type }} text-white">
                <i
                    class="fa fa-{{ $type == 'success' ? 'check-circle' : ($type == 'danger' ? 'times-circle' : ($type == 'warning' ? 'exclamation-triangle' : 'info-circle')) }} me-2"></i>
                <strong class="me-auto">{{ $title }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $message }}
            </div>
        </div>
    @endif
</footer>