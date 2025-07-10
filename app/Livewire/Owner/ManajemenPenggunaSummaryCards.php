<?php

namespace App\Livewire\Owner;

use App\Models\Admin;
use Livewire\Component;

class ManajemenPenggunaSummaryCards extends Component
{
    // Properties untuk menyimpan data statistik
    public $totalAdmin;
    public $totalTeknisi;
    public $totalPemilik;
    public $onlineAdmin;

    /**
     * Mount component dan load data awal
     */
    public function mount()
    {
        $this->loadStatistics();
    }

    /**
     * Load statistik pengguna berdasarkan role
     */
    public function loadStatistics()
    {
        // Hitung total admin (tidak termasuk yang dihapus)
        $this->totalAdmin = Admin::where('role', 'admin')->count();

        // Hitung total teknisi (tidak termasuk yang dihapus)
        $this->totalTeknisi = Admin::where('role', 'teknisi')->count();

        // Hitung total pemilik (tidak termasuk yang dihapus)
        $this->totalPemilik = Admin::where('role', 'pemilik')->count();

        // Hitung admin yang sedang online (aktif dalam 5 menit terakhir)
        $this->onlineAdmin = Admin::whereIn('role', ['admin', 'teknisi', 'pemilik'])
            ->where('last_seen_at', '>', now()->subMinutes(5))
            ->count();
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.owner.manajemen-pengguna-summary-cards');
    }
}
