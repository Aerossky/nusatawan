{{-- resources/views/components/ui/alert.blade.php --}}
@props(['type' => 'success', 'message' => ''])

@php
    $alertColors = [
        'success' => ['bg-green-100', 'border-green-500', 'text-green-700'],
        'error' => ['bg-red-100', 'border-red-500', 'text-red-700'],
        'info' => ['bg-blue-100', 'border-blue-500', 'text-blue-700'],
        'warning' => ['bg-yellow-100', 'border-yellow-500', 'text-yellow-700'],
    ];

    $colorClasses = $alertColors[$type] ?? $alertColors['success']; // default to success if type not found
@endphp

<div class="mb-4">
    <div class="{{ $colorClasses[0] }} border-l-4 {{ $colorClasses[1] }} {{ $colorClasses[2] }} p-4 mb-2">
        <p>{{ $message }}</p>
    </div>
</div>
