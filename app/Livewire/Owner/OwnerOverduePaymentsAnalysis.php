<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\PaymentDetail;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Carbon\Carbon;

class OwnerOverduePaymentsAnalysis extends Component
{
    protected $listeners = ['refresh-dashboard' => '$refresh', 'refresh-charts' => 'loadOverduePayments'];

    public $overduePayments;

    public function mount()
    {
        $this->loadOverduePayments();
    }

    public function loadOverduePayments()
    {
        $this->overduePayments = $this->getOverduePayments();
    }

    private function getOverduePayments()
    {
        $now = Carbon::now();

        // Get overdue payments from orders
        $overdueProductOrders = OrderProduct::with(['customer'])
            ->where('status_order', '!=', 'completed')
            ->where('is_expired', true)
            ->whereHas('paymentDetails', function ($query) {
                $query->where('status', 'belum_dibayar');
            })
            ->get()
            ->map(function ($order) use ($now) {
                $overdueDays = round($now->diffInHours($order->expired_date) / 24);
                $unpaidAmount = $order->paymentDetails->where('status', 'belum_dibayar')->sum('amount');

                return [
                    'order_id' => $order->order_product_id,
                    'customer_name' => $order->customer->name,
                    'expired_date' => $order->expired_date,
                    'overdue_days' => $overdueDays,
                    'amount' => $unpaidAmount,
                    'type' => 'product'
                ];
            });

        $overdueServiceOrders = OrderService::with(['customer'])
            ->where('status_order', '!=', 'completed')
            ->where('is_expired', true)
            ->whereHas('paymentDetails', function ($query) {
                $query->where('status', 'belum_dibayar');
            })
            ->get()
            ->map(function ($order) use ($now) {
                $overdueDays = round($now->diffInHours($order->expired_date) / 24);
                $unpaidAmount = $order->paymentDetails->where('status', 'belum_dibayar')->sum('amount');

                return [
                    'order_id' => $order->order_service_id,
                    'customer_name' => $order->customer->name,
                    'expired_date' => $order->expired_date,
                    'overdue_days' => $overdueDays,
                    'amount' => $unpaidAmount,
                    'type' => 'service'
                ];
            });

        return $overdueProductOrders->concat($overdueServiceOrders)
            ->sortByDesc('overdue_days')
            ->take(10)
            ->values();
    }

    public function render()
    {
        return view('livewire.owner.owner-overdue-payments-analysis');
    }
}
