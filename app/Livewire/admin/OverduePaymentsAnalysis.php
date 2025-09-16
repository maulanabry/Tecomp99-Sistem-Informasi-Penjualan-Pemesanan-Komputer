<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Carbon\Carbon;

class OverduePaymentsAnalysis extends Component
{
    public $overduePayments = [];
    public $summaryStats = [];
    public $showPaymentPlanModal = false;
    public $selectedOrderId = null;
    public $selectedOrderType = null;
    public $newDueDate = null;
    public $rescheduleNote = null;

    protected $listeners = ['refresh-dashboard' => 'loadData'];

    protected $rules = [
        'newDueDate' => 'required|date|after:today',
        'rescheduleNote' => 'nullable|string|max:500'
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Get overdue orders (those with expired_date in the past and not fully paid)
        $overdueProducts = OrderProduct::with('customer')
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', Carbon::now())
            ->whereIn('status_payment', ['belum_dibayar', 'down_payment'])
            ->orderBy('expired_date', 'asc')
            ->take(3)
            ->get();

        $overdueServices = OrderService::with('customer')
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', Carbon::now())
            ->whereIn('status_payment', ['belum_dibayar', 'cicilan'])
            ->orderBy('expired_date', 'asc')
            ->take(2)
            ->get();

        $this->overduePayments = collect()
            ->merge($overdueProducts->map(function ($order) {
                $daysOverdue = Carbon::parse($order->expired_date)->diffInDays(Carbon::now());
                return [
                    'order' => $order,
                    'type' => 'product',
                    'days_overdue' => $daysOverdue,
                    'due_date' => Carbon::parse($order->expired_date)->format('d/m/Y')
                ];
            }))
            ->merge($overdueServices->map(function ($order) {
                $daysOverdue = Carbon::parse($order->expired_date)->diffInDays(Carbon::now());
                return [
                    'order' => $order,
                    'type' => 'service',
                    'days_overdue' => $daysOverdue,
                    'due_date' => Carbon::parse($order->expired_date)->format('d/m/Y')
                ];
            }))
            ->sortByDesc('days_overdue')
            ->take(5)
            ->values()
            ->toArray();

        $this->calculateSummaryStats();
    }

    private function calculateSummaryStats()
    {
        $totalOverdueAmount = collect($this->overduePayments)->sum(function ($payment) {
            return $payment['order']->remaining_balance;
        });

        $totalOverdueCount = count($this->overduePayments);

        $avgOverdueDays = collect($this->overduePayments)->avg('days_overdue');
        $maxOverdueDays = collect($this->overduePayments)->max('days_overdue');

        $this->summaryStats = [
            'total_overdue_amount' => $totalOverdueAmount,
            'total_overdue_count' => $totalOverdueCount,
            'avg_overdue_days' => round($avgOverdueDays ?? 0),
            'max_overdue_days' => $maxOverdueDays ?? 0
        ];
    }

    public function sendPaymentReminder($orderId, $type)
    {
        // Logic to send payment reminder
        session()->flash('overdue_message', 'Pengingat pembayaran berhasil dikirim');
        session()->flash('overdue_type', 'success');
        $this->loadData();
    }

    public function showPaymentPlan($orderId, $type)
    {
        $this->selectedOrderId = $orderId;
        $this->selectedOrderType = $type;
        $this->showPaymentPlanModal = true;
        $this->newDueDate = Carbon::now()->addDays(7)->format('Y-m-d');
    }

    public function closePaymentPlanModal()
    {
        $this->showPaymentPlanModal = false;
        $this->selectedOrderId = null;
        $this->selectedOrderType = null;
        $this->newDueDate = null;
        $this->rescheduleNote = null;
        $this->resetErrorBag();
    }

    public function reschedulePayment()
    {
        $this->validate();

        // Logic to reschedule payment
        session()->flash('overdue_message', 'Jadwal pembayaran berhasil diubah');
        session()->flash('overdue_type', 'success');

        $this->closePaymentPlanModal();
        $this->loadData();
    }

    public function sendBulkReminders()
    {
        // Logic to send bulk reminders
        session()->flash('overdue_message', 'Pengingat massal berhasil dikirim');
        session()->flash('overdue_type', 'success');
        $this->loadData();
    }

    public function exportOverdueReport()
    {
        // Logic to export overdue report
        session()->flash('overdue_message', 'Laporan berhasil diexport');
        session()->flash('overdue_type', 'success');
    }

    public function showAllOverdue()
    {
        return redirect()->route('admin.payment.overdue');
    }

    public function render()
    {
        return view('livewire.admin.overdue-payments-analysis');
    }
}
