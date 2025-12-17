@extends('layouts.app')
@section('title', 'PinkCapy - Đặt hàng thành công')
@section('content')
<div class="order-success-wrapper">
    <div class="grid">
        <div class="order-success-card">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            
            <h1 class="success-title">Đặt hàng thành công!</h1>
            
            <p class="success-message">
                Cảm ơn bạn đã đặt hàng tại PinkCapy. Đơn hàng của bạn đang được xử lý.
            </p>

            @if(isset($cartId))
            <div class="order-info">
                <div class="order-info-item">
                    <span class="order-info-label">Mã đơn hàng:</span>
                    <span class="order-info-value">#{{ $cartId }}</span>
                </div>
            </div>
            @endif

            <div class="success-steps">
                <div class="step-item completed">
                    <div class="step-icon">
                        <i class="bi bi-check"></i>
                    </div>
                    <span>Đặt hàng</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item active">
                    <div class="step-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <span>Xử lý</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <span>Giao hàng</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-icon">
                        <i class="bi bi-house-check"></i>
                    </div>
                    <span>Hoàn thành</span>
                </div>
            </div>

            <div class="success-notice">
                <i class="bi bi-envelope"></i>
                <p>Chúng tôi sẽ gửi email xác nhận đơn hàng đến địa chỉ email của bạn.</p>
            </div>

            <div class="success-actions">
                <a href="{{ route('account.orders') }}" class="btn btn-primary">
                    <i class="bi bi-list-check"></i> Xem đơn hàng
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline">
                    <i class="bi bi-bag"></i> Tiếp tục mua sắm
                </a>
            </div>

            <div class="success-contact">
                <p>Có thắc mắc? Liên hệ hotline: <strong>1900 1234</strong></p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.order-success-wrapper {
    padding: 3rem 0;
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.order-success-card {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    padding: 3rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.success-icon {
    margin-bottom: 1.5rem;
}

.success-icon i {
    font-size: 5rem;
    color: #198754;
    animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

.success-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.success-message {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.order-info {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 2rem;
}

.order-info-item {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.order-info-label {
    color: var(--text-secondary);
}

.order-info-value {
    font-weight: 600;
    color: var(--primary);
}

/* Steps */
.success-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fafafa;
    border-radius: 12px;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.step-item span {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.step-item.completed .step-icon {
    background: #198754;
    color: white;
}

.step-item.completed span {
    color: #198754;
}

.step-item.active .step-icon {
    background: var(--primary);
    color: white;
    animation: pulse 2s infinite;
}

.step-item.active span {
    color: var(--primary);
    font-weight: 600;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(246, 36, 78, 0.4); }
    50% { box-shadow: 0 0 0 10px rgba(246, 36, 78, 0); }
}

.step-line {
    width: 40px;
    height: 2px;
    background: #e0e0e0;
    margin: 0 0.5rem;
    margin-bottom: 1.5rem;
}

/* Notice */
.success-notice {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #e8f4fd;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.success-notice i {
    font-size: 1.25rem;
    color: #0d6efd;
}

.success-notice p {
    margin: 0;
    color: #0d6efd;
    font-size: 0.875rem;
}

/* Actions */
.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.success-actions .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.success-actions .btn-primary {
    background: var(--primary);
    color: white;
    border: none;
}

.success-actions .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.success-actions .btn-outline {
    background: transparent;
    color: var(--text-primary);
    border: 2px solid #e0e0e0;
}

.success-actions .btn-outline:hover {
    border-color: var(--primary);
    color: var(--primary);
}

/* Contact */
.success-contact {
    color: var(--text-muted);
    font-size: 0.875rem;
}

.success-contact strong {
    color: var(--primary);
}

/* Responsive */
@media (max-width: 576px) {
    .order-success-card {
        padding: 2rem 1.5rem;
    }

    .success-icon i {
        font-size: 4rem;
    }

    .success-title {
        font-size: 1.5rem;
    }

    .success-steps {
        padding: 1rem;
    }

    .step-icon {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }

    .step-item span {
        font-size: 0.6875rem;
    }

    .step-line {
        width: 20px;
    }

    .success-actions {
        flex-direction: column;
    }

    .success-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush
@endsection