/**
 * Cart Page JavaScript
 * Handles cart interactions: load, add, remove, update quantity, checkout
 */

class CartManager {
    constructor(config) {
        this.config = config;
        this.cart = null;
        this.selectedItems = new Set();

        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCart();
    }

    bindEvents() {
        // Select all checkbox
        document
            .getElementById("selectAll")
            ?.addEventListener("change", (e) => {
                this.handleSelectAll(e.target.checked);
            });

        // Delete selected button
        document
            .getElementById("btn-delete-selected")
            ?.addEventListener("click", () => {
                this.handleDeleteSelected();
            });

        // Clear cart button
        document
            .getElementById("btn-clear-cart")
            ?.addEventListener("click", () => {
                this.handleClearCart();
            });

        // Checkout button
        document
            .getElementById("btn-checkout")
            ?.addEventListener("click", () => {
                this.handleCheckout();
            });

        // Voucher apply button
        document
            .getElementById("btn-apply-voucher")
            ?.addEventListener("click", () => {
                this.handleApplyVoucher();
            });
    }

    async loadCart() {
        this.showLoading(true);

        try {
            console.log("Loading cart from:", this.config.urls.getCart);

            const response = await fetch(this.config.urls.getCart, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.config.csrfToken,
                },
            });

            console.log("Cart response status:", response.status);

            const data = await response.json();
            console.log("Cart data:", data);

