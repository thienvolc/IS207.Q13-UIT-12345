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
        btn.addEventListener("click", function () {
            const productId = this.dataset.productId;
            const quantity = parseInt(qtyInput?.value || 1);
            // TODO: Call API
            alert(
                `Đã thêm ${quantity} sản phẩm vào giỏ hàng!\n\nTODO: Implement API call`,
            );
        });
    });

    // Buy Now
    document.querySelectorAll(".buy-now").forEach((btn) => {
        btn.addEventListener("click", function () {
            const productId = this.dataset.productId;
            const quantity = parseInt(qtyInput?.value || 1);
            // TODO: Call API + redirect
            alert(
                `Mua ngay ${quantity} sản phẩm!\n\nTODO: Implement checkout flow`,
            );
        });
    });
});
