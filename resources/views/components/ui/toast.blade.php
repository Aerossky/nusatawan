{{-- Toast Component
    props:
    - type: success|error|warning|info (default: success)
    - message: string (default: '')
    - autoClose: boolean (default: true)
    - duration: integer in ms (default: 5000)
--}}
@props([
    'type' => 'success',
    'message' => '',
    'autoClose' => true,
    'duration' => 5000,
])

@php
    $typeClasses = [
        'success' => [
            'border' => 'border-green-500',
            'text' => 'text-green-500',
            'bg' => 'bg-green-100',
            'icon' =>
                '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>',
        ],
        'error' => [
            'border' => 'border-red-500',
            'text' => 'text-red-500',
            'bg' => 'bg-red-100',
            'icon' =>
                '<path fill-rule="evenodd" d="M10 9a1 1 0 100 2 1 1 0 000-2zm.707-4.293a1 1 0 00-1.414 0L3.586 10.414a1 1 0 000 1.414l5.707 5.707a1 1 0 001.414 0l5.707-5.707a1 1 0 000-1.414L10.707 4.707z" clip-rule="evenodd" />',
        ],
        'warning' => [
            'border' => 'border-yellow-500',
            'text' => 'text-yellow-500',
            'bg' => 'bg-yellow-100',
            'icon' =>
                '<path fill-rule="evenodd" d="M8.257 3.099c.763-1.36 2.72-1.36 3.483 0l6.516 11.621c.75 1.337-.213 2.98-1.742 2.98H3.483c-1.53 0-2.492-1.643-1.742-2.98L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v3a1 1 0 01-1 1z" clip-rule="evenodd" />',
        ],
        'info' => [
            'border' => 'border-blue-500',
            'text' => 'text-blue-500',
            'bg' => 'bg-blue-100',
            'icon' =>
                '<path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zM9 9a1 1 0 012 0v4a1 1 0 11-2 0V9zm1-4a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd" />',
        ],
    ];

    $currentType = isset($typeClasses[$type]) ? $type : 'info';
    $classes = $typeClasses[$currentType];
    $toastId = 'toast-' . uniqid();
@endphp

<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-3 max-w-md">
    <div id="{{ $toastId }}"
        class="flex items-center w-full max-w-md p-4 text-gray-500 bg-white rounded-lg shadow-lg border-l-4 transform transition-all duration-300 ease-in-out opacity-100 {{ $classes['border'] }}"
        role="alert">
        <div
            class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg {{ $classes['text'] }} {{ $classes['bg'] }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                {!! $classes['icon'] !!}
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal">
            <div class="font-medium capitalize">{{ $currentType }}!</div>
            <div class="text-sm text-gray-600">{{ $message }}</div>
        </div>
        <button type="button"
            class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8"
            onclick="closeToast('{{ $toastId }}')" aria-label="Close">
            <span class="sr-only">Tutup</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup auto-close for the toast if enabled
            @if ($autoClose)
                setTimeout(() => {
                    closeToast('{{ $toastId }}');
                }, {{ $duration }});
            @endif
        });

        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        }
    </script>
@endpush