            if (data.success) {
                this.cart = data.data;
                this.renderCart();
            } else {
                console.error("Cart load failed:", data.message);
                this.showError(
                    "Không thể tải giỏ hàng: " +
                        (data.message || "Unknown error"),
                );
            }
        } catch (error) {
            console.error("Load cart error:", error);
            this.showError("Đã có lỗi xảy ra khi tải giỏ hàng.");
        } finally {
            this.showLoading(false);
        }
    }

    renderCart() {
        console.log("=== renderCart called ===");
        console.log("this.cart:", this.cart);

        const cartContent = document.getElementById("cart-content");
        const cartEmpty = document.getElementById("cart-empty");
        const cartItemsList = document.getElementById("cart-items-list");

        console.log("cartContent element:", cartContent);
        console.log("cartItemsList element:", cartItemsList);

        if (!this.cart || !this.cart.items || this.cart.items.length === 0) {
            console.log("Cart is empty or no items");
            cartContent.style.display = "none";
            cartEmpty.style.display = "block";
            return;
        }

        console.log("Cart has", this.cart.items.length, "items");
        cartContent.style.display = "";
        cartEmpty.style.display = "none";

        // Update cart count
        document.getElementById("cart-count").textContent =
            this.cart.items.length;
        document.getElementById("select-all-count").textContent =
            this.cart.items.length;

        // Clear existing items
        cartItemsList.innerHTML = "";

        // Render each item
        const template = document.getElementById("cart-item-template");
        console.log("Template element:", template);

        this.cart.items.forEach((item, index) => {
            console.log(`Rendering item ${index}:`, item);
            const clone = template.content.cloneNode(true);
            console.log("Cloned template:", clone);

            const cartItem = clone.querySelector(".cart-item");
            cartItem.dataset.itemId = item.item_id;
            cartItem.dataset.productId = item.product_id;

            // Checkbox - select all by default
            const checkbox = clone.querySelector(".item-checkbox");
            checkbox.checked =
                this.selectedItems.has(item.item_id) ||
                this.selectedItems.size === 0;
            if (this.selectedItems.size === 0) {
                this.selectedItems.add(item.item_id);
            }
            checkbox.addEventListener("change", (e) => {
                this.handleItemSelect(item.item_id, e.target.checked);
            });

            // Image
            const img = clone.querySelector(".cart-item-image img");
            img.src = item.product_image || "/img/placeholder.png";
            img.alt = item.product_name || "Sản phẩm";

            // Product link
            const productUrl = item.product_slug
                ? this.config.urls.productDetail.replace(
                      ":slug",
                      item.product_slug,
                  )
                : "#";
            clone.querySelector(".cart-item-image a").href = productUrl;

            // Name
            const nameLink = clone.querySelector(".cart-item-name a");
            nameLink.textContent =
                item.product_name || `Sản phẩm #${item.product_id}`;
            nameLink.href = productUrl;

            // Variant info (can be extended later)
            const variant = clone.querySelector(".cart-item-variant");
            variant.textContent = "";

            // Price
            const currentPrice = clone.querySelector(".price-current");
            const originalPrice = clone.querySelector(".price-original");
            const discountBadge = clone.querySelector(".price-discount");

            currentPrice.textContent = this.formatCurrency(item.price);

            if (item.discount > 0) {
                const originalValue = item.price / (1 - item.discount / 100);
                originalPrice.textContent = this.formatCurrency(originalValue);
                discountBadge.textContent = `-${item.discount}%`;
            } else {
                originalPrice.style.display = "none";
                discountBadge.style.display = "none";
            }

            // Quantity
            const qtyInput = clone.querySelector(".qty-input");
            qtyInput.value = item.quantity;
            qtyInput.addEventListener("change", (e) => {
                this.handleQuantityChange(
                    item.item_id,
                    item.product_id,
                    parseInt(e.target.value),
                );
            });

            // Quantity buttons
            clone.querySelector(".qty-minus").addEventListener("click", () => {
                const newQty = Math.max(1, item.quantity - 1);
                if (newQty !== item.quantity) {
                    this.handleQuantityChange(
                        item.item_id,
                        item.product_id,
                        newQty,
                    );
                }
            });

            clone.querySelector(".qty-plus").addEventListener("click", () => {
                const newQty = Math.min(99, item.quantity + 1);
                if (newQty !== item.quantity) {
                    this.handleQuantityChange(
                        item.item_id,
                        item.product_id,
                        newQty,
                    );
                }
            });

            // Item total
            const itemTotal = clone.querySelector(".item-total-price");
            itemTotal.textContent = this.formatCurrency(
                item.subtotal || item.price * item.quantity,
            );

            // Delete button
            clone.querySelector(".btn-delete").addEventListener("click", () => {
                this.handleRemoveItem(item.item_id);
            });

            cartItemsList.appendChild(clone);
            console.log(
                `Item ${index} appended to list. Current items count:`,
                cartItemsList.children.length,
            );
        });

        console.log("Final cartItemsList innerHTML:", cartItemsList.innerHTML);

        // Update summary
        this.updateSummary();
        this.updateSelectAllCheckbox();
    }

    updateSummary() {
        const selectedItems = this.cart.items.filter((item) =>
            this.selectedItems.has(item.item_id),
        );

        const subtotal = selectedItems.reduce((sum, item) => {
            return sum + item.price * item.quantity;
        }, 0);

        document.getElementById("summary-items-count").textContent =
            selectedItems.length;
        document.getElementById("subtotal").textContent =
            this.formatCurrency(subtotal);
        document.getElementById("total").textContent =
            this.formatCurrency(subtotal);

        // Enable/disable checkout button
        const checkoutBtn = document.getElementById("btn-checkout");
        checkoutBtn.disabled = selectedItems.length === 0;
    }

    updateSelectAllCheckbox() {
        const selectAll = document.getElementById("selectAll");
        const allSelected = this.cart.items.every((item) =>
            this.selectedItems.has(item.item_id),
        );
        const someSelected = this.cart.items.some((item) =>
            this.selectedItems.has(item.item_id),
        );

        selectAll.checked = allSelected;
        selectAll.indeterminate = someSelected && !allSelected;

        // Update delete selected button
        const deleteSelectedBtn = document.getElementById(
            "btn-delete-selected",
        );
        deleteSelectedBtn.disabled = this.selectedItems.size === 0;
    }

    handleSelectAll(checked) {
        this.selectedItems.clear();

        if (checked) {
            this.cart.items.forEach((item) => {
                this.selectedItems.add(item.item_id);
            });
        }

        // Update all checkboxes
        document.querySelectorAll(".item-checkbox").forEach((cb) => {
            cb.checked = checked;
        });

        this.updateSummary();
        this.updateSelectAllCheckbox();
    }

    handleItemSelect(itemId, checked) {
        if (checked) {
            this.selectedItems.add(itemId);
        } else {
            this.selectedItems.delete(itemId);
        }

        this.updateSummary();
        this.updateSelectAllCheckbox();
    }

    async handleQuantityChange(itemId, productId, newQuantity) {
        if (newQuantity < 1 || newQuantity > 99) return;

        // First remove the item
        await this.removeItemFromServer(itemId);

        // Then add with new quantity
        await this.addItemToServer(productId, newQuantity);

        // Reload cart
        await this.loadCart();
    }

    async handleRemoveItem(itemId) {
        if (!confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?"))
            return;

        try {
            const url = this.config.urls.removeItem.replace(":id", itemId);
            const response = await fetch(url, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.config.csrfToken,
                },
            });

            const data = await response.json();

            if (data.success) {
                this.selectedItems.delete(itemId);
                this.showToast("Đã xóa sản phẩm khỏi giỏ hàng.");
                await this.loadCart();
            } else {
                this.showToast(
                    data.message || "Không thể xóa sản phẩm.",
                    "error",
                );
            }
        } catch (error) {
            console.error("Remove item error:", error);
            this.showToast("Đã có lỗi xảy ra.", "error");
        }
    }

    async handleDeleteSelected() {
        if (this.selectedItems.size === 0) return;

        if (
            !confirm(
                `Bạn có chắc muốn xóa ${this.selectedItems.size} sản phẩm đã chọn?`,
            )
        )
            return;

        const itemIds = Array.from(this.selectedItems);

        for (const itemId of itemIds) {
            await this.removeItemFromServer(itemId);
        }

        this.selectedItems.clear();
        this.showToast("Đã xóa các sản phẩm đã chọn.");
        await this.loadCart();
    }

    async handleClearCart() {
        if (!confirm("Bạn có chắc muốn xóa toàn bộ giỏ hàng?")) return;

        try {
            const response = await fetch(this.config.urls.clearCart, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.config.csrfToken,
                },
            });

            const data = await response.json();

            if (data.success) {
                this.selectedItems.clear();
                this.showToast("Đã xóa toàn bộ giỏ hàng.");
                await this.loadCart();
            } else {
                this.showToast(
                    data.message || "Không thể xóa giỏ hàng.",
                    "error",
                );
            }
        } catch (error) {
            console.error("Clear cart error:", error);
            this.showToast("Đã có lỗi xảy ra.", "error");
        }
    }

    handleCheckout() {
        if (this.selectedItems.size === 0) {
            this.showToast("Vui lòng chọn sản phẩm để thanh toán.", "warning");
            return;
        }

        // Store selected items in session storage
        sessionStorage.setItem(
            "checkoutItems",
            JSON.stringify(Array.from(this.selectedItems)),
        );

        // Redirect to checkout page
        window.location.href = this.config.urls.checkout;
    }

    handleApplyVoucher() {
        const voucherCode = document
            .getElementById("voucher-code")
            .value.trim();

        if (!voucherCode) {
            this.showToast("Vui lòng nhập mã giảm giá.", "warning");
            return;
        }

        // TODO: Implement voucher API
        this.showToast("Tính năng mã giảm giá đang được phát triển.", "info");
    }

    async removeItemFromServer(itemId) {
        const url = this.config.urls.removeItem.replace(":id", itemId);
        await fetch(url, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": this.config.csrfToken,
            },
        });
    }

    async addItemToServer(productId, quantity) {
        await fetch(this.config.urls.addItem, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": this.config.csrfToken,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
            }),
        });
    }

    showLoading(show) {
        const loading = document.getElementById("cart-loading");
        const content = document.getElementById("cart-content");
        const empty = document.getElementById("cart-empty");

        if (show) {
            loading.style.display = "block";
            content.style.display = "none";
            empty.style.display = "none";
        } else {
            loading.style.display = "none";
        }
    }

    showError(message) {
        document.getElementById("cart-loading").innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3">${message}</p>
                <button class="btn btn-primary" onclick="location.reload()">Thử lại</button>
            </div>
        `;
    }

    showToast(message, type = "success") {
        // Simple toast notification
        const toast = document.createElement("div");
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <i class="bi bi-${type === "success" ? "check-circle" : type === "error" ? "x-circle" : "info-circle"}"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add("show"), 10);

        // Remove after 3s
        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        })
            .format(amount)
            .replace("₫", "₫");
    }
}

// Global function to add item to cart (used from product pages)
window.addToCart = async function (productId, quantity = 1) {
    // Check if user is authenticated
    if (!window.isAuthenticated) {
        showToast(
            "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.",
            "warning",
        );
        return { success: false, message: "Not authenticated" };
    }

    try {
        const response = await fetch(
            window.cartConfig?.urls?.addItem || "/api/web/cart/items",
            {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        window.cartConfig?.csrfToken ||
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content,
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                }),
            },
        );

        const data = await response.json();

        if (data.success) {
            // Show success toast
            showToast("Đã thêm sản phẩm vào giỏ hàng!");
            // Update cart count in header if exists
            updateHeaderCartCount();
        } else {
            showToast(data.message || "Không thể thêm sản phẩm.", "error");
        }

        return data;
    } catch (error) {
        console.error("Add to cart error:", error);
        showToast("Đã có lỗi xảy ra.", "error");
        return { success: false };
    }
};

function showToast(message, type = "success") {
    const toast = document.createElement("div");
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <i class="bi bi-${type === "success" ? "check-circle" : type === "error" ? "x-circle" : "info-circle"}"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add("show"), 10);
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

async function updateHeaderCartCount() {
    try {
        const response = await fetch(
            window.cartConfig?.urls?.getCart || "/api/web/cart",
        );
        const data = await response.json();

        if (data.success && data.data) {
            const countEl = document.querySelector(".header-cart-count");
            if (countEl) {
                countEl.textContent = data.data.total_quantity || 0;
                countEl.style.display =
                    data.data.total_quantity > 0 ? "flex" : "none";
            }
        }
    } catch (error) {
        console.error("Update cart count error:", error);
    }
}

// Initialize cart manager when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    if (window.cartConfig) {
        window.cartManager = new CartManager(window.cartConfig);
    }
});
