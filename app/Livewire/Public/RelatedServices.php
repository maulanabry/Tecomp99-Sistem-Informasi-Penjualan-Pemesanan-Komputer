<?php

namespace App\Livewire\Public;

use App\Models\Service;
use Livewire\Component;

class RelatedServices extends Component
{
    public $serviceId;

    public function render()
    {
        $service = Service::find($this->serviceId);

        if (!$service) {
            return view('livewire.public.related-services', [
                'relatedServices' => collect()
            ]);
        }

        $relatedServices = Service::where('categories_id', $service->categories_id)
            ->where('service_id', '!=', $this->serviceId)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.public.related-services', [
            'relatedServices' => $relatedServices
        ]);
    }
}
