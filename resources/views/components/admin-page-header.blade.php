@props(['title' => '', 'actions' => null])

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Breadcrumbs -->
        <div class="mb-2">
            <x-breadcrumbs />
        </div>
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            @if($title)
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
            @endif
            
            @if($actions)
                <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</div>
