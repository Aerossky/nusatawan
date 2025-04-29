
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper with custom pagination options
    const swiper = new Swiper('.mySwiper', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });

    // Apply Tailwind classes to pagination bullets
    const bullets = document.querySelectorAll('.swiper-pagination');
    bullets.forEach(bullet => {
        bullet.classList.add('w-2.5', 'h-2.5', 'mx-1');
    });

    const activeBullets = document.querySelectorAll('.swiper-pagination-active');
    activeBullets.forEach(bullet => {
        bullet.classList.add('bg-blue-500');
    });
});
