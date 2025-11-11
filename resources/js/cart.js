// ============================================
// Cart Management - Add to Cart Functionality
// ============================================

// Check if user is logged in
function isUserLoggedIn() {
    // TODO: Implement proper authentication check
    // For now, check if there's a session or token
    return false; // Tạm thời return false để dùng guest cart
}

// Guest Cart - Save to localStorage
function addToGuestCart(productId, quantity = 1) {
    let guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]');
    
    // Check if product already exists
    const existingItem = guestCart.find(item => item.product_id === productId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        guestCart.push({
            product_id: productId,
            quantity: quantity,
            added_at: new Date().toISOString()
        });
    }
    
    localStorage.setItem('guestCart', JSON.stringify(guestCart));
    updateGuestCartCount();
    return true;
}

// Get guest cart count
function getGuestCartCount() {
    const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]');
    return guestCart.length;
}

// Update guest cart count in UI
function updateGuestCartCount() {
    const cartCount = getGuestCartCount();
    const cartBadge = document.querySelector('.header-cart__count, .cart-count');
    
    if (cartBadge) {
        cartBadge.textContent = cartCount;
        if (cartCount > 0) {
            cartBadge.style.display = 'inline-block';
        } else {
            cartBadge.style.display = 'none';
        }
    }
}

// Add to Cart function
async function addToCart(productId, quantity = 1) {
    // If user not logged in, use guest cart
    if (!isUserLoggedIn()) {
        const success = addToGuestCart(productId, quantity);
        if (success) {
            showToast('Đã thêm vào giỏ hàng!', 'success');
        }
        return;
    }
    
    // If logged in, use API
    try {
        const response = await fetch('/api/me/carts/items', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'include', // Include cookies for authentication
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Show success message
            showToast('Đã thêm vào giỏ hàng!', 'success');
            
            // Update cart count in header
            updateCartCount();
            
            return data;
        } else if (response.status === 401) {
            // User not logged in, fallback to guest cart
            const success = addToGuestCart(productId, quantity);
            if (success) {
                showToast('Đã thêm vào giỏ hàng! (Đăng nhập để lưu vĩnh viễn)', 'success');
            }
            return null;
        } else {
            // Show error message
            showToast(data.message || 'Không thể thêm vào giỏ hàng', 'error');
            return null;
        }
    } catch (error) {
        console.error('Lỗi khi thêm vào giỏ hàng:', error);
        // Fallback to guest cart on error
        const success = addToGuestCart(productId, quantity);
        if (success) {
            showToast('Đã thêm vào giỏ hàng!', 'success');
        } else {
            showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
        }
        return null;
    }
}

// Update cart count in header
async function updateCartCount() {
    try {
        const response = await fetch('/api/me/carts', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
            credentials: 'include'
        });

        if (response.ok) {
            const data = await response.json();
            const cartCount = data.data?.items?.length || 0;
            
            // Update cart badge
            const cartBadge = document.querySelector('.header-cart__count, .cart-count');
            if (cartBadge) {
                cartBadge.textContent = cartCount;
                if (cartCount > 0) {
                    cartBadge.style.display = 'inline-block';
                } else {
                    cartBadge.style.display = 'none';
                }
            }
        }
    } catch (error) {
        console.error('Lỗi khi cập nhật số lượng giỏ hàng:', error);
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'}"></i>
            <span>${message}</span>
        </div>
    `;

    // Add to body
    document.body.appendChild(toast);

    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);

    // Auto hide after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Initialize add to cart buttons
document.addEventListener('DOMContentLoaded', function() {
    // Handle all add to cart buttons
    document.addEventListener('click', function(e) {
        const addButton = e.target.closest('.btn-add-cart, .btn-add-floating');
        if (addButton) {
            e.preventDefault();
            
            // Get product ID from data attribute or closest product card
            const productCard = addButton.closest('.product-item');
            if (productCard) {
                const productLink = productCard.querySelector('a[href*="/san-pham/"]');
                if (productLink) {
                    // Extract product slug from URL
                    const href = productLink.getAttribute('href');
                    const match = href.match(/\/san-pham\/([^\/]+)/);
                    if (match) {
                        const slug = match[1];
                        
                        // Get product ID from data attribute if available
                        const productId = productCard.dataset.productId || addButton.dataset.productId;
                        
                        if (productId) {
                            addToCart(parseInt(productId), 1);
                        } else {
                            console.error('Không tìm thấy ID sản phẩm');
                            showToast('Không tìm thấy ID sản phẩm', 'error');
                        }
                    }
                }
            }
        }
    });

    // Update cart count on page load (check both API and guest cart)
    if (isUserLoggedIn()) {
        updateCartCount();
    } else {
        updateGuestCartCount();
    }
});
