<footer class="admin-footer" role="contentinfo">
    <div class="container-fluid">
        <div class="footer-content">

            <!-- Main Footer Line -->
            <div class="footer-main d-flex justify-content-between align-items-center flex-wrap gap-3 py-4">
                <div class="footer-left">
                    <span class="text-muted me-2">© {{ date('Y') }}</span>
                    <strong class="text-pinkcapy fw-bold">PinkCapy</strong>
                    <span class="text-muted ms-1">• All rights reserved.</span>
                </div>

                <div class="footer-right d-flex align-items-center gap-4 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Made with</span>
                        <span class="heartbeat">❤️</span>
                        <span class="text-muted">by PinkCapy Team</span>
                    </div>
                    <div class="badge-version">
                        v3.2.0.0
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Message Toast (đẹp như Bootstrap 5 + hiệu ứng mượt) -->
    @if(session('success') || session('error') || session('warning') || session('info'))
        <div id="adminToast" class="admin-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header {{ session('success') ? 'bg-success text-white' : (session('error') ? 'bg-danger text-white' : (session('warning') ? 'bg-warning text-dark' : 'bg-info text-white')) }}">
                <strong class="me-auto">
                    @if(session('success')) Success
                    @elseif(session('error')) Error
                    @elseif(session('warning')) Warning
                    @else Info @endif
                </strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body fw-medium">
                {{ session('success') ?? session('error') ?? session('warning') ?? session('info') }}
            </div>
        </div>
    @endif
</footer>