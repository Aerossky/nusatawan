@extends('layouts.admin')

@section('content')
    <div class="">
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Detail Pengguna</h2>

            {{-- Tombol Kembali --}}
            <x-button href="{{ route('admin.users.index') }}" variant="secondary">
                Kembali
            </x-button>
        </div>
        <div class="space-y-6">
            {{-- Detail User --}}
            <div class="bg-white p-6 rounded-xl shadow">
                @if ($user->image)
                    <div class="my-2">
                        <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                            class="w-24 h-24 object-cover rounded">
                    </div>
                @endif
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="font-medium">Nama:</span> {{ $user->name }}</div>
                    <div><span class="font-medium">Email:</span> {{ $user->email }}</div>
                    <div><span class="font-medium">Status:</span> {{ ucfirst($user->status) }}</div>
                    <div><span class="font-medium">Role:</span> {{ $user->isAdmin ? 'Admin' : 'User' }}</div>
                </div>
            </div>

            {{-- Review / Komentar --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-xl font-semibold mb-4">Komentar / Review</h2>

                @if ($user->reviews->count())
                    <div class="space-y-4 text-sm">
                        @foreach ($user->reviews as $review)
                            <div class="border-b pb-2 flex justify-between items-start gap-4">
                                <div>
                                    <div class="font-medium">{{ $review->destination->place_name ?? '-' }}</div>
                                    <div class="text-gray-600">{{ $review->comment }}</div>
                                    <div class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</div>
                                </div>

                                <div class="my-auto">
                                    <form action="" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus review ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 text-base  hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum memberikan komentar.</p>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('scripts')
@endpush
