<?php

namespace App\Livewire\Public;

use Livewire\Component;

class FeaturesSection extends Component
{
    public $features = [
        [
            'icon' => 'fas fa-mobile-alt',
            'title' => 'Responsif',
            'description' => 'Layanan cepat dan responsif untuk semua kebutuhan IT Anda'
        ],
        [
            'icon' => 'fas fa-shield-alt',
            'title' => 'Terpercaya',
            'description' => 'Dipercaya ribuan pelanggan di Surabaya sejak bertahun-tahun'
        ],
        [
            'icon' => 'fas fa-check-circle',
            'title' => 'Bergaransi',
            'description' => 'Semua layanan dan produk dilengkapi dengan garansi resmi'
        ],
        [
            'icon' => 'fas fa-shipping-fast',
            'title' => 'Pengiriman Nasional',
            'description' => 'Melayani pengiriman ke seluruh Indonesia dengan aman'
        ]
    ];

    public function render()
    {
        return view('livewire.public.features-section');
    }
}
