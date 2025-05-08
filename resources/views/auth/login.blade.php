<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nusatawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-primary {
            background-color: #3B82F6;
        }

        .text-primary {
            color: #3B82F6;
        }

        .hover\:bg-primary-dark:hover {
            background-color: #2563EB;
        }

        .focus\:border-primary:focus {
            border-color: #3B82F6;
        }

        .focus\:ring-primary:focus {
            --tw-ring-color: #3B82F6;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="h-screen w-screen flex items-center justify-center bg-gray-50">
    <div
        class="w-full max-w-5xl h-auto md:h-[550px] flex flex-col md:flex-row bg-white shadow-xl rounded-xl overflow-hidden animate-fade-in">

        <!-- Gambar & Branding -->
        <div class="relative hidden md:block md:w-1/2 bg-gradient-to-br from-blue-500 to-blue-700">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-80"
                style="background-image: url('{{ asset('images/auth.png') }}');">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-600/60 to-blue-900/60 flex flex-col justify-between p-8">
                <div>
                    <img src="{{ asset('images/logo/nusatawan-logo.png') }}" alt="Nusatawan Logo" class="h-20">
                </div>
                <div class="text-white mb-10">
                    <h1 class="text-3xl font-bold mb-4">Jelajahi Keindahan Nusantara</h1>
                    <p class="text-lg opacity-90">Akses penawaran eksklusif dan kelola perjalanan Anda dengan mudah.</p>
                </div>
            </div>
        </div>

        <!-- Form Login -->
        <div class="md:w-1/2 p-6 md:p-12 flex items-center justify-center bg-white">
            <div class="w-full max-w-md">
                <!-- Logo untuk tampilan mobile -->
                <div class="flex justify-center mb-6 md:hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Nusatawan Logo" class="h-12">
                </div>

                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Selamat Datang Kembali</h2>
                <p class="text-gray-600 mb-8">Masukkan kredensial Anda untuk melanjutkan</p>

                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Display success message -->
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="nama@email.com" required>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                            <a href="" class="text-sm text-primary hover:underline">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password"
                                class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                placeholder="••••••••" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword"
                                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg shadow-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition duration-300">
                        Masuk
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('auth.register') }}" class="text-primary font-semibold hover:underline">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Change the eye icon
            this.innerHTML = type === 'password' ?
                '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>' :
                '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>';
        });
    </script>
</body>

</html>
