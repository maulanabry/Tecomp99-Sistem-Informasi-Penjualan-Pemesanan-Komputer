<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    /**
     * Track product order by order ID
     */
    public function trackProduct($order_id)
    {
        $order = OrderProduct::with([
            'customer',
            'items.product',
            'shipping',
            'paymentDetails' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->where('order_product_id', $order_id)->first();

        if (!$order) {
            return view('public.tracking.not-found', [
                'type' => 'produk',
                'order_id' => $order_id
            ]);
        }

        // Calculate progress steps
        $steps = $this->getProductSteps($order);

        return view('public.tracking.tracking-order-product-details', compact('order', 'steps'));
    }

    /**
     * Track service order by order ID
     */
    public function trackService($order_id)
    {
        $order = OrderService::with([
            'customer',
            'items.item',
            'tickets.actions' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'paymentDetails' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->where('order_service_id', $order_id)->first();

        if (!$order) {
            return view('public.tracking.not-found', [
                'type' => 'servis',
                'order_id' => $order_id
            ]);
        }

        // Calculate progress steps
        $steps = $this->getServiceSteps($order);

        return view('public.tracking.tracking-order-service-details', compact('order', 'steps'));
    }

    /**
     * Get product order progress steps
     */
    private function getProductSteps($order)
    {
        $steps = [
            [
                'title' => 'Pesanan Diterima',
                'description' => 'Pesanan Anda telah diterima dan sedang diproses',
                'status' => 'completed',
                'date' => $order->created_at,
                'icon' => 'fas fa-check-circle'
            ]
        ];

        // Step 2: Dikemas
        if (in_array($order->status_order, ['dikemas', 'dikirim', 'selesai'])) {
            $steps[] = [
                'title' => 'Dikemas',
                'description' => 'Pesanan sedang dikemas untuk pengiriman',
                'status' => 'completed',
                'date' => $order->updated_at,
                'icon' => 'fas fa-box'
            ];
        } else {
            $steps[] = [
                'title' => 'Dikemas',
                'description' => 'Menunggu proses pengemasan',
                'status' => $order->status_order === 'diproses' ? 'current' : 'pending',
                'date' => null,
                'icon' => 'fas fa-box'
            ];
        }

        // Step 3: Dikirim (only for shipping type)
        if ($order->type === 'pengiriman') {
            if (in_array($order->status_order, ['dikirim', 'selesai'])) {
                $steps[] = [
                    'title' => 'Dikirim',
                    'description' => $order->shipping ? 'Paket sedang dalam perjalanan' : 'Sedang dipersiapkan untuk pengiriman',
                    'status' => 'completed',
                    'date' => $order->shipping?->shipped_at ?? $order->updated_at,
                    'icon' => 'fas fa-truck',
                    'tracking_number' => $order->shipping?->tracking_number
                ];
            } else {
                $steps[] = [
                    'title' => 'Dikirim',
                    'description' => 'Menunggu pengiriman',
                    'status' => $order->status_order === 'dikemas' ? 'current' : 'pending',
                    'date' => null,
                    'icon' => 'fas fa-truck'
                ];
            }
        }

        // Step 4: Selesai
        if ($order->status_order === 'selesai') {
            $steps[] = [
                'title' => 'Selesai',
                'description' => $order->type === 'pengiriman' ? 'Paket telah diterima' : 'Pesanan siap diambil',
                'status' => 'completed',
                'date' => $order->shipping?->delivered_at ?? $order->updated_at,
                'icon' => 'fas fa-check-double'
            ];
        } else {
            $steps[] = [
                'title' => 'Selesai',
                'description' => $order->type === 'pengiriman' ? 'Menunggu konfirmasi penerimaan' : 'Menunggu pengambilan',
                'status' => 'pending',
                'date' => null,
                'icon' => 'fas fa-check-double'
            ];
        }

        return $steps;
    }

    /**
     * Get service order progress steps
     */
    private function getServiceSteps($order)
    {
        $steps = [
            [
                'title' => 'Pesanan Masuk',
                'description' => 'Pesanan servis Anda telah diterima',
                'status' => 'completed',
                'date' => $order->created_at,
                'icon' => 'fas fa-clipboard-list'
            ]
        ];

        $ticket = $order->tickets->first();

        // Step 2: Tiket Dibuat
        if ($ticket) {
            $steps[] = [
                'title' => 'Tiket Dibuat',
                'description' => 'Tiket servis telah dibuat dengan ID: ' . $ticket->service_ticket_id,
                'status' => 'completed',
                'date' => $ticket->created_at,
                'icon' => 'fas fa-ticket-alt'
            ];

            // For onsite services, add visit scheduling steps
            if ($order->type === 'onsite') {
                if ($ticket->visit_schedule) {
                    $steps[] = [
                        'title' => 'Kunjungan Dijadwalkan',
                        'description' => 'Teknisi akan berkunjung pada: ' . $ticket->visit_schedule->format('d/m/Y H:i'),
                        'status' => $ticket->visit_schedule->isPast() ? 'completed' : 'current',
                        'date' => $ticket->visit_schedule,
                        'icon' => 'fas fa-calendar-check'
                    ];
                } else {
                    $steps[] = [
                        'title' => 'Menunggu Kunjungan',
                        'description' => 'Menunggu penjadwalan kunjungan teknisi',
                        'status' => 'current',
                        'date' => null,
                        'icon' => 'fas fa-calendar-alt'
                    ];
                }
            }

            // Step 3: Sedang Dikerjakan
            if (in_array($ticket->status, ['dikerjakan', 'selesai'])) {
                $steps[] = [
                    'title' => 'Sedang Dikerjakan',
                    'description' => 'Teknisi sedang mengerjakan servis Anda',
                    'status' => 'completed',
                    'date' => $ticket->actions->where('action', 'like', '%mulai%')->first()?->created_at ?? $ticket->updated_at,
                    'icon' => 'fas fa-tools'
                ];
            } else {
                $steps[] = [
                    'title' => 'Sedang Dikerjakan',
                    'description' => 'Menunggu teknisi memulai pekerjaan',
                    'status' => in_array($ticket->status, ['menunggu', 'dijadwalkan']) ? 'current' : 'pending',
                    'date' => null,
                    'icon' => 'fas fa-tools'
                ];
            }

            // Step 4: Selesai
            if ($ticket->status === 'selesai') {
                $steps[] = [
                    'title' => 'Selesai',
                    'description' => 'Servis telah selesai dikerjakan',
                    'status' => 'completed',
                    'date' => $ticket->actions->where('action', 'like', '%selesai%')->first()?->created_at ?? $ticket->updated_at,
                    'icon' => 'fas fa-check-double'
                ];
            } else {
                $steps[] = [
                    'title' => 'Selesai',
                    'description' => 'Menunggu penyelesaian servis',
                    'status' => 'pending',
                    'date' => null,
                    'icon' => 'fas fa-check-double'
                ];
            }
        } else {
            // No ticket created yet
            $steps[] = [
                'title' => 'Tiket Dibuat',
                'description' => 'Menunggu pembuatan tiket servis',
                'status' => 'current',
                'date' => null,
                'icon' => 'fas fa-ticket-alt'
            ];

            $steps[] = [
                'title' => 'Sedang Dikerjakan',
                'description' => 'Menunggu teknisi memulai pekerjaan',
                'status' => 'pending',
                'date' => null,
                'icon' => 'fas fa-tools'
            ];

            $steps[] = [
                'title' => 'Selesai',
                'description' => 'Menunggu penyelesaian servis',
                'status' => 'pending',
                'date' => null,
                'icon' => 'fas fa-check-double'
            ];
        }

        return $steps;
    }

    /**
     * Show search form for order tracking
     */
    public function search()
    {
        return view('public.tracking.search');
    }

    /**
     * Handle search form submission
     */
    public function handleSearch(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'type' => 'required|in:produk,servis'
        ]);

        $orderId = $request->order_id;
        $type = $request->type;

        if ($type === 'produk') {
            return redirect()->route('tracking.product', $orderId);
        } else {
            return redirect()->route('tracking.service', $orderId);
        }
    }
}
