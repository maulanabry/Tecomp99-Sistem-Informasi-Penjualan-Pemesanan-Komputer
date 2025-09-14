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
        // A. Total Pesanan Produk (jumlah semua order product)
        $totalOrders = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->count();

        // B. Total Produk Terjual (sum dari quantity produk pada order yang statusnya selesai)
        $totalProductsSold = OrderProductItem::whereHas('orderProduct', function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end])
                ->where('status_order', 'selesai')
                ->whereNotIn('status_payment', ['dibatalkan']);
        })->sum('quantity');

        // C. Total Pendapatan (Kotor) → hitung dari order_products dengan status_payment = lunas
        $totalRevenue = OrderProduct::whereBetween('created_at', [$start, $end])
            ->where('status_payment', 'lunas')
            ->sum('grand_total');

        // D. Jumlah Produk dengan Stok Rendah/Habis (misal stok < 5)
        $lowStockProducts = Product::where('stock', '<', 5)->count();

        // E. Pesanan yang Belum Diselesaikan → order product dengan status_order selain selesai dan dibatalkan
        $pendingOrders = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->count();

        // F. Total Diskon
        $totalDiscounts = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->sum('discount_amount');

        // G. Total Ongkir
        $totalShipping = OrderProduct::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->sum('shipping_cost');

        return [
            'total_orders' => $totalOrders,
            'total_products_sold' => $totalProductsSold,
            'total_revenue' => $totalRevenue,
            'low_stock_products' => $lowStockProducts,
            'pending_orders' => $pendingOrders,
            'total_discounts' => $totalDiscounts,
            'total_shipping' => $totalShipping
        ];
    }

    private function getSalesData($start, $end, $request)
    {
        $query = OrderProduct::with(['customer', 'payments'])
            ->whereBetween('order_products.created_at', [$start, $end])
            ->whereNotIn('order_products.status_payment', ['dibatalkan']);

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

        // Filter by status_order
        if ($request->has('status_order') && !empty($request->status_order)) {
            $query->where('order_products.status_order', $request->status_order);
        }

        // Filter by status_payment
        if ($request->has('status_payment') && !empty($request->status_payment)) {
            $query->where('order_products.status_payment', $request->status_payment);
        }

        // Filter by payment method
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->whereHas('payments', function ($paymentQuery) use ($request) {
                $paymentQuery->where('method', $request->payment_method);
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Validate sort_by to prevent SQL injection
        $allowedSortFields = ['created_at', 'grand_total', 'order_product_id', 'customer_name', 'items_count', 'primary_payment_method', 'status_payment', 'status_order'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }

        // Handle special sort fields
        if ($sortBy === 'customer_name') {
            $query->leftJoin('customers', 'order_products.customer_id', '=', 'customers.customer_id')
                ->orderBy('customers.name', $sortDirection);
        } elseif ($sortBy === 'items_count') {
            // For items_count, we need to add it as a subquery or handle differently
            // Since items_count is calculated after, we'll sort by created_at for now
            $query->orderBy('order_products.created_at', $sortDirection);
        } elseif ($sortBy === 'primary_payment_method') {
            // Sort by payment method from payments relationship
            $query->leftJoin('payment_details', function ($join) {
                $join->on('order_products.order_product_id', '=', 'payment_details.order_product_id')
                    ->where('payment_details.status', 'dibayar');
            })->orderBy('payment_details.method', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Get items count for each order
        $orders = $query->paginate(15)->appends($request->query());

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

        // A. Sales per day by payment status
        $salesByPaymentStatus = OrderProduct::selectRaw('DATE(created_at) as date, status_payment, SUM(grand_total) as total')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('date', 'status_payment')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

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

        // D. Low stock products
        $lowStockProducts = Product::select('name', 'stock')
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Order Types Data for chart
        $orderTypes = OrderProduct::selectRaw('COALESCE(type, "Tidak Diketahui") as order_type, COUNT(*) as total_orders, SUM(grand_total) as total_revenue')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('type')
            ->orderBy('total_orders', 'desc')
            ->get();

        // H. Payment status distribution
        $paymentStatusDistribution = OrderProduct::selectRaw('status_payment, COUNT(*) as count, SUM(grand_total) as total_amount')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('status_payment')
            ->get();

        return [
            'sales_per_day' => $salesPerDay,
            'sales_by_payment_status' => $salesByPaymentStatus,
            'payment_methods' => $paymentMethods,
            'top_products' => $topProducts,
            'low_products' => $lowProducts,
            'low_stock_products' => $lowStockProducts,
            'order_types' => $orderTypes,
            'payment_status_distribution' => $paymentStatusDistribution
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

        // Get technicians for filter
        $technicians = DB::table('admins')
            ->join('service_tickets', 'admins.id', '=', 'service_tickets.admin_id')
            ->join('order_services', 'service_tickets.order_service_id', '=', 'order_services.order_service_id')
            ->whereBetween('order_services.created_at', [$start, $end])
            ->whereNotIn('order_services.status_payment', ['dibatalkan'])
            ->select('admins.id', 'admins.name')
            ->distinct()
            ->orderBy('admins.name')
            ->get();

        return view('owner.laporan.pemesanan-servis', compact(
            'serviceSummary',
            'serviceData',
            'chartData',
            'technicians',
            'startDate',
            'endDate'
        ));
    }

    private function getServiceSummary($start, $end)
    {
        // A. Total Service Orders → with subheading showing total revenue (Rp)
        $totalOrders = OrderService::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->count();

        $totalRevenueAll = OrderService::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->sum('grand_total');

        // B. Total Completed Orders
        $completedOrders = OrderService::whereBetween('created_at', [$start, $end])
            ->where('status_order', 'selesai')
            ->count();

        // C. Total Revenue (Gross) → from service orders with status_payment = lunas
        $totalRevenue = OrderService::whereBetween('created_at', [$start, $end])
            ->where('status_payment', 'lunas')
            ->sum('grand_total');

        // D. Pending Orders → orders with status_order not in (selesai, dibatalkan)
        $pendingOrders = OrderService::whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->count();

        // E. Average Completion Time
        $completedOrdersData = OrderService::whereBetween('created_at', [$start, $end])
            ->where('status_order', 'selesai')
            ->whereNotNull('estimated_completion')
            ->get();

        $totalCompletionTime = 0;
        $completedCount = 0;
        foreach ($completedOrdersData as $order) {
            $created = Carbon::parse($order->created_at);
            $completed = Carbon::parse($order->estimated_completion);
            $diffInHours = $created->diffInHours($completed);
            $totalCompletionTime += $diffInHours;
            $completedCount++;
        }
        $averageCompletionTime = $completedCount > 0 ? $totalCompletionTime / $completedCount : 0;

        // F. Late / Expired Orders → count of orders with isExpired = true
        $expiredOrders = OrderService::whereBetween('created_at', [$start, $end])
            ->where('is_expired', true)
            ->count();

        return [
            'total_orders' => $totalOrders,
            'total_revenue_all' => $totalRevenueAll,
            'completed_orders' => $completedOrders,
            'total_revenue' => $totalRevenue,
            'pending_orders' => $pendingOrders,
            'average_completion_time' => $averageCompletionTime,
            'expired_orders' => $expiredOrders
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

        // Filter by status_order
        if ($request->has('status_order') && !empty($request->status_order)) {
            $query->where('status_order', $request->status_order);
        }

        // Filter by status_payment
        if ($request->has('status_payment') && !empty($request->status_payment)) {
            $query->where('status_payment', $request->status_payment);
        }

        // Filter by payment method
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->whereHas('paymentDetails', function ($paymentQuery) use ($request) {
                $paymentQuery->where('method', $request->payment_method);
            });
        }

        // Filter by expired status
        if ($request->has('expired_status') && !empty($request->expired_status)) {
            switch ($request->expired_status) {
                case 'expired':
                    $query->where('is_expired', true);
                    break;
                case 'upcoming':
                    $query->where('is_expired', false)
                        ->whereNotNull('expired_date')
                        ->where('expired_date', '<=', Carbon::now()->addDays(7));
                    break;
                case 'active':
                    $query->where(function ($q) {
                        $q->where('is_expired', false)
                            ->where(function ($subQ) {
                                $subQ->whereNull('expired_date')
                                    ->orWhere('expired_date', '>', Carbon::now()->addDays(7));
                            });
                    });
                    break;
            }
        }

        // Filter by technician
        if ($request->has('technician') && !empty($request->technician)) {
            $query->whereHas('tickets', function ($ticketQuery) use ($request) {
                $ticketQuery->where('admin_id', $request->technician);
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Validate sort_by to prevent SQL injection
        $allowedSortFields = ['created_at', 'grand_total', 'order_service_id', 'customer_name', 'status_order', 'status_payment', 'expired_date'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }

        // Handle special sort fields
        if ($sortBy === 'customer_name') {
            $query->leftJoin('customers', 'order_services.customer_id', '=', 'customers.customer_id')
                ->orderBy('customers.name', $sortDirection);
        } elseif ($sortBy === 'expired_date') {
            $query->orderBy('expired_date', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Get orders with pagination
        $orders = $query->paginate(15)->appends($request->query());

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
        // Orders Over Time (daily/weekly/monthly by payment status)
        $ordersOverTime = OrderService::selectRaw('DATE(created_at) as date, status_payment, COUNT(*) as count, SUM(grand_total) as total')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('date', 'status_payment')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Discount Usage Trend
        $discountTrend = OrderService::selectRaw('DATE(created_at) as date, SUM(discount_amount) as total_discount, COUNT(*) as orders_with_discount')
            ->whereBetween('created_at', [$start, $end])
            ->where('discount_amount', '>', 0)
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Technician Workload (orders per technician)
        $technicianWorkload = DB::table('service_tickets')
            ->join('order_services', 'service_tickets.order_service_id', '=', 'order_services.order_service_id')
            ->join('admins', 'service_tickets.admin_id', '=', 'admins.id')
            ->selectRaw('admins.name as technician_name, COUNT(*) as total_orders')
            ->whereBetween('order_services.created_at', [$start, $end])
            ->whereNotIn('order_services.status_payment', ['dibatalkan'])
            ->groupBy('admins.id', 'admins.name')
            ->orderBy('total_orders', 'desc')
            ->get();

        // Service Type Distribution (onsite vs. ticket service)
        $serviceTypeDistribution = OrderService::selectRaw('COALESCE(type, "ticket") as service_type, COUNT(*) as count')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('type')
            ->get();

        // Top Requested Services
        $topServices = Service::selectRaw('service.name, SUM(order_service_items.quantity) as total_ordered')
            ->join('order_service_items', 'service.service_id', '=', 'order_service_items.item_id')
            ->join('order_services', 'order_service_items.order_service_id', '=', 'order_services.order_service_id')
            ->where('order_service_items.item_type', 'App\\Models\\Service')
            ->whereBetween('order_services.created_at', [$start, $end])
            ->whereNotIn('order_services.status_payment', ['dibatalkan'])
            ->groupBy('service.service_id', 'service.name')
            ->orderBy('total_ordered', 'desc')
            ->limit(5)
            ->get();

        // Payment Analytics: Payment Status
        $paymentStatusDistribution = OrderService::selectRaw('status_payment, COUNT(*) as count, SUM(grand_total) as total_amount')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->groupBy('status_payment')
            ->get();

        // Payment Analytics: Payment Method
        $paymentMethods = PaymentDetail::selectRaw('method, COUNT(*) as count, SUM(amount) as total_amount')
            ->whereHas('orderService', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->where('status', 'dibayar')
            ->groupBy('method')
            ->get();

        // Expiration Analytics: Expired Orders
        $expiredOrders = OrderService::whereBetween('created_at', [$start, $end])
            ->where('is_expired', true)
            ->count();

        // Expiration Analytics: Upcoming Expiration Orders
        $upcomingExpiration = OrderService::whereBetween('created_at', [$start, $end])
            ->where('is_expired', false)
            ->whereNotNull('expired_date')
            ->where('expired_date', '<=', Carbon::now()->addDays(7))
            ->count();

        // Reguler Orders by Status (type != 'onsite')
        $regulerOrdersByStatus = OrderService::selectRaw('status_order, COUNT(*) as count')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->where('type', '!=', 'onsite')
            ->groupBy('status_order')
            ->get();

        // Onsite Orders by Status (type = 'onsite')
        $onsiteOrdersByStatus = OrderService::selectRaw('status_order, COUNT(*) as count')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status_payment', ['dibatalkan'])
            ->where('type', 'onsite')
            ->groupBy('status_order')
            ->get();

        return [
            'orders_over_time' => $ordersOverTime,
            'discount_trend' => $discountTrend,
            'technician_workload' => $technicianWorkload,
            'service_type_distribution' => $serviceTypeDistribution,
            'top_services' => $topServices,
            'payment_status_distribution' => $paymentStatusDistribution,
            'payment_methods' => $paymentMethods,
            'reguler_orders_by_status' => $regulerOrdersByStatus,
            'onsite_orders_by_status' => $onsiteOrdersByStatus
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
