<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center p-2 rounded-lg transition group
                    {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-blue-600' : 'text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-900' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Dasbor</span>
                </a>
            </li>

            <!-- User (misal untuk super admin saja) -->
            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center p-2 rounded-lg transition group
                    {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-blue-600' : 'text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-900' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path
                            d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                    </svg>
                    <span class="ms-3">Pengguna</span>
                </a>
            </li>

            <!-- Destinasi -->
            <li>
                <a href="{{ route('admin.destinations.index') }}"
                    class="flex items-center p-2 rounded-lg transition group
                    {{ request()->routeIs('admin.destinations.*') ? 'bg-gray-100 text-blue-600' : 'text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.destinations.*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-900' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Z" />
                        <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z" />
                    </svg>
                    <span class="ms-3">Destinasi</span>
                </a>
            </li>

            <!-- Pengajuan Destinasi -->
            <li>
                <a href="{{ route('admin.destination-submission.index') }}"
                    class="flex items-center p-2 rounded-lg transition group
                    {{ request()->routeIs('admin.destination-submission.*') ? 'bg-gray-100 text-blue-600' : 'text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.destination-submission.*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-900' }}"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M7.13173 20.7371C4.07023 19.0275 2 15.7555 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 15.7555 19.9298 19.0275 16.8683 20.7371L15.9724 18.9457C18.3788 17.5664 20 14.9725 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 14.9725 5.62119 17.5664 8.02763 18.9457L7.13173 20.7371ZM8.92427 17.1528C7.17271 16.105 6 14.1894 6 12C6 8.68629 8.68629 6 12 6C15.3137 6 18 8.68629 18 12C18 14.1894 16.8273 16.105 15.0757 17.1528L14.1772 15.3561C15.2744 14.6429 16 13.4062 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 13.4062 8.72559 14.6429 9.82279 15.3561L8.92427 17.1528ZM12 16L15 22H9L12 16Z">
                        </path>
                    </svg>
                    <span class="ms-3">Pengajuan Destinasi</span>
                </a>
            </li>

            {{-- Kategori --}}
            <li>
                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center p-2 rounded-lg transition group
                    {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 text-blue-600' : 'text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-900' }}"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M21 3C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H21ZM11 13H4V19H11V13ZM20 13H13V19H20V13ZM11 5H4V11H11V5ZM20 5H13V11H20V5Z">
                        </path>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Kategori</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
