@props(['currentPage' => ''])

<nav id="navbar" data-page="{{ $currentPage }}"
    class="fixed w-full z-50 top-0 start-0 transition-all duration-300 bg-transparent">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <!-- Logo / Brand -->
        <a href="{{ route('user.home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span
                class="self-center text-xl md:text-2xl font-semibold whitespace-nowrap text-white transition-colors duration-300"
                id="brand-text">Nusatawan.</span>
        </a>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex items-center md:order-2">
            @auth
                <!-- Profile Dropdown (Mobile & Desktop) -->
                <div class="relative">
                    <button type="button" class="flex text-sm rounded-full focus:ring-4 focus:ring-blue-300 mr-2"
                        id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom">
                        <span class="sr-only">Buka menu pengguna</span>
                        <img class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border-2 border-white"
                            src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('/images/default-avatar.png') }}"
                            alt="Foto profil {{ Auth::user()->name }}">
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg absolute right-0 w-44"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900 truncate">{{ Auth::user()->name }}</span>
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
                <!-- Register Button -->
                <a href=""
                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 md:px-5 md:py-2.5 text-center transition-all duration-300">
                    Daftar
                </a>
            @endauth

            <!-- Hamburger Menu Button -->
            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 ml-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-700/50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors duration-300"
                id="hamburger-menu" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Buka menu utama</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1 transition-all duration-300"
            id="navbar-sticky">
            <ul
                class="flex flex-col p-4 mt-4 font-medium rounded-lg bg-white/95 backdrop-blur-sm shadow-lg border-gray-200 border md:bg-transparent md:shadow-none md:p-0 md:flex-row md:space-x-4 lg:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
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
                    <li>
                        <a href="{{ route($menu['route']) }}"
                            class="block py-2 px-3 rounded-md md:px-3 lg:px-4 md:py-1.5 md:rounded-full transition-all duration-300
                            {{ $currentPage == $menu['name'] ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-200 md:text-white md:hover:bg-blue-600/80' }} "
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
        const navLinks = document.querySelectorAll('ul li a'); // Ambil semua link di dalam ul
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const currentPage = navbar.getAttribute('data-page');
        const navbarMenu = document.getElementById('navbar-sticky');
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        // Handle user menu dropdown
        if (userMenuButton) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');

                // Position dropdown correctly on mobile
                if (window.innerWidth < 768) {
                    // Reset any previous positioning
                    userDropdown.style.left = '';
                    userDropdown.style.right = '';

                    // Calculate position to keep dropdown within screen
                    const buttonRect = userMenuButton.getBoundingClientRect();
                    const dropdownWidth = userDropdown.offsetWidth;

                    // Check if dropdown would overflow on right side
                    if (buttonRect.right - dropdownWidth < 0) {
                        userDropdown.style.left = '0';
                        userDropdown.style.right = 'auto';
                    } else {
                        // Position dropdown to align with right edge of button
                        userDropdown.style.right = '0';
                        userDropdown.style.left = 'auto';
                    }
                }
            });
        }

        // Close user dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (userDropdown && !userMenuButton.contains(e.target) && !userDropdown.contains(e
                    .target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Handle mobile menu toggle
        hamburgerMenu.addEventListener('click', function() {
            navbarMenu.classList.toggle('hidden');

            // Pastikan navbar memiliki background ketika menu mobile terbuka
            if (!navbarMenu.classList.contains('hidden')) {
                setNavbarSolid();
            } else if ((currentPage === "user.home" || currentPage === "user.destinations.index") &&
                window.scrollY <= 50) {
                // Kembalikan ke state normal hanya jika di halaman beranda/destinasi dan scroll belum jauh
                setNavbarTransparent();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!hamburgerMenu.contains(event.target) && !navbarMenu.contains(event.target) && !
                navbarMenu.classList.contains('hidden')) {
                navbarMenu.classList.add('hidden');

                // Kembalikan navbar ke state normal ketika menu ditutup (untuk halaman beranda/destinasi saja)
                if ((currentPage === "user.home" || currentPage === "user.destinations.index") && window
                    .scrollY <= 50) {
                    setNavbarTransparent();
                }
            }
        });

        // Handle navbar styling based on page type and scroll
        if (currentPage !== "user.home" && currentPage !== "user.destinations.index") {
            setNavbarSolid();
        } else {
            // Initial state for home/destinations pages
            setNavbarTransparent();

            // Add scroll listener
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    setNavbarSolid();
                } else {
                    setNavbarTransparent();
                }
            });
        }

        // Menambahkan event listeners untuk hover
        navLinks.forEach(link => {
            // Event saat hover masuk
            link.addEventListener('mouseenter', function() {
                // Selalu set warna putih saat hover
                this.style.color = '#ffffff';
            });

            // Event saat hover keluar
            link.addEventListener('mouseleave', function() {
                const page = this.getAttribute('data-page');

                if (navbar.classList.contains('bg-white')) {
                    // Navbar solid: kembalikan warna sesuai status (aktif/tidak)
                    if (page === currentPage) {
                        this.style.color = '#ffffff'; // Menu aktif tetap putih
                    } else {
                        this.style.color = '#111827'; // Menu tidak aktif kembali hitam
                    }
                } else {
                    // Navbar transparan: semua menu tetap putih
                    this.style.color = '#ffffff';
                }
            });
        });

        // Function to set navbar to solid style with active menu white and others black
        function setNavbarSolid() {
            // Navbar background
            navbar.classList.add('bg-white', 'shadow-md');
            navbar.classList.remove('bg-transparent');

            // Brand text
            brandText.classList.remove('text-white');
            brandText.classList.add('text-gray-900');

            // Hamburger menu
            hamburgerMenu.classList.remove('text-white');
            hamburgerMenu.classList.add('text-gray-900');

            // Navigation links - menu aktif putih, lainnya hitam
            navLinks.forEach(link => {
                const page = link.getAttribute('data-page');

                if (page === currentPage) {
                    // Menu aktif: pastikan warna putih dan background biru
                    link.classList.remove('text-gray-900', 'hover:bg-gray-100');
                    link.style.color = '#ffffff'; // Putih
                    link.classList.add('bg-blue-600', 'text-white');
                } else {
                    // Menu tidak aktif: warna hitam
                    link.classList.remove('text-white', 'md:text-white', 'bg-blue-600',
                        'hover:bg-gray-700/50');
                    link.style.color = '#111827'; // Hitam (text-gray-900)
                    link.classList.add('hover:bg-gray-100', 'hover:text-white');
                }
            });
        }

        // Function to set navbar to transparent style
        function setNavbarTransparent() {
            // Navbar background
            navbar.classList.add('bg-transparent');
            navbar.classList.remove('bg-white', 'shadow-md');

            // Brand text
            brandText.classList.remove('text-gray-900');
            brandText.classList.add('text-white');

            // Hamburger menu
            hamburgerMenu.classList.remove('text-gray-900');
            hamburgerMenu.classList.add('text-white');

            // Navigation links - semua putih kecuali yang aktif
            navLinks.forEach(link => {
                const page = link.getAttribute('data-page');

                // Reset inline style untuk semua link
                link.style.color = '#ffffff';

                if (page === currentPage) {
                    // Menu aktif: pastikan warna putih dan background biru
                    link.classList.remove('text-gray-900', 'hover:bg-gray-100');
                    link.classList.add('bg-blue-600', 'text-white');
                } else {
                    // Menu tidak aktif: tetap putih saat navbar transparan
                    link.classList.remove('text-gray-900', 'hover:bg-gray-100');
                    link.classList.add('text-white', 'md:text-white', 'hover:bg-gray-700/50',
                        'hover:text-white');
                }
            });
        }

        // Handle window resize events
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                navbarMenu.classList.remove('hidden');

                // Reset dropdown positioning on desktop
                if (userDropdown) {
                    userDropdown.style.left = '';
                    userDropdown.style.right = '0';
                }
            } else {
                navbarMenu.classList.add('hidden');
            }
        });
    });
</script>
