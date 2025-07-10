<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .summary-card {
            width: 18%;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            margin-bottom: 10px;
        }
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #333;
        }
        .summary-card p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
        }
        .table-container {
            margin-top: 20px;
        }
        .table-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-lunas { background-color: #d1fae5; color: #065f46; }
        .status-down-payment { background-color: #fef3c7; color: #92400e; }
        .status-belum-dibayar { background-color: #fee2e2; color: #991b1b; }
        .status-selesai { background-color: #d1fae5; color: #065f46; }
        .status-diproses { background-color: #dbeafe; color: #1e40af; }
        .status-menunggu { background-color: #f3f4f6; color: #374151; }
        .status-dikirim { background-color: #e9d5ff; color: #7c2d12; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PENJUALAN PRODUK</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Order Produk</h3>
            <p>{{ number_format($salesSummary['total_orders']) }}</p>
        </div>
        <div class="summary-card">
            <h3>Total Produk Terjual</h3>
            <p>{{ number_format($salesSummary['total_products_sold']) }}</p>
        </div>
        <div class="summary-card">
            <h3>Total Pendapatan</h3>
            <p>Rp {{ number_format($salesSummary['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="summary-card">
            <h3>Total Diskon</h3>
            <p>Rp {{ number_format($salesSummary['total_discounts'], 0, ',', '.') }}</p>
        </div>
        <div class="summary-card">
            <h3>Total Ongkir</h3>
            <p>Rp {{ number_format($salesSummary['total_shipping'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Detailed Sales Table -->
    <div class="table-container">
        <div class="table-title">Detail Penjualan</div>
        <table>
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Jumlah Item</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pembayaran</th>
                    <th>Status Order</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesData as $order)
                    <tr>
                        <td>{{ $order->order_product_id }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                        <td>{{ $order->items_count ?? 0 }}</td>
                        <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        <td>{{ $order->payments->first()->method ?? 'Belum Ada' }}</td>
                        <td>
                            <span class="status-badge status-{{ str_replace('_', '-', $order->status_payment) }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $order->status_order }}">
                                {{ ucfirst($order->status_order) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data penjualan ditemukan untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem TeComp99</p>
        <p>© {{ date('Y') }} TeComp99. All rights reserved.</p>
    </div>
</body>
</html>
