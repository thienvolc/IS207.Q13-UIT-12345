// ============================================
// Global Functions - Toast & Cart
// ============================================

/**
 * Show global toast notification
 * @param {string} message - Message to display
 * @param {string} type - Type of toast: success, error, warning, info
 * @param {number} duration - Duration in milliseconds
 */
window.showGlobalToast = function (message, type = "success", duration = 3000) {
    // Remove existing toasts
    document.querySelectorAll(".global-toast").forEach((t) => t.remove());

    const toast = document.createElement("div");
    toast.className = "global-toast";

    const config = {
        success: { color: "#198754", icon: "check-circle-fill" },
        error: { color: "#dc3545", icon: "x-circle-fill" },
        warning: { color: "#ffc107", icon: "exclamation-triangle-fill" },
        info: { color: "#0dcaf0", icon: "info-circle-fill" },
    };

    const { color, icon } = config[type] || config.success;

    toast.innerHTML = `<i class="bi bi-${icon}" style="color: ${color}"></i> ${message}`;
    toast.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        padding: 16px 24px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 14px;
        z-index: 99999;
        opacity: 0;
        transform: translateX(400px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        border-left: 5px solid ${color};
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        min-width: 300px;
        max-width: 420px;
    `;

    document.body.appendChild(toast);

    // Trigger animation
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.style.opacity = "1";
            toast.style.transform = "translateX(0)";
        });
    });

    // Hide and remove
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateX(400px)";
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

/**
 * Add product to cart from product card
 * @param {HTMLElement} button - Button element
 * @param {number} productId - Product ID
 */
window.addToCartFromCard = async function (button, productId) {
    // Check authentication
    if (!window.isAuthenticated) {
        showGlobalToast(
            "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng",
            "warning",
        );
        return;
    }

    // Save original state
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i>';

    const resetButton = () => {
        button.innerHTML = originalHtml;
        button.disabled = false;
    };

    try {
        const response = await fetch("/api/web/cart/items", {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                )?.content,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
            }),
        });

        const data = await response.json();

        if (data.success) {
            button.innerHTML = '<i class="bi bi-check-lg"></i>';
            showGlobalToast("Đã thêm vào giỏ hàng!", "success");
            setTimeout(resetButton, 1500);
        } else {
            resetButton();
            showGlobalToast(data.message || "Không thể thêm vào giỏ", "error");
        }
    } catch (error) {
        console.error("Add to cart error:", error);
        resetButton();
        showGlobalToast("Có lỗi xảy ra, vui lòng thử lại", "error");
    }
};
