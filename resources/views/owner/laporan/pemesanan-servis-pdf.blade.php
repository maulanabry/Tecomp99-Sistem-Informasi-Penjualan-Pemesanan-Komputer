<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemesanan Servis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2563eb;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .summary-cards {
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            margin-bottom: 10px;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background-color: #f8f9fa;
            width: 23%;
        }
        .summary-card h3 {
            margin: 0 0 3px 0;
            font-size: 11px;
            color: #333;
            font-weight: bold;
        }
        .summary-card p {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .table-section {
            margin-top: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
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
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-lunas {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-down-payment {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-belum-dibayar {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-diproses {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-menunggu {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .status-dibatalkan {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .service-type-onsite {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .service-type-reguler {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            
            .summary-grid {
                margin-bottom: 15px;
            }
            
            .table-section {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Pemesanan Servis</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <!-- First Row -->
        <table class="summary-table">
            <tr>
                <td class="summary-card">
                    <h3>Total Order Servis</h3>
                    <p>{{ number_format($serviceSummary['total_orders']) }}</p>
                </td>
                <td class="summary-card">
                    <h3>Order Belum Dibayar</h3>
                    <p>{{ number_format($serviceSummary['total_services_ordered']) }}</p>
                </td>
                <td class="summary-card">
                    <h3>Total Pendapatan</h3>
                    <p>Rp {{ number_format($serviceSummary['total_revenue'], 0, ',', '.') }}</p>
                </td>
                <td class="summary-card">
                    <h3>Total Diskon</h3>
                    <p>Rp {{ number_format($serviceSummary['total_discounts'], 0, ',', '.') }}</p>
                </td>
            </tr>
        </table>
        <!-- Second Row -->
        <table class="summary-table">
            <tr>
                <td class="summary-card">
                    <h3>Order dengan Teknisi</h3>
                    <p>{{ number_format($serviceSummary['orders_with_technicians']) }}</p>
                </td>
                <td class="summary-card">
                    <h3>Order Selesai</h3>
                    <p>{{ number_format($serviceSummary['completed_orders']) }}</p>
                </td>
                <td class="summary-card">
                    <h3>Total Down Payment</h3>
                    <p>Rp {{ number_format($serviceSummary['down_payment_amount'], 0, ',', '.') }}</p>
                </td>
                <td class="summary-card">
                    <h3>Rata-rata per Order</h3>
                    <p>Rp {{ number_format($serviceSummary['average_per_order'], 0, ',', '.') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Detailed Table -->
    <div class="table-section">
        <div class="section-title">Detail Pemesanan Servis (Total: {{ is_countable($serviceData) ? count($serviceData) : $serviceData->count() }} order)</div>
        
        @if((is_countable($serviceData) ? count($serviceData) : $serviceData->count()) > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID Pemesanan</th>
                        <th>Nama Customer</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Jenis Layanan</th>
                        <th>Nama Teknisi</th>
                        <th>Status Order</th>
                        <th>Status Pembayaran</th>
                        <th>Total Bayar</th>
                        <th>Diskon</th>
                        <th>Metode Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceData as $order)
                        <tr>
                            <td>{{ $order->order_service_id }}</td>
                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge {{ $order->type === 'onsite' ? 'service-type-onsite' : 'service-type-reguler' }}">
                                    {{ ucfirst($order->type ?? 'Reguler') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $ticket = $order->tickets->first();
                                    $technicianName = $ticket && $ticket->admin 
                                        ? $ticket->admin->name
                                        : 'Belum Ditugaskan';
                                @endphp
                                {{ $technicianName }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ $order->status_order }}">
                                    {{ ucfirst($order->status_order) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ str_replace('_', '-', $order->status_payment) }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status_payment)) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                            <td>{{ $order->paymentDetails->first()->method ?? 'Belum Ada Pembayaran' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                Tidak ada data pemesanan servis ditemukan untuk periode ini.
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem TeComp99</p>
        <p>© {{ date('Y') }} TeComp99. Semua hak dilindungi.</p>
    </div>
</body>
</html>
