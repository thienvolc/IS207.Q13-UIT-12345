// resources/js/header.js
// Sticky header
document.addEventListener("DOMContentLoaded", () => {
    const header = document.querySelector(".header");
    const STICKY_THRESHOLD = 1;

    window.addEventListener("scroll", () => {
        if (window.scrollY > STICKY_THRESHOLD) {
            header.classList.add("is-stuck");
        } else {
            header.classList.remove("is-stuck");
        }
    });
});
