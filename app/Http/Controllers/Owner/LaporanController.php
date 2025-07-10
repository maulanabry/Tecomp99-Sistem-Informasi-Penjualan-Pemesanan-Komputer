<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\PaymentDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function penjualanProduk(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Parse dates
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

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
        // Sales per day
        $salesPerDay = OrderProduct::selectRaw('DATE(created_at) as date, SUM(grand_total) as total, COUNT(*) as orders')
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

        return [
            'sales_per_day' => $salesPerDay,
            'payment_methods' => $paymentMethods,
            'top_products' => $topProducts
        ];
    }

    public function exportPdf(Request $request)
    {
        // Get the same data as the main view
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $salesSummary = $this->getSalesSummary($start, $end);
        $salesData = $this->getSalesData($start, $end, $request);

        // For now, return the PDF view directly (can be printed as PDF by browser)
        return view('owner.laporan.penjualan-produk-pdf', compact(
            'salesSummary',
            'salesData',
            'startDate',
            'endDate'
        ));
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
            ->get();

        $filename = 'laporan-penjualan-produk-' . $startDate . '-to-' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($salesData) {
            $file = fopen('php://output', 'w');

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
                    'Rp ' . number_format($order->grand_total, 0, ',', '.'),
                    $paymentMethod,
                    ucfirst(str_replace('_', ' ', $order->status_payment)),
                    ucfirst($order->status_order)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
