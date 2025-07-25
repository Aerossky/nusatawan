<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex ms-2 md:me-24">
                    <img src="{{ asset('images/logo/nusatawan-logo.png') }}" class="h-12" alt="Logo Nusatawan" />
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button"
                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300"
                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            @if (Auth::user()->image)
                                <img class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border-2 border-white"
                                    src="{{ asset('storage/' . Auth::user()->image) }}"
                                    alt="Foto profil {{ Auth::user()->name }}">
                            @else
                                <img class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border-2 border-white"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=100"
                                    alt="Inisial {{ Auth::user()->name }}">
                            @endif
                        </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow"
                        id="dropdown-user">
                        @auth
                            <div class="px-4 py-3" role="none">
                                {{-- name --}}
                                <p class="text-sm text-gray-900" role="none">
                                    {{ auth()->user()->name }}
                                </p>
                                {{-- email --}}
                                <p class="text-sm font-medium text-gray-900 truncate" role="none">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                        @endauth
                        <ul class="py-1" role="none">
                            <li>
                                <a href="{{ route('user.home') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Halaman Pengunjung</a>
                            </li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full">
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
