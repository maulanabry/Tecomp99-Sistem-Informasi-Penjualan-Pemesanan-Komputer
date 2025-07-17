<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Sidebar extends Component
{
    public $isDataMasterOpen = false;
    public $isOrderOpen = false;
    public $isSidebarOpen = false;

    protected $listeners = ['toggleSidebar' => 'toggleSidebar'];

    public function mount()
    {
        // Initialize open states based on current route
        $this->isDataMasterOpen = request()->is('admin/kategori*') || request()->is('admin/brand*') || request()->is('produk.*') || request()->is('admin/servis*') || request()->is('admin/voucher*') || request()->is('admin/pelanggan*');
        $this->isOrderOpen = request()->is('order.servis') || request()->is('order.produk');
    }

    public function toggleDataMaster()
    {
        $this->isDataMasterOpen = !$this->isDataMasterOpen;
    }

    public function toggleOrder()
    {
        $this->isOrderOpen = !$this->isOrderOpen;
    }

    public function toggleSidebar()
    {
        $this->isSidebarOpen = !$this->isSidebarOpen;
    }

    public function render()
    {
        return view('livewire.admin.sidebar');
    }
}
