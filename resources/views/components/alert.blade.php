@props([
    'type' => 'info',
    'message',
])

@php
    $classes = [
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-gray-800',
            'border' => 'border-blue-300 dark:border-blue-800',
            'text' => 'text-blue-800 dark:text-blue-400',
        ],
        'danger' => [
            'bg' => 'bg-red-50 dark:bg-gray-800',
            'border' => 'border-red-300 dark:border-red-800',
            'text' => 'text-red-800 dark:text-red-400',
        ],
        'success' => [
            'bg' => 'bg-green-50 dark:bg-gray-800',
            'border' => 'border-green-300 dark:border-green-800',
            'text' => 'text-green-800 dark:text-green-400',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-gray-800',
            'border' => 'border-yellow-300 dark:border-yellow-800',
            'text' => 'text-yellow-800 dark:text-yellow-300',
        ]
    ];

    $titles = [
        'info' => 'Info alert!',
        'danger' => 'Danger alert!',
        'success' => 'Success alert!',
        'warning' => 'Warning alert!'
    ];
@endphp

<div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-sm {{ $classes[$type]['text'] }} border {{ $classes[$type]['border'] }} rounded-lg {{ $classes[$type]['bg'] }}" role="alert">
    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
    </svg>
    <span class="sr-only">{{ $titles[$type] }}</span>
    <div class="flex-grow">
        <span class="font-medium">{{ $titles[$type] }}</span> {{ $message }}
    </div>
    <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 {{ $classes[$type]['text'] }} rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </button>
</div>
