@props(['currentPage' => ''])

<nav id="navbar" data-page="{{ $currentPage }}"
    class="fixed w-full z-50 top-0 start-0 transition-all duration-300 bg-transparent">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ route('user.home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white transition-colors duration-300"
                id="brand-text">Nusatawan.</span>
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            @auth
                <div class="flex items-center">
                    <button type="button" class="flex text-sm rounded-full focus:ring-4 focus:ring-blue-300"
                        id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom">
                        <span class="sr-only">Buka menu pengguna</span>
                        <img class="w-10 h-10 rounded-full object-cover border-2 border-white"
                            src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('/images/default-avatar.png') }}"
                            alt="Foto profil {{ Auth::user()->name }}">
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900">{{ Auth::user()->name }}</span>
                            <span class="block text-sm text-gray-500 truncate">{{ Auth::user()->email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="{{ route('user.profile.show') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                            </li>
                            <li>
                                <a href="{{ route('user.profile.show') }}#destinasi"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">DestinasiKu</a>
                            </li>
                            <li>
                                <a href="{{ route('user.destination-favorite.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Favorit</a>
                            </li>
                            <li>
                                <form method="POST" action="">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <div class="flex items-center space-x-4">
                    <a href="{{ route('register') }}"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center transition-all duration-300">
                        Daftar
                    </a>
                </div>
            @endauth
            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-300"
                id="hamburger-menu" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Buka menu utama</span>
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
                        ['route' => 'user.home', 'name' => 'user.home', 'label' => 'Beranda'],
                        [
                            'route' => 'user.destinations.index',
                            'name' => 'user.destinations.index',
                            'label' => 'Destinasi',
                        ],
                        [
                            'route' => 'user.itinerary.index',
                            'name' => 'user.itinerary.index',
                            'label' => 'Rencana Perjalanan',
                        ],
                        ['route' => 'user.about', 'name' => 'user.about', 'label' => 'Tentang Kami'],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    <li class="my-auto">
                        <a href="{{ route($menu['route']) }}"
                            class="block py-2 px-3 rounded-md transition md:px-6 md:py-2 md:rounded-full
                            {{ $currentPage == $menu['name'] ? 'bg-blue-600 text-white' : 'text-white hover:text-blue-400 transition-colors duration-300' }} "
                            id="nav-link-{{ str_replace('.', '-', $menu['name']) }}" data-page="{{ $menu['name'] }}">
                            {{ $menu['label'] }}
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

        // Check if current page is home or destinations
        if (currentPage !== "user.home" && currentPage !== "user.destinations.index") {
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
