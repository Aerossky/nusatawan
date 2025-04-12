
document.addEventListener("DOMContentLoaded", function() {
    new Swiper(".mySwiper", {
        slidesPerView: "auto", // Menyesuaikan lebar card secara otomatis
        spaceBetween: 10, // Jarak antar card
        centeredSlides: false, // Jangan dipaksa ke tengah
        initialSlide: 0,
        loop: false,
        watchSlidesProgress: true,
        speed: 1500,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 40
            },
        },
    });
});
