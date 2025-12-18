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
            this.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin me-2"></i>Đang thêm...';

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
                        product_id: parseInt(productId),
                        quantity: quantity,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    window.showGlobalToast(
                        "Đã thêm sản phẩm vào giỏ hàng!",
                        "success",
                    );
                    updateHeaderCartCount();
                } else {
                    window.showGlobalToast(
                        data.message || "Không thể thêm sản phẩm vào giỏ hàng.",
                        "error",
                    );
                }
            } catch (error) {
                console.error("Add to cart error:", error);
                window.showGlobalToast(
                    "Đã có lỗi xảy ra. Vui lòng thử lại.",
                    "error",
                );
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
            this.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin me-2"></i>Đang xử lý...';

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
                        product_id: parseInt(productId),
                        quantity: quantity,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect to checkout
                    window.location.href = "/checkout";
                } else {
                    window.showGlobalToast(
                        data.message || "Không thể thêm sản phẩm vào giỏ hàng.",
                        "error",
                    );
                    this.disabled = false;
                    this.innerHTML = originalText;
                }
            } catch (error) {
                console.error("Buy now error:", error);
                window.showGlobalToast(
                    "Đã có lỗi xảy ra. Vui lòng thử lại.",
                    "error",
                );
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    });
});

// Update cart count in header
async function updateHeaderCartCount() {
    try {
        const response = await fetch("/api/web/cart");
        const data = await response.json();

        if (data.success && data.data) {
            const count =
                data.data.total_quantity || data.data.items?.length || 0;

            // Update cart count badge in header
            let countEl = document.querySelector(".header-cart-count");
            if (!countEl) {
                // Try to find cart icon and add count badge
                const cartIcon = document.querySelector(
                    'a[href*="cart"] .bi-bag, a[href*="cart"] .bi-cart',
                );
                if (cartIcon) {
                    countEl = document.createElement("span");
                    countEl.className = "header-cart-count";
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
                    cartIcon.parentElement.style.position = "relative";
                    cartIcon.parentElement.appendChild(countEl);
                }
            }

            if (countEl) {
                countEl.textContent = count;
                countEl.style.display = count > 0 ? "flex" : "none";
            }
        }
    } catch (error) {
        console.error("Update cart count error:", error);
    }
}
