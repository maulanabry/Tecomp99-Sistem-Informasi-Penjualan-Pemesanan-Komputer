<?php

namespace App\Livewire\Teknisi;

use Livewire\Component;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\ServiceTicket;
use Illuminate\Support\Collection;

class GlobalSearch extends Component
{
    public $query = '';
    public $searchResults = [];
    public $showResults = false;

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->searchResults = [];
            $this->showResults = false;
            return;
        }

        $this->searchResults = $this->performSearch();
        $this->showResults = true;
    }

    private function performSearch(): array
    {
        $results = [];
        $query = '%' . $this->query . '%';

        // Search Order Services
        $orderServices = OrderService::where('order_service_id', 'like', $query)
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', $query);
            })
            ->orWhere('device', 'like', $query)
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_service_id,
                    'title' => 'Order Servis #' . $order->order_service_id,
                    'subtitle' => 'Customer: ' . $order->customer->name . ', Device: ' . $order->device,
                    'type' => 'order_service',
                    'url' => route('teknisi.order-services.show', $order->order_service_id)
                ];
            });
        if ($orderServices->isNotEmpty()) {
            $results['📦 Order Servis'] = $orderServices;
        }

        // Search Service Tickets
        $serviceTickets = ServiceTicket::where('service_ticket_id', 'like', $query)
            ->orWhere('admin_id', 'like', $query)
            ->orWhereHas('orderService.customer', function ($q) use ($query) {
                $q->where('name', 'like', $query);
            })
            ->limit(5)
            ->get()
            ->map(function ($ticket) {
                $scheduleText = $ticket->visit_schedule ?
                    $ticket->visit_schedule->format('d/m/Y H:i') : ($ticket->schedule_date ? $ticket->schedule_date->format('d/m/Y') : 'Belum dijadwalkan');

                return [
                    'id' => $ticket->service_ticket_id,
                    'title' => 'Tiket Servis #' . $ticket->service_ticket_id,
                    'subtitle' => 'Status: ' . ucfirst($ticket->status) . ', Jadwal: ' . $scheduleText,
                    'type' => 'service_ticket',
                    'url' => route('teknisi.service-tickets.show', $ticket->service_ticket_id)
                ];
            });
        if ($serviceTickets->isNotEmpty()) {
            $results['🎫 Tiket Servis'] = $serviceTickets;
        }

        // Search Customers
        $customers = Customer::where('name', 'like', $query)
            ->orWhere('contact', 'like', $query)
            ->orWhere('customer_id', 'like', $query)
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->customer_id,
                    'title' => $customer->name,
                    'subtitle' => 'ID: ' . $customer->customer_id . ', Kontak: ' . ($customer->contact ?: 'Tidak ada'),
                    'type' => 'customer',
                    'url' => route('teknisi.customers.show', $customer->customer_id)
                ];
            });
        if ($customers->isNotEmpty()) {
            $results['👤 Customer'] = $customers;
        }

        // Search Pages/Routes
        $pages = $this->searchPages();
        if (!empty($pages)) {
            $results['📁 Halaman'] = $pages;
        }

        return $results;
    }

    private function searchPages(): array
    {
        $pages = [
            [
                'name' => 'Dashboard',
                'url' => route('teknisi.dashboard.index'),
                'keywords' => ['dashboard', 'beranda', 'utama']
            ],
            [
                'name' => 'Order Servis',
                'url' => route('teknisi.order-services.index'),
                'keywords' => ['order', 'servis', 'pesanan', 'layanan']
            ],
            [
                'name' => 'Tiket Servis',
                'url' => route('teknisi.service-tickets.index'),
                'keywords' => ['tiket', 'ticket', 'servis', 'service']
            ],
            [
                'name' => 'Jadwal Servis',
                'url' => route('teknisi.jadwal-servis.index'),
                'keywords' => ['jadwal', 'schedule', 'kalender', 'calendar']
            ],
            [
                'name' => 'Customer',
                'url' => route('teknisi.customers.index'),
                'keywords' => ['customer', 'pelanggan', 'klien']
            ],
            [
                'name' => 'Pembayaran',
                'url' => route('teknisi.payments.index'),
                'keywords' => ['pembayaran', 'payment', 'bayar', 'tagihan']
            ],
            [
                'name' => 'Notifikasi',
                'url' => route('teknisi.notifications.index'),
                'keywords' => ['notifikasi', 'notification', 'pemberitahuan']
            ],
            [
                'name' => 'Pengaturan',
                'url' => route('teknisi.settings.index'),
                'keywords' => ['pengaturan', 'settings', 'konfigurasi', 'config']
            ]
        ];

        $matchedPages = [];
        $searchTerm = strtolower($this->query);

        foreach ($pages as $page) {
            $nameMatch = stripos($page['name'], $searchTerm) !== false;
            $keywordMatch = false;

            foreach ($page['keywords'] as $keyword) {
                if (stripos($keyword, $searchTerm) !== false) {
                    $keywordMatch = true;
                    break;
                }
            }

            if ($nameMatch || $keywordMatch) {
                $matchedPages[] = [
                    'id' => strtolower(str_replace(' ', '_', $page['name'])),
                    'title' => $page['name'],
                    'subtitle' => 'Halaman ' . $page['name'],
                    'type' => 'page',
                    'url' => $page['url']
                ];
            }
        }

        return array_slice($matchedPages, 0, 3); // Limit to 3 pages
    }

    public function render()
    {
        return view('livewire.teknisi.global-search');
    }
}
