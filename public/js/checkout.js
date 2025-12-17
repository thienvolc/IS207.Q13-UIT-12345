/**
 * Checkout Page JavaScript
 * Handles checkout form submission and order placement
 */

class CheckoutManager {
    constructor(config) {
        this.config = config;
        this.cart = null;
        this.selectedItemIds = [];

        this.init();
    }

    init() {
        this.loadSelectedItems();
        this.bindEvents();
        this.loadCart();
    }

    loadSelectedItems() {
        // Get selected items from session storage (set by cart page)
        const stored = sessionStorage.getItem('checkoutItems');
        if (stored) {
            this.selectedItemIds = JSON.parse(stored);
        }
    }

    bindEvents() {
        // Place order button
        document.getElementById('btn-place-order')?.addEventListener('click', () => {
            this.handlePlaceOrder();
        });

        // Payment method selection
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.handlePaymentMethodChange(e.target.value);
            });
        });

        // Form validation on input
        document.querySelectorAll('#checkout-form input, #checkout-form select').forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
        });
    }

    async loadCart() {
        this.showLoading(true);

        try {
            const response = await fetch(this.config.urls.getCart, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
            });

            const data = await response.json();

            if (data.success && data.data.items.length > 0) {
                this.cart = data.data;
                
                // If no selected items from cart page, select all
                if (this.selectedItemIds.length === 0) {
                    this.selectedItemIds = this.cart.items.map(item => item.item_id);
                }

                this.renderCheckout();
            } else {
                this.showEmptyCart();
            }
        } catch (error) {
            console.error('Load cart error:', error);
            this.showError('Không thể tải thông tin giỏ hàng.');
        } finally {
            this.showLoading(false);
        }
    }

    renderCheckout() {
        const content = document.getElementById('checkout-content');
        const orderItemsList = document.getElementById('order-items-list');

        content.style.display = '';

        // Filter to selected items only
        const selectedItems = this.cart.items.filter(item => 
            this.selectedItemIds.includes(item.item_id)
        );

        if (selectedItems.length === 0) {
            this.showEmptyCart();
            return;
        }

        // Clear existing items
        orderItemsList.innerHTML = '';

        // Render order items
        const template = document.getElementById('order-item-template');
        
        selectedItems.forEach(item => {
            const clone = template.content.cloneNode(true);

            // Image
            const img = clone.querySelector('.order-item-image img');
            img.src = item.product_image || '/img/placeholder.png';
            img.alt = item.product_name || 'Sản phẩm';

            // Quantity badge
            clone.querySelector('.order-item-qty').textContent = item.quantity;

            // Name
            clone.querySelector('.order-item-name').textContent = 
                item.product_name || `Sản phẩm #${item.product_id}`;

            // Price
            clone.querySelector('.order-item-price').textContent = 
                this.formatCurrency(item.subtotal || item.price * item.quantity);

            orderItemsList.appendChild(clone);
        });

        // Update summary
        this.updateSummary(selectedItems);
    }

    updateSummary(selectedItems) {
        const subtotal = selectedItems.reduce((sum, item) => {
            return sum + (item.price * item.quantity);
        }, 0);

        const shippingFee = subtotal >= 500000 ? 0 : 30000;
        const total = subtotal + shippingFee;

        document.getElementById('checkout-subtotal').textContent = this.formatCurrency(subtotal);
        document.getElementById('checkout-shipping').textContent = 
            shippingFee === 0 ? 'Miễn phí' : this.formatCurrency(shippingFee);
        document.getElementById('checkout-total').textContent = this.formatCurrency(total);
    }

    handlePaymentMethodChange(method) {
        // Update active state
        document.querySelectorAll('.payment-method-item').forEach(item => {
            const radio = item.querySelector('input[type="radio"]');
            item.classList.toggle('active', radio.value === method);
        });
    }

    validateField(input) {
        const isValid = input.checkValidity();
        
        input.classList.remove('is-valid', 'is-invalid');
        input.classList.add(isValid ? 'is-valid' : 'is-invalid');

        // Show/hide error message
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = isValid ? 'none' : 'block';
        }

        return isValid;
    }

    validateForm() {
        const form = document.getElementById('checkout-form');
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        let isValid = true;
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    async handlePlaceOrder() {
        // Validate form
        if (!this.validateForm()) {
            this.showToast('Vui lòng điền đầy đủ thông tin.', 'warning');
            return;
        }

        const form = document.getElementById('checkout-form');
        const formData = new FormData(form);

        // Prepare order data
        const orderData = {
            items: this.selectedItemIds,
            first_name: formData.get('first_name'),
            middle_name: formData.get('middle_name') || null,
            last_name: formData.get('last_name'),
            phone: formData.get('phone'),
            email: formData.get('email'),
            line1: formData.get('line1'),
            line2: formData.get('line2') || null,
            city: formData.get('city'),
            province: formData.get('province'),
            country: 'Vietnam',
            note: formData.get('note') || null,
            payment_method: document.querySelector('input[name="payment_method"]:checked')?.value || 'cod',
        };

        // Disable button and show loading
        const btn = document.getElementById('btn-place-order');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';

        try {
            const response = await fetch(this.config.urls.checkout, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
                body: JSON.stringify(orderData),
            });

            const data = await response.json();

            if (data.success) {
                // Clear session storage
                sessionStorage.removeItem('checkoutItems');
                
                // Show success and redirect
                this.showToast('Đặt hàng thành công!');
                
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = this.config.urls.orderSuccess + '?cart_id=' + data.data.cart_id;
                    }
                }, 1000);
            } else {
                this.showToast(data.message || 'Không thể đặt hàng. Vui lòng thử lại.', 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Place order error:', error);
            this.showToast('Đã có lỗi xảy ra. Vui lòng thử lại.', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    showLoading(show) {
        const loading = document.getElementById('checkout-loading');
        const content = document.getElementById('checkout-content');
        const empty = document.getElementById('checkout-empty');

        if (show) {
            loading.style.display = 'block';
            content.style.display = 'none';
            empty.style.display = 'none';
        } else {
            loading.style.display = 'none';
        }
    }

    showEmptyCart() {
        document.getElementById('checkout-loading').style.display = 'none';
        document.getElementById('checkout-content').style.display = 'none';
        document.getElementById('checkout-empty').style.display = 'block';
    }

    showError(message) {
        document.getElementById('checkout-loading').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3">${message}</p>
                <a href="/cart" class="btn btn-primary">Quay lại giỏ hàng</a>
            </div>
        `;
    }

    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
        }).format(amount).replace('₫', '₫');
    }
}

// Initialize checkout manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (window.checkoutConfig) {
        window.checkoutManager = new CheckoutManager(window.checkoutConfig);
    }
});
