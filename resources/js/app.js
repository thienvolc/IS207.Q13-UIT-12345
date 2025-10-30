// resources/js/app.js
import "bootstrap/dist/js/bootstrap.bundle.min.js";

// Data slides
const slides = [
    {
        img: "/img/hero4.png",
        title: "SIÊU PHẨM<br>SMARTWATCH 2025",
        subtitle: "ƯU ĐÃI ĐẶC BIỆT CHO BẠN",
        priceHtml: "<strong>1.290</strong><sup>.000đ</sup>",
    },
    {
        img: "/img/hero3.png",
        title: "TUYỆT ĐỈNH<br>ÂM THANH",
        subtitle: "PIN TRÂU - BASS CỰC CĂNG",
        priceHtml: "<strong>1.690</strong><sup>.000đ</sup>",
    },
    {
        img: "/img/hero1.webp",
        title: "SIÊU NHẸ<br>CHUỘT GAMING",
        subtitle: "MƯỢT MÀ - NHANH NHẠY - CHÍNH XÁC",
        priceHtml: "<strong>990</strong><sup>.000đ</sup>",
    },
    {
        img: "/img/hero5.webp",
        title: "CARD PC<br>MẠNH MẼ",
        subtitle: "ĐỈNH CAO CÔNG NGHỆ PC",
        priceHtml: "<strong>2.790</strong><sup>.000đ</sup>",
    },
    {
        img: "/img/hero2.png",
        title: "PHÍM THỦ<br>KHÓ BỎ QUA",
        subtitle: "GÕ LÀ THÍCH - CHƠI LÀ MÊ",
        priceHtml: "<strong>1.490</strong><sup>.000đ</sup>",
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
                        }" data-index="${i}"></span>`
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
        })
    );

    render(0);
    timer = setInterval(next, 6500);
});
