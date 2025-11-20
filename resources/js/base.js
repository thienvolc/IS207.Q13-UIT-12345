// ===============================
// LAZY LOADING IMAGES (Common utility)
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');

    lazyImages.forEach((img) => {
        // If image is already loaded (cached)
        if (img.complete) {
            img.classList.add("loaded");
            img.style.opacity = "1";
        } else {
            // Listen for load event
            img.addEventListener("load", function () {
                img.classList.add("loaded");
                img.style.opacity = "1";
                img.style.transition = "opacity 0.3s ease";
            });
        }
    });
});
