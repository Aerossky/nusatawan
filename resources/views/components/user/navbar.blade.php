@props(['currentPage' => ''])

<nav id="navbar" data-page="{{ $currentPage }}"
    class="fixed w-full z-20 top-0 start-0 transition-all duration-300 bg-transparent">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white transition-colors duration-300"
                id="brand-text">Nusatawan.</span>
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Daftar
            </button>
            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-300"
                id="hamburger-menu" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul
                class="flex flex-col p-4 md:p-0 mt-4 font-medium border md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 my-auto">
                @php
                    $menus = [
                        'home' => 'Beranda',
                        'destinations' => 'Destinasi',
                        'plans' => 'Rencana Perjalanan',
                        'about' => 'Tentang Kami',
                    ];
                @endphp

                @foreach ($menus as $page => $label)
                    <li class="my-auto">
                        <a href="#"
                            class="block py-2 px-3 rounded-md transition md:px-6 md:py-2 md:rounded-full
                                {{ $currentPage == $page ? 'bg-blue-600 text-white' : 'text-white hover:text-blue-400 transition-colors duration-300' }} "
                            id="nav-link-{{ $page }}" data-page="{{ $page }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.getElementById('navbar');
        const brandText = document.getElementById('brand-text');
        const navLinks = document.querySelectorAll('[id^="nav-link-"]');
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const currentPage = navbar.getAttribute('data-page');

        // Jika di halaman "about", langsung atur navbar tetap putih
        if (currentPage === "about") {
            navbar.classList.add('bg-white', 'shadow-md');
            navbar.classList.remove('bg-transparent');
            brandText.classList.remove('text-white');
            brandText.classList.add('text-gray-900');
            hamburgerMenu.classList.remove('text-white');
            hamburgerMenu.classList.add('text-gray-900');

            navLinks.forEach(link => {
                const page = link.getAttribute('data-page');
                if (page === "about") {
                    link.classList.add('bg-blue-600', 'text-white');
                } else {
                    link.classList.remove('text-white', 'bg-blue-600');
                    link.classList.add('text-gray-900');
                }
            });
        } else {
            // Scroll event untuk halaman lain
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-white', 'shadow-md');
                    navbar.classList.remove('bg-transparent');
                    brandText.classList.remove('text-white');
                    brandText.classList.add('text-gray-900');
                    hamburgerMenu.classList.remove('text-white');
                    hamburgerMenu.classList.add('text-gray-900');

                    navLinks.forEach(link => {
                        const page = link.getAttribute('data-page');
                        if (page === currentPage) {
                            link.classList.add('bg-blue-600', 'text-white');
                        } else {
                            link.classList.remove('text-white', 'bg-blue-600');
                            link.classList.add('text-gray-900');
                        }
                    });
                } else {
                    navbar.classList.add('bg-transparent');
                    navbar.classList.remove('bg-white', 'shadow-md');
                    brandText.classList.remove('text-gray-900');
                    brandText.classList.add('text-white');
                    hamburgerMenu.classList.remove('text-gray-900');
                    hamburgerMenu.classList.add('text-white');

                    navLinks.forEach(link => {
                        const page = link.getAttribute('data-page');
                        if (page === currentPage) {
                            link.classList.add('bg-blue-600', 'text-white');
                        } else {
                            link.classList.remove('text-gray-900', 'bg-blue-600');
                            link.classList.add('text-white');
                        }
                    });
                }
            });
        }
    });
</script>
