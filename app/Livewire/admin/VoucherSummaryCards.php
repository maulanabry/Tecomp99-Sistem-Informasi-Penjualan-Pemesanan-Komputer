<?php

namespace App\Livewire\Admin;

use App\Models\Voucher;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Exception;

class VoucherSummaryCards extends Component
{
    public $totalVoucher;
    public $voucherAktif;
    public $voucherTidakAktif;
    public $voucherTerhapus;
    public $showAll = false;

    public function mount()
    {
        try {
            $this->totalVoucher = Voucher::count();
            $this->voucherAktif = Voucher::where('is_active', true)->count();
            $this->voucherTidakAktif = Voucher::where('is_active', false)->count();
            $this->voucherTerhapus = Voucher::onlyTrashed()->count();
        } catch (Exception $e) {
            Log::error('Error fetching voucher counts: ' . $e->getMessage());
            $this->totalVoucher = 0;
            $this->voucherAktif = 0;
            $this->voucherTidakAktif = 0;
            $this->voucherTerhapus = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.voucher-summary-cards');
    }
}
