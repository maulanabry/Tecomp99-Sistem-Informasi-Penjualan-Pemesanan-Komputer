<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @php
            $flatBreadcrumbs = [];
            foreach($breadcrumbs as $breadcrumb) {
                if(is_array($breadcrumb) && isset($breadcrumb[0]) && is_array($breadcrumb[0])) {
                    // Handle nested arrays
                    foreach($breadcrumb as $subBreadcrumb) {
                        $flatBreadcrumbs[] = $subBreadcrumb;
                    }
                } else {
                    $flatBreadcrumbs[] = $breadcrumb;
                }
            }
        @endphp
        
        @foreach($flatBreadcrumbs as $index => $breadcrumb)
            <li class="inline-flex items-center">
                @if($index > 0)
                    <svg class="w-3 h-3 text-neutral-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                @endif
                
                @if($breadcrumb['active'])
                    <span class="text-sm font-medium text-neutral-500 dark:text-neutral-400">
                        {{ $breadcrumb['title'] }}
                    </span>
                @else
                    @if($breadcrumb['url'])
                        <a href="{{ $breadcrumb['url'] }}" wire:navigate class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-white">
                            @if($index == 0)
                                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                            @endif
                            {{ $breadcrumb['title'] }}
                        </a>
                    @else
                        <span class="text-sm font-medium text-neutral-500 dark:text-neutral-400">
                            {{ $breadcrumb['title'] }}
                        </span>
                    @endif
                @endif
            </li>
        @endforeach
    </ol>
</nav>
