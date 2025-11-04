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
// BEST SELLERS - TAB NAVIGATION
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    let currentTab = 1;
    const totalTabs = 2;

    // Change Tab Function
    function changeTab(direction) {
        const oldTab = currentTab;
        currentTab += direction;

        // Loop around
        if (currentTab > totalTabs) currentTab = 1;
        if (currentTab < 1) currentTab = totalTabs;

        showTab(currentTab, direction);
    }

    // Go to specific tab
    function goToTab(tabNumber) {
        const direction = tabNumber > currentTab ? 1 : -1;
        currentTab = tabNumber;
        showTab(currentTab, direction);
    }

    // Show Tab with animation
    function showTab(tabNumber, direction = 1) {
        const tabs = document.querySelectorAll(".product-tab");

        // Add slide-out animation to current tab
        tabs.forEach((tab, index) => {
            if (tab.classList.contains("active")) {
                // Slide out in opposite direction
                if (direction > 0) {
                    tab.classList.add("slide-out-left");
                } else {
                    tab.classList.add("slide-out-right");
                }

                // Remove active after animation starts
                setTimeout(() => {
                    tab.classList.remove(
                        "active",
                        "slide-out-left",
                        "slide-out-right",
                    );
                }, 100);
            }
        });

        // Show selected tab with slide-in animation
        setTimeout(() => {
            const selectedTab = document.getElementById(
                `tab-page-${tabNumber}`,
            );
            if (selectedTab) {
                selectedTab.classList.add("active");
            }
        }, 100);

        // Update indicators
        const dots = document.querySelectorAll(".tab-dot");
        dots.forEach((dot, index) => {
            if (index + 1 === tabNumber) {
                dot.classList.add("active");
            } else {
                dot.classList.remove("active");
            }
        });
    }

    // Event delegation for navigation buttons
    document.addEventListener("click", function (e) {
        const target = e.target.closest("[data-tab-action]");
        if (target) {
            const action = target.dataset.tabAction;
            if (action === "next") {
                changeTab(1);
            } else if (action === "prev") {
                changeTab(-1);
            }
        }

        // Tab dots click handler
        const dotTarget = e.target.closest("[data-tab-number]");
        if (dotTarget) {
            const tabNumber = parseInt(dotTarget.dataset.tabNumber);
            goToTab(tabNumber);
        }
    });
});
