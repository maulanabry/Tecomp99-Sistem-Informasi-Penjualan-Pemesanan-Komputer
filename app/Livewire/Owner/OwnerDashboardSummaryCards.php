<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use App\Models\Customer;
use Carbon\Carbon;

class OwnerDashboardSummaryCards extends Component
{
    public $showMore = false;

    // Card data
    public $totalPendapatanKotor;
    public $totalOrder;
    public $pesananMelewatiBatasWaktu;
    public $customerBaruBulanIni;
    public $totalCicilan;
    public $totalDownPayment;
    public $servisSelesaiBelumDiambil;
    public $pembayaranBelumLunas;

    public function mount()
    {
        $this->calculateCards();
    }

    public function calculateCards()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // Total Pendapatan Kotor (current month)
        $this->totalPendapatanKotor = PaymentDetail::where('status', 'dibayar')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        // Total Order (Product + Service)
        $this->totalOrder = OrderProduct::count() + OrderService::count();

        // Pesanan Melewati Batas Waktu (orders past deadline)
        $this->pesananMelewatiBatasWaktu = OrderService::where('expired_date', '<', $now)
            ->where('status_order', '!=', 'selesai')
            ->count();

        // Customer Baru Bulan Ini
        $this->customerBaruBulanIni = Customer::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Total Cicilan (installment payments)
        $this->totalCicilan = PaymentDetail::where('payment_type', 'cicilan')
            ->where('status', 'dibayar')
            ->sum('amount');

        // Total Down Payment
        $this->totalDownPayment = PaymentDetail::where('payment_type', 'down_payment')
            ->where('status', 'dibayar')
            ->sum('amount');

        // Servis Selesai Belum Diambil (services ready for pickup)
        $this->servisSelesaiBelumDiambil = OrderService::where('status_order', 'siap_diambil')
            ->count();

        // Pembayaran Belum Lunas
        $this->pembayaranBelumLunas = PaymentDetail::where('status', 'belum_dibayar')
            ->count();
    }

    public function toggleShowMore()
    {
        $this->showMore = !$this->showMore;
    }

    public function refreshCards()
    {
        $this->calculateCards();
    }

    public function getTotalRevenue()
    {
        return $this->totalPendapatanKotor;
    }

    public function render()
    {
        return view('livewire.owner.owner-dashboard-summary-cards');
    }
}
