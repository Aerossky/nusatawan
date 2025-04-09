@props(['title', 'value', 'color' => 'blue'])

<div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-lg font-semibold">{{ $title }}</h2>
    <p class="text-3xl font-bold text-{{ $color }}-500">{{ $value }}</p>
</div>
