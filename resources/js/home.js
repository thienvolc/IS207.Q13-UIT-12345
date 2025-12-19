// resources/js/home.js

// ===============================
// HERO SLIDER
// ===============================
const slides = [
    {
        img: "/img/hero4.png",
        title: "SIÊU PHẨM<br>SMARTWATCH 2025",
        subtitle: "ƯU ĐÃI ĐẶC BIỆT CHO BẠN",
        priceHtml: "<strong>599</strong><sup>999</sup>",
    },
    {
        img: "/img/hero3.png",
        title: "TUYỆT ĐỈNH<br>ÂM THANH",
        subtitle: "PIN TRÂU - BASS CỰC CĂNG",
        priceHtml: "<strong>499</strong><sup>999</sup>",
    },
    {
        img: "/img/hero1.webp",
        title: "SIÊU NHẸ<br>CHUỘT GAMING",
        subtitle: "MƯỢT MÀ - NHANH NHẠY - CHÍNH XÁC",
        priceHtml: "<strong>399</strong><sup>999</sup>",
    },
    {
        img: "/img/hero5.webp",
        title: "CARD PC<br>MẠNH MẼ",
        subtitle: "ĐỈNH CAO CÔNG NGHỆ PC",
        priceHtml: "<strong>1.999</strong><sup>999</sup>",
    },
    {
        img: "/img/hero2.png",
        title: "PHÍM THỦ<br>KHÓ BỎ QUA",
        subtitle: "GÕ LÀ THÍCH - CHƠI LÀ MÊ",
        priceHtml: "<strong>299</strong><sup>999</sup>",
    },
];

document.addEventListener("DOMContentLoaded", () => {
    const hero = document.querySelector(".hero");
    if (!hero) return;

    const img = hero.querySelector(".hero__img");
    const title = hero.querySelector(".hero__title");
    const sub = hero.querySelector(".hero__subtitle");
    const priceStrong = hero.querySelector(".hero__price strong");
    const priceSup = hero.querySelector(".hero__price sup");
    const dotsWrap = hero.querySelector(".hero__dots");

    let dots = [];
    if (dotsWrap) {
        if (dotsWrap.children.length !== slides.length) {
            dotsWrap.innerHTML = slides
                .map(
                    (_, i) =>
                        `<span class="dot${
                            i === 0 ? " is-active" : ""
                        }" data-index="${i}"></span>`,
                )
                .join("");
        } else {
            [...dotsWrap.children].forEach((el, i) => (el.dataset.index = i));
        }
        dots = [...dotsWrap.querySelectorAll(".dot")];
    }

    let current = 0,
        timer;

    function render(idx) {
        const s = slides[idx];

        // Fade out current content
        hero.classList.remove("hero--anim");

        // Wait for fade out, then update content
        setTimeout(() => {
            // Update content while invisible/faded
            if (img) img.src = s.img;
            if (title) title.innerHTML = s.title;
            if (sub) sub.textContent = s.subtitle;

            // Parse priceHtml to extract number and decimal
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = s.priceHtml;
            if (priceStrong)
                priceStrong.textContent =
                    tempDiv.querySelector("strong")?.textContent || "";
            if (priceSup)
                priceSup.textContent =
                    tempDiv.querySelector("sup")?.textContent || "";

            dots.forEach((d, i) => d.classList.toggle("is-active", i === idx));
            current = idx;

            // Trigger animation after content is updated
            requestAnimationFrame(() => {
                hero.classList.add("hero--anim");
            });
        }, 100);
    }

    function next() {
        render((current + 1) % slides.length);
    }

    dots.forEach((d) =>
        d.addEventListener("click", () => {
            clearInterval(timer);
            render(+d.dataset.index);
            timer = setInterval(next, 6500);
        }),
    );

    render(0);
    timer = setInterval(next, 6500);
});

// ===============================
// COUNTDOWN TIMER
// ===============================
document.addEventListener("DOMContentLoaded", () => {
    const countdowns = document.querySelectorAll(".js-countdown");

    countdowns.forEach((countdown) => {
        const endDate = new Date(countdown.dataset.endDate).getTime();
        const hoursEl = countdown.querySelector(".js-cd-hours");
        const minutesEl = countdown.querySelector(".js-cd-minutes");
        const secondsEl = countdown.querySelector(".js-cd-seconds");

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                hoursEl.textContent = "00";
                minutesEl.textContent = "00";
                secondsEl.textContent = "00";
                return;
            }

            const hours = Math.floor(
                (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
            );
            const minutes = Math.floor(
                (distance % (1000 * 60 * 60)) / (1000 * 60),
            );
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            hoursEl.textContent = String(hours).padStart(2, "0");
            minutesEl.textContent = String(minutes).padStart(2, "0");
            secondsEl.textContent = String(seconds).padStart(2, "0");
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});

// ===============================
// GENERIC PRODUCT CAROUSEL
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    const carousels = document.querySelectorAll(".product-carousel-section");

    carousels.forEach((carousel) => {
        const track = carousel.querySelector(".product-carousel-track");
        const nextBtn = carousel.querySelector(".js-carousel-next");
        const prevBtn = carousel.querySelector(".js-carousel-prev");

        if (!track || !nextBtn || !prevBtn) return;

        // Slide 1 item at a time
        // Item width is 20% (5 items visible)
        let currentIndex = 0;
        const totalItems = track.children.length;
        const itemsPerPage = 5;
        const maxIndex = totalItems > itemsPerPage ? totalItems - itemsPerPage : 0;

        // Ensure buttons state on init
        updateButtons();

        // Next Button Click
        nextBtn.addEventListener("click", () => {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });

        // Prev Button Click
        prevBtn.addEventListener("click", () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });

        function updateCarousel() {
            // Translate track by currentIndex * 20%
            track.style.transform = `translateX(-${currentIndex * 20}%)`;
            updateButtons();
        }

        function updateButtons() {
            // Disable/Hide Prev button if at start
            if (currentIndex <= 0) {
                prevBtn.style.opacity = "0"; // Hide completely or fade
                prevBtn.style.pointerEvents = "none";
                prevBtn.style.visibility = "hidden";
            } else {
                prevBtn.style.opacity = "1";
                prevBtn.style.pointerEvents = "auto";
                prevBtn.style.visibility = "visible";
            }

            // Disable/Hide Next button if at end
            if (currentIndex >= maxIndex) {
                nextBtn.style.opacity = "0";
                nextBtn.style.pointerEvents = "none";
                nextBtn.style.visibility = "hidden";
            } else {
                nextBtn.style.opacity = "1";
                nextBtn.style.pointerEvents = "auto";
                nextBtn.style.visibility = "visible";
            }
        }
    });
});
