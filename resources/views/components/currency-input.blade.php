@props([
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => 'Rp 0',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'min' => 0,
    'max' => null,
    'step' => 1000,
    'class' => '',
    'label' => null,
    'help' => null,
    'error' => null,
    'livewire' => false,
    'allowEmpty' => true,
    'icon' => 'fas fa-money-bill-wave',
    'iconPosition' => 'left'
])

@php
    $inputId = $id ?? $name;
    $hasError = $error || $errors->has($name);
    $baseClasses = 'block w-full px-3 py-2 border rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200';
    
    if ($hasError) {
        $borderClasses = 'border-red-500 dark:border-red-400 focus:ring-red-500 focus:border-red-500';
    } else {
        $borderClasses = 'border-gray-300 dark:border-gray-600';
    }
    
    if ($iconPosition === 'left') {
        $inputClasses = 'pl-12 pr-3';
    } else {
        $inputClasses = 'pl-3 pr-12';
    }
    
    $finalClasses = $baseClasses . ' ' . $borderClasses . ' ' . $inputClasses . ' ' . $class;
@endphp

<div class="space-y-2">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative rounded-md shadow-sm">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400 dark:text-gray-500"></i>
            </div>
        @endif
        
        <input 
            type="number"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="{{ $finalClasses }}"
            data-currency="true"
            data-currency-livewire="{{ $livewire ? 'true' : 'false' }}"
            data-currency-allow-empty="{{ $allowEmpty ? 'true' : 'false' }}"
            data-currency-min="{{ $min }}"
            @if($max) data-currency-max="{{ $max }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($step) step="{{ $step }}" @endif
            {{ $attributes->except(['class', 'type', 'name', 'id', 'value', 'placeholder', 'required', 'disabled', 'readonly', 'min', 'max', 'step']) }}
        >
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400 dark:text-gray-500"></i>
            </div>
        @endif
    </div>
    
    @if($help && !$hasError)
        <p class="text-xs text-gray-500 dark:text-gray-400">
            <i class="fas fa-info-circle mr-1"></i>
            {{ $help }}
        </p>
    @endif
    
    @if($hasError)
        <p class="text-sm text-red-600 dark:text-red-400">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $error ?? $errors->first($name) }}
        </p>
    @endif
</div>

@once
    @push('scripts')
        <script src="{{ asset('js/currency-formatter.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize currency formatters with enhanced options
                const currencyInputs = document.querySelectorAll('input[data-currency="true"]');
                
                currencyInputs.forEach(input => {
                    const options = {
                        allowEmpty: input.dataset.currencyAllowEmpty === 'true',
                        minValue: parseInt(input.dataset.currencyMin) || 0,
                        maxValue: input.dataset.currencyMax ? parseInt(input.dataset.currencyMax) : null,
                        livewire: input.dataset.currencyLivewire === 'true',
                        placeholder: input.placeholder || 'Rp 0',
                        onValueChange: function(numericValue, formattedValue) {
                            // Dispatch custom event for other scripts to listen
                            const event = new CustomEvent('currencyValueChanged', {
                                detail: {
                                    input: input,
                                    numericValue: numericValue,
                                    formattedValue: formattedValue
                                }
                            });
                            input.dispatchEvent(event);
                            
                            // Livewire integration
                            if (options.livewire && window.Livewire) {
                                // Find the Livewire component and update the property
                                const component = input.closest('[wire\\:id]');
                                if (component) {
                                    const wireId = component.getAttribute('wire:id');
                                    const propertyName = input.getAttribute('wire:model') || input.getAttribute('wire:model.defer');
                                    
                                    if (propertyName && window.Livewire.find(wireId)) {
                                        window.Livewire.find(wireId).set(propertyName, numericValue);
                                    }
                                }
                            }
                        }
                    };
                    
                    window.rupiahFormatter.init(input, options);
                });
                
                // Handle dynamic content (for AJAX loaded forms)
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                const newCurrencyInputs = node.querySelectorAll ? 
                                    node.querySelectorAll('input[data-currency="true"]') : [];
                                
                                newCurrencyInputs.forEach(input => {
                                    if (!input.hasAttribute('data-currency-formatter')) {
                                        const options = {
                                            allowEmpty: input.dataset.currencyAllowEmpty === 'true',
                                            minValue: parseInt(input.dataset.currencyMin) || 0,
                                            maxValue: input.dataset.currencyMax ? parseInt(input.dataset.currencyMax) : null,
                                            livewire: input.dataset.currencyLivewire === 'true',
                                            placeholder: input.placeholder || 'Rp 0'
                                        };
                                        
                                        window.rupiahFormatter.init(input, options);
                                    }
                                });
                            }
                        });
                    });
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            });
        </script>
    @endpush
@endonce
