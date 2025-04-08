@extends('layouts.admin')

@section('content')
    <div class="dashboard">
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <x-admin.stat-card title="Total Pengguna" :value="$totalUsers" color="blue" />
            <x-admin.stat-card title="Total Destinasi" :value="$totalDestinations" color="green" />
            <x-admin.stat-card title="Total Kategori" :value="$totalCategories" color="purple" />
            <x-admin.stat-card title="Total Ulasan" :value="$totalReviews" color="red" />
        </div>

        {{-- Notifikasi Sistem --}}
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Notifikasi Sistem</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Pengajuan Destinasi --}}
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-800">Pengajuan Destinasi</h3>
                            <p class="text-2xl font-bold text-yellow-900">
                                {{ $systemNotifications['pendingDestinations'] }}
                            </p>
                        </div>
                        <a href="" class="text-yellow-700 hover:underline">
                            Tinjau
                        </a>
                    </div>
                </div>

                {{-- Akun Tidak Aktif --}}
                <div class="bg-blue-100 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-800">Akun Tidak Aktif</h3>
                            <p class="text-2xl font-bold text-blue-900">{{ $systemNotifications['inactiveUsers'] }}</p>
                        </div>
                        <a href="" class="text-blue-700 hover:underline">
                            Kelola
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12"></div>

        {{-- Grafik dan Detail --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Pertumbuhan Pengguna --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Pertumbuhan Pengguna</h2>
                <canvas id="userGrowthChart"></canvas>
            </div>

            {{-- Distribusi Destinasi per Kategori --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Destinasi per Kategori</h2>
                <canvas id="destinationCategoryChart"></canvas>
            </div>
        </div>

        {{-- Destinasi Terpopuler --}}
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Destinasi Terpopuler</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">Nama Destinasi</th>
                        <th class="text-left p-2">Jumlah Ulasan</th>
                        <th class="text-left p-2">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($popularDestinations as $destination)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="p-2">{{ $destination->place_name }}</td>
                            <td class="p-2">{{ $destination->reviews_count }}</td>
                            <td class="p-2">{{ $destination->category->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Grafik Pertumbuhan Pengguna
            const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(userGrowthCtx, {
                type: 'line',
                data: {
                    labels: {!! $userGrowth->pluck('full_month_label') !!},
                    datasets: [{
                        label: 'Pertumbuhan Pengguna',
                        data: {!! $userGrowth->pluck('count') !!},
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Pertumbuhan Pengguna per Bulan'
                        }
                    }
                }
            });

            // Grafik Distribusi Destinasi per Kategori
            const destinationCategoryCtx = document.getElementById('destinationCategoryChart').getContext('2d');
            new Chart(destinationCategoryCtx, {
                type: 'pie',
                data: {
                    labels: {!! $destinationByCategory->pluck('name') !!},
                    datasets: [{
                        data: {!! $destinationByCategory->pluck('destinations_count') !!},
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 206, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)',
                            // Tambahkan warna lain sesuai kebutuhan
                        ]
                    }]
                }
            });
        </script>
    @endpush
@endsection
