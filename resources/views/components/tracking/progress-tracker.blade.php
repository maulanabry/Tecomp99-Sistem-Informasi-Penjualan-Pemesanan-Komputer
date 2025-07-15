@props(['steps'])

<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Status Pesanan</h2>
    
    <div class="relative">
        @foreach($steps as $index => $step)
            <div class="flex items-start mb-8 {{ $loop->last ? 'mb-0' : '' }}">
                <!-- Step Icon -->
                <div class="flex-shrink-0 relative">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-2 
                        @if($step['status'] === 'completed') 
                            bg-green-100 border-green-500 text-green-600
                        @elseif($step['status'] === 'current') 
                            bg-blue-100 border-blue-500 text-blue-600 animate-pulse
                        @else 
                            bg-gray-100 border-gray-300 text-gray-400
                        @endif
                    ">
                        <i class="{{ $step['icon'] }} text-lg"></i>
                    </div>
                    
                    @if(!$loop->last)
                        <div class="absolute top-12 left-1/2 transform -translate-x-1/2 w-0.5 h-16 
                            @if($step['status'] === 'completed') bg-green-500 @else bg-gray-300 @endif
                        "></div>
                    @endif
                </div>
                
                <!-- Step Content -->
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold 
                            @if($step['status'] === 'completed') text-green-800
                            @elseif($step['status'] === 'current') text-blue-800
                            @else text-gray-500 @endif
                        ">
                            {{ $step['title'] }}
                        </h3>
                        
                        @if($step['date'])
                            <span class="text-sm text-gray-500">
                                {{ $step['date']->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-gray-600 mt-1">{{ $step['description'] }}</p>
                    
                    @if(isset($step['tracking_number']) && $step['tracking_number'])
                        <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-truck text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-blue-800">
                                    No. Resi: {{ $step['tracking_number'] }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
