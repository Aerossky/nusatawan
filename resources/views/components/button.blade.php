@props([
    'href' => '#',
    'variant' => 'primary', // default
    'textColor' => 'text-white', // default text color
])

@php
    $baseClass = 'inline-block rounded border px-5 py-3 font-medium shadow-sm transition-colors';

    $variants = [
        'primary' => 'border-indigo-400 bg-primary hover:bg-indigo-700',
        'secondary' => 'border-gray-400 bg-gray-500 hover:bg-gray-600',
        'danger' => 'border-red-400 bg-red-500 hover:bg-red-600',
        'success' => 'border-green-400 bg-green-500 hover:bg-green-600',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClass $variantClass $textColor"]) }}>
    {{ $slot }}
</a>
