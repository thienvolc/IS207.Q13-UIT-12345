// admin.js - sidebar toggle, flash, small helpers
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".admin-sidebar");
    const toggleBtn = document.getElementById("btn-sidebar-toggle");
    const collapseBtn = document.getElementById("btn-sidebar-collapse");

    function setAria(open) {
        const btn = document.getElementById("btn-sidebar-toggle");
        const collapse = document.getElementById("btn-sidebar-collapse");
        if (btn) btn.setAttribute("aria-expanded", open ? "true" : "false");
        if (collapse)
            collapse.setAttribute("aria-expanded", open ? "true" : "false");
    }

    function openSidebar() {
        sidebar && sidebar.classList.add("open");
        setAria(true);
    }
    function closeSidebar() {
        sidebar && sidebar.classList.remove("open");
        setAria(false);
    }
    function toggleSidebar() {
        sidebar && sidebar.classList.toggle("open");
        setAria(sidebar.classList.contains("open"));
    }

    if (toggleBtn) toggleBtn.addEventListener("click", toggleSidebar);
    if (collapseBtn) collapseBtn.addEventListener("click", closeSidebar);

    // close sidebar when clicking outside on small screens
    document.addEventListener("click", function (e) {
        if (!sidebar) return;
        if (sidebar.classList.contains("open") && window.innerWidth < 992) {
            const isClickInside =
                sidebar.contains(e.target) ||
                (toggleBtn && toggleBtn.contains(e.target));
            if (!isClickInside) closeSidebar();
        }
    });

    // Flash message (session-driven)
    const flash = document.getElementById("flash-message");
    if (flash) {
        flash.classList.add("show");
        setTimeout(() => flash.classList.remove("show"), 4200);
    }

    // helper for delete confirmation (called from forms)
    window.confirmDelete = function (
        message = "Bạn có chắc muốn xóa mục này?"
    ) {
        return confirm(message);
    };
});
