<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\OrderService;
use App\Models\OrderServiceItem;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function penjualanProduk(Request $request)
    {
        // Get date range from request or default to current month
        $dates = $this->parseDateRange($request);
        $start = $dates['start'];
        $end = $dates['end'];
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Get sales summary data
        $salesSummary = $this->getSalesSummary($start, $end);

        // Get detailed sales data
        $salesData = $this->getSalesData($start, $end, $request);

        // Get chart data
        $chartData = $this->getChartData($start, $end);

        return view('owner.laporan.penjualan-produk', compact(
            'salesSummary',
            'salesData',
            'chartData',
            'startDate',
            'endDate'
        ));
    }

    private function getSalesSummary($start, $end)
    {
        // Total Product Orders
        $totalOrders = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->count();

        // Total Products Sold (sum of quantities from order items)
        $totalProductsSold = OrderProductItem::whereHas('orderProduct', function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end])
                ->whereNotIn('status_payment', ['dibatalkan']);
        })->sum('quantity');

        // Total Revenue (grand_total from paid orders)
        $totalRevenue = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereIn('status_payment', ['lunas', 'down_payment'])
            ->sum('grand_total');

        // Total Discounts
        $totalDiscounts = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->sum('discount_amount');

        // Total Shipping
        $totalShipping = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->where('type', 'pengiriman')
            ->sum('shipping_cost');

        return [
            'total_orders' => $totalOrders,
            'total_products_sold' => $totalProductsSold,
            'total_revenue' => $totalRevenue,
            'total_discounts' => $totalDiscounts,
            'total_shipping' => $totalShipping
        ];
    }

    private function getSalesData($start, $end, $request)
    {
        $query = OrderProduct::with(['customer', 'payments'])
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_product_id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Get items count for each order
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Add items count to each order
        foreach ($orders as $order) {
            $order->items_count = OrderProductItem::where('order_product_id', $order->order_product_id)->sum('quantity');
            $order->primary_payment_method = $order->payments->first()->method ?? 'Belum Ada Pembayaran';
        }

        return $orders;
    }

    private function getChartData($start, $end)
    {
        // Sales per day with discount and shipping
        $salesPerDay = OrderProduct::selectRaw('DATE(created_at) as date, SUM(grand_total) as total, SUM(discount_amount) as discount, SUM(CASE WHEN type = \'pengiriman\' THEN shipping_cost ELSE 0 END) as shipping, COUNT(*) as orders')
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status_payment', ['lunas', 'down_payment'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment method distribution
        $paymentMethods = PaymentDetail::selectRaw('method, COUNT(*) as count, SUM(amount) as total_amount')
            ->whereHas('orderProduct', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->where('status', 'dibayar')
            ->groupBy('method')
            ->get();

        // Top 5 Best-Selling Products
        $topProducts = Product::selectRaw('products.name, SUM(order_product_items.quantity) as total_sold, SUM(order_product_items.quantity * order_product_items.price) as total_revenue')
            ->join('order_product_items', 'products.product_id', '=', 'order_product_items.product_id')
            ->join('order_products', 'order_product_items.order_product_id', '=', 'order_products.order_product_id')
            ->whereBetween('order_products.created_at', [$start, $end])
            ->whereNotIn('order_products.status_payment', ['dibatalkan'])
            ->groupBy('products.product_id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Bottom 5 Lowest-Selling Products
        $lowProducts = Product::selectRaw('products.name, COALESCE(SUM(order_product_items.quantity), 0) as total_sold')
            ->leftJoin('order_product_items', 'products.product_id', '=', 'order_product_items.product_id')
            ->leftJoin('order_products', function ($join) use ($start, $end) {
                $join->on('order_product_items.order_product_id', '=', 'order_products.order_product_id')
                    ->whereBetween('order_products.created_at', [$start, $end])
                    ->whereNotIn('order_products.status_payment', ['dibatalkan']);
            })
            ->groupBy('products.product_id', 'products.name')
            ->orderBy('total_sold', 'asc')
            ->limit(5)
            ->get();

        // Order Types Data for chart
        $orderTypes = OrderProduct::selectRaw('COALESCE(type, "Tidak Diketahui") as order_type, COUNT(*) as total_orders, SUM(grand_total) as total_revenue')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('type')
            ->orderBy('total_orders', 'desc')
            ->get();

        return [
            'sales_per_day' => $salesPerDay,
            'payment_methods' => $paymentMethods,
            'top_products' => $topProducts,
            'low_products' => $lowProducts,
            'order_types' => $orderTypes
        ];
    }

    public function exportPdf(Request $request)
    {
        // Get the same data as the main view
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $isPrint = $request->get('print', false);

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $salesSummary = $this->getSalesSummary($start, $end);
        $salesData = $this->getSalesData($start, $end, $request);

        try {
            // Try to use dompdf if available
            $pdf = Pdf::loadView('owner.laporan.penjualan-produk-pdf', compact(
                'salesSummary',
                'salesData',
                'startDate',
                'endDate'
            ))->setPaper('a4', 'landscape');

            $filename = "Laporan Penjualan Produk periode {$startDate} - {$endDate}.pdf";

            // If print parameter is present, stream the PDF for viewing/printing
            if ($isPrint) {
                return $pdf->stream($filename);
            }

            // Otherwise, download the PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Fallback: Return HTML view with download headers
            $filename = "Laporan Penjualan Produk periode {$startDate} - {$endDate}.html";

            $headers = [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response()->view('owner.laporan.penjualan-produk-pdf', compact(
                'salesSummary',
                'salesData',
                'startDate',
                'endDate'
            ), 200, $headers);
        }
    }

    public function exportExcel(Request $request)
    {
        // This would require Laravel Excel package
        // For now, we'll return CSV format
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $salesData = OrderProduct::with(['customer', 'payments'])
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan-penjualan-produk-' . $startDate . '-to-' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($salesData) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding in Excel
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Headers
            fputcsv($file, [
                'ID Order',
                'Tanggal Order',
                'Nama Customer',
                'Jumlah Item',
                'Total Harga',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Status Order'
            ]);

            foreach ($salesData as $order) {
                $itemsCount = OrderProductItem::where('order_product_id', $order->order_product_id)->sum('quantity');
                $paymentMethod = $order->payments->first()->method ?? 'Belum Ada Pembayaran';

                fputcsv($file, [
                    $order->order_product_id,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->customer->name ?? 'N/A',
                    $itemsCount,
                    number_format($order->grand_total, 0, ',', ''),
                    $paymentMethod,
                    ucfirst(str_replace('_', ' ', $order->status_payment)),
                    ucfirst($order->status_order)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ===== SERVICE REPORT METHODS =====

    public function pemesananServis(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Parse dates
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get service summary data
        $serviceSummary = $this->getServiceSummary($start, $end);

        // Get detailed service data
        $serviceData = $this->getServiceData($start, $end, $request);

        // Get chart data for services
        $chartData = $this->getServiceChartData($start, $end);

        return view('owner.laporan.pemesanan-servis', compact(
            'serviceSummary',
            'serviceData',
            'chartData',
            'startDate',
            'endDate'
        ));
    }

    private function getServiceSummary($start, $end)
    {
        // Total Service Orders
        $totalOrders = OrderService::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->count();

        // Total Services Ordered (count orders with status_payment = belum_dibayar)
        $totalServicesOrdered = OrderService::whereBetween('created_at', [$start, $end])
            ->where('status_payment', 'belum_dibayar')
            ->count();

        // Total Revenue (grand_total from LUNAS orders only)
        $totalRevenue = OrderService::whereBetween('created_at', [$start, $end])
            ->where('status_payment', 'lunas')
            ->sum('grand_total');

        // Total Discounts
        $totalDiscounts = OrderService::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->sum('discount_amount');

        // Count of orders with technicians assigned
        $ordersWithTechnicians = OrderService::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->whereHas('tickets')
            ->count();

        // Count of completed orders
        $completedOrders = OrderService::whereBetween('created_at', [$start, $end])
            ->where('status_order', 'selesai')
            ->count();

        // Total paid amount from down_payment orders
        $downPaymentAmount = OrderService::whereBetween('created_at', [$start, $end])
            ->where('status_payment', 'down_payment')
            ->sum('paid_amount');

        // Average revenue per order (only from lunas orders)
        $averagePerOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'total_orders' => $totalOrders,
            'total_services_ordered' => $totalServicesOrdered,
            'total_revenue' => $totalRevenue,
            'total_discounts' => $totalDiscounts,
            'orders_with_technicians' => $ordersWithTechnicians,
            'completed_orders' => $completedOrders,
            'down_payment_amount' => $downPaymentAmount,
            'average_per_order' => $averagePerOrder
        ];
    }

    private function getServiceData($start, $end, $request)
    {
        $query = OrderService::with(['customer', 'paymentDetails', 'tickets.admin'])
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_service_id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Get orders with pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Add additional data to each order
        foreach ($orders as $order) {
            $order->items_count = OrderServiceItem::where('order_service_id', $order->order_service_id)->sum('quantity');
            $order->primary_payment_method = $order->paymentDetails->first()->method ?? 'Belum Ada Pembayaran';

            // Get technician name from service tickets
            $ticket = $order->tickets->first();
            $order->technician_name = $ticket && $ticket->admin
                ? $ticket->admin->name
                : 'Belum Ditugaskan';
        }

        return $orders;
    }

    private function getServiceDataForExport($start, $end, $request)
    {
        $query = OrderService::with(['customer', 'paymentDetails', 'tickets.admin'])
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_service_id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Get ALL orders without pagination for export
        $orders = $query->orderBy('created_at', 'desc')->get();

        // Add additional data to each order
        foreach ($orders as $order) {
            $order->items_count = OrderServiceItem::where('order_service_id', $order->order_service_id)->sum('quantity');
            $order->primary_payment_method = $order->paymentDetails->first()->method ?? 'Belum Ada Pembayaran';

            // Get technician name from service tickets
            $ticket = $order->tickets->first();
            $order->technician_name = $ticket && $ticket->admin
                ? $ticket->admin->name
                : 'Belum Ditugaskan';
        }

        return $orders;
    }

    private function getServiceChartData($start, $end)
    {
        // Service orders per day with discounts
        $servicePerDay = OrderService::selectRaw('DATE(created_at) as date, SUM(grand_total) as total, SUM(discount_amount) as discount, COUNT(*) as orders')
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status_payment', ['lunas', 'down_payment'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment method distribution for services
        $paymentMethods = PaymentDetail::selectRaw('method, COUNT(*) as count, SUM(amount) as total_amount')
            ->whereHas('orderService', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->where('status', 'dibayar')
            ->groupBy('method')
            ->get();

        // Top 5 Most Ordered Services
        $topServices = Service::selectRaw('service.name, SUM(order_service_items.quantity) as total_ordered, SUM(order_service_items.quantity * order_service_items.price) as total_revenue')
            ->join('order_service_items', 'service.service_id', '=', 'order_service_items.item_id')
            ->join('order_services', 'order_service_items.order_service_id', '=', 'order_services.order_service_id')
            ->where('order_service_items.item_type', 'App\\Models\\Service')
            ->whereBetween('order_services.created_at', [$start, $end])
            ->whereNotIn('order_services.status_payment', ['dibatalkan'])
            ->groupBy('service.service_id', 'service.name')
            ->orderBy('total_ordered', 'desc')
            ->limit(5)
            ->get();

        // Bottom 5 Least Ordered Services
        $lowServices = Service::selectRaw('service.name, COALESCE(SUM(order_service_items.quantity), 0) as total_ordered')
            ->leftJoin('order_service_items', function ($join) {
                $join->on('service.service_id', '=', 'order_service_items.item_id')
                    ->where('order_service_items.item_type', '=', 'App\\Models\\Service');
            })
            ->leftJoin('order_services', function ($join) use ($start, $end) {
                $join->on('order_service_items.order_service_id', '=', 'order_services.order_service_id')
                    ->whereBetween('order_services.created_at', [$start, $end])
                    ->whereNotIn('order_services.status_payment', ['dibatalkan']);
            })
            ->groupBy('service.service_id', 'service.name')
            ->orderBy('total_ordered', 'asc')
            ->limit(5)
            ->get();

        // Service Types (Reguler vs Onsite)
        $serviceTypes = OrderService::selectRaw('COALESCE(type, "Reguler") as service_type, COUNT(*) as total_orders, SUM(grand_total) as total_revenue')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('type')
            ->orderBy('total_orders', 'desc')
            ->get();

        return [
            'service_per_day' => $servicePerDay,
            'payment_methods' => $paymentMethods,
            'top_services' => $topServices,
            'low_services' => $lowServices,
            'service_types' => $serviceTypes
        ];
    }

    public function exportServicePdf(Request $request)
    {
        // Get the same data as the main view
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $isPrint = $request->get('print', false);

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $serviceSummary = $this->getServiceSummary($start, $end);
        // Get ALL data for PDF export (not paginated)
        $serviceData = $this->getServiceDataForExport($start, $end, $request);

        try {
            // Try to use dompdf if available
            $pdf = Pdf::loadView('owner.laporan.pemesanan-servis-pdf', compact(
                'serviceSummary',
                'serviceData',
                'startDate',
                'endDate'
            ))->setPaper('a4', 'landscape');

            $filename = "Laporan Pemesanan Servis periode {$startDate} - {$endDate}.pdf";

            // If print parameter is present, stream the PDF for viewing/printing
            if ($isPrint) {
                return $pdf->stream($filename);
            }

            // Otherwise, download the PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Fallback: Return HTML view with download headers
            $filename = "Laporan Pemesanan Servis periode {$startDate} - {$endDate}.html";

            $headers = [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response()->view('owner.laporan.pemesanan-servis-pdf', compact(
                'serviceSummary',
                'serviceData',
                'startDate',
                'endDate'
            ), 200, $headers);
        }
    }

    public function exportServiceExcel(Request $request)
    {
        // CSV format for service orders
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $serviceData = OrderService::with(['customer', 'paymentDetails', 'tickets.admin'])
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan-pemesanan-servis-' . $startDate . '-to-' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($serviceData) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding in Excel
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Headers
            fputcsv($file, [
                'ID Pemesanan',
                'Nama Customer',
                'Tanggal Pemesanan',
                'Jenis Layanan',
                'Nama Teknisi',
                'Status Order',
                'Status Pembayaran',
                'Total Bayar',
                'Diskon',
                'Metode Pembayaran'
            ]);

            foreach ($serviceData as $order) {
                $itemsCount = OrderServiceItem::where('order_service_id', $order->order_service_id)->sum('quantity');
                $paymentMethod = $order->paymentDetails->first()->method ?? 'Belum Ada Pembayaran';

                // Get technician name
                $ticket = $order->tickets->first();
                $technicianName = $ticket && $ticket->admin
                    ? $ticket->admin->name
                    : 'Belum Ditugaskan';

                fputcsv($file, [
                    $order->order_service_id,
                    $order->customer->name ?? 'N/A',
                    $order->created_at->format('d/m/Y H:i'),
                    ucfirst($order->type ?? 'Reguler'),
                    $technicianName,
                    ucfirst($order->status_order),
                    ucfirst(str_replace('_', ' ', $order->status_payment)),
                    number_format($order->grand_total, 0, ',', ''),
                    number_format($order->discount_amount, 0, ',', ''),
                    $paymentMethod
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function parseDateRange(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];
    }
}
