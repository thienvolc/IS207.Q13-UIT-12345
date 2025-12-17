// Product Detail Page JS

document.addEventListener("DOMContentLoaded", function () {
    // Quantity Selector
    const qtyInput = document.getElementById("product-quantity");
    const decreaseBtn = document.getElementById("decrease-qty");
    const increaseBtn = document.getElementById("increase-qty");
    const maxQty = parseInt(qtyInput?.max || 1);

    decreaseBtn?.addEventListener("click", function () {
        let currentVal = parseInt(qtyInput.value);
        if (currentVal > 1) {
            qtyInput.value = currentVal - 1;
        }
    });

    increaseBtn?.addEventListener("click", function () {
        let currentVal = parseInt(qtyInput.value);
        if (currentVal < maxQty) {
            qtyInput.value = currentVal + 1;
        }
    });

    qtyInput?.addEventListener("change", function () {
        let val = parseInt(this.value);
        if (val < 1) this.value = 1;
        if (val > maxQty) this.value = maxQty;
    });

    // Add to Cart
    document.querySelectorAll(".add-to-cart").forEach((btn) => {
        btn.addEventListener("click", async function () {
            const productId = this.dataset.productId;
            const quantity = parseInt(qtyInput?.value || 1);
            
            // Disable button while processing
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Đang thêm...';
            
            try {
                const response = await fetch('/api/web/cart/items', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify({
                        product_id: parseInt(productId),
                        quantity: quantity,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                    updateHeaderCartCount();
                } else {
                    showToast(data.message || 'Không thể thêm sản phẩm vào giỏ hàng.', 'error');
                }
            } catch (error) {
                console.error('Add to cart error:', error);
                showToast('Đã có lỗi xảy ra. Vui lòng thử lại.', 'error');
            } finally {
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    });

    // Buy Now
    document.querySelectorAll(".buy-now").forEach((btn) => {
        btn.addEventListener("click", async function () {
            const productId = this.dataset.productId;
            const quantity = parseInt(qtyInput?.value || 1);
            
            // Disable button while processing
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Đang xử lý...';
            
            try {
                const response = await fetch('/api/web/cart/items', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify({
                        product_id: parseInt(productId),
                        quantity: quantity,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirect to checkout
                    window.location.href = '/checkout';
                } else {
                    showToast(data.message || 'Không thể thêm sản phẩm vào giỏ hàng.', 'error');
                    this.disabled = false;
                    this.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Buy now error:', error);
                showToast('Đã có lỗi xảy ra. Vui lòng thử lại.', 'error');
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    });
});

// Toast notification
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Toast styles
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 9999;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        border-left: 4px solid ${type === 'success' ? '#198754' : type === 'error' ? '#dc3545' : '#0dcaf0'};
    `;
    
    const icon = toast.querySelector('i');
    if (icon) {
        icon.style.color = type === 'success' ? '#198754' : type === 'error' ? '#dc3545' : '#0dcaf0';
    }
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after 3s
    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Update cart count in header
async function updateHeaderCartCount() {
    try {
        const response = await fetch('/api/web/cart');
        const data = await response.json();
        
        if (data.success && data.data) {
            const count = data.data.total_quantity || data.data.items?.length || 0;
            
            // Update cart count badge in header
            let countEl = document.querySelector('.header-cart-count');
            if (!countEl) {
                // Try to find cart icon and add count badge
                const cartIcon = document.querySelector('a[href*="cart"] .bi-bag, a[href*="cart"] .bi-cart');
                if (cartIcon) {
                    countEl = document.createElement('span');
                    countEl.className = 'header-cart-count';
                    countEl.style.cssText = `
                        position: absolute;
                        top: -8px;
                        right: -8px;
                        background: #f6244e;
                        color: white;
                        font-size: 0.6875rem;
                        font-weight: 600;
                        min-width: 18px;
                        height: 18px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 0 4px;
                    `;
                    cartIcon.parentElement.style.position = 'relative';
                    cartIcon.parentElement.appendChild(countEl);
                }
            }
            
            if (countEl) {
                countEl.textContent = count;
                countEl.style.display = count > 0 ? 'flex' : 'none';
            }
        }
    } catch (error) {
        console.error('Update cart count error:', error);
    }
}
