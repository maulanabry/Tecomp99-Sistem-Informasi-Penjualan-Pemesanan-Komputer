<?php

namespace App\Console\Commands;

use App\Models\OrderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Command untuk mengecek dan memperbarui status order service yang sudah expired
 * Command ini akan dijalankan secara otomatis melalui scheduler Laravel
 */
class CheckExpiredOrders extends Command
{
    /**
     * Nama dan signature command
     */
    protected $signature = 'orders:check-expired {--dry-run : Jalankan tanpa melakukan perubahan aktual}';

    /**
     * Deskripsi command
     */
    protected $description = 'Mengecek dan memperbarui status order service yang sudah melewati tanggal expired';

    /**
     * Execute command
     */
    public function handle()
    {
        $this->info('Memulai pengecekan order service yang expired...');

        // Query untuk mendapatkan order yang perlu dicek
        $expiredOrders = OrderService::whereNotNull('expired_date')
            ->where('expired_date', '<', now())
            ->whereIn('status_payment', [
                OrderService::STATUS_PAYMENT_BELUM_DIBAYAR,
                OrderService::STATUS_PAYMENT_CICILAN
            ])
            ->where('is_expired', false)
            ->with(['customer', 'tickets'])
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Tidak ada order service yang perlu diperbarui.');
            Log::info('CheckExpiredOrders: Tidak ada order yang expired');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$expiredOrders->count()} order service yang expired.");
        $this->newLine();

        // Tampilkan tabel preview
        $this->table(
            ['ID Order', 'Customer', 'Expired Date', 'Status Payment', 'Status Order'],
            $expiredOrders->map(function ($order) {
                return [
                    $order->order_service_id,
                    $order->customer->name ?? 'N/A',
                    $order->expired_date->format('d/m/Y H:i'),
                    $order->status_payment,
                    $order->status_order,
                ];
            })
        );

        // Konfirmasi jika bukan dry-run
        if (!$this->option('dry-run')) {
            if (!$this->confirm('Apakah Anda ingin melanjutkan update status order ini?', true)) {
                $this->info('Proses dibatalkan oleh user.');
                return self::SUCCESS;
            }
        }

        // Progress bar
        $progressBar = $this->output->createProgressBar($expiredOrders->count());
        $progressBar->start();

        $updatedCount = 0;
        $errorCount = 0;

        foreach ($expiredOrders as $order) {
            try {
                if ($this->option('dry-run')) {
                    // Dry run - hanya log tanpa update
                    $this->line(" [DRY RUN] Order {$order->order_service_id} akan diperbarui");
                    Log::info("CheckExpiredOrders DRY RUN: Order {$order->order_service_id} akan diperbarui", [
                        'customer' => $order->customer->name ?? 'N/A',
                        'expired_date' => $order->expired_date->toDateTimeString(),
                    ]);
                } else {
                    // Update sebenarnya
                    $updated = $order->checkExpiredStatus();
                    if ($updated) {
                        $updatedCount++;
                        $this->line(" Order {$order->order_service_id} berhasil diperbarui");
                    }
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Error updating order {$order->order_service_id}: {$e->getMessage()}");
                Log::error("CheckExpiredOrders error for order {$order->order_service_id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        if ($this->option('dry-run')) {
            $this->info("DRY RUN selesai. {$expiredOrders->count()} order akan diperbarui.");
        } else {
            $this->info("Proses selesai. {$updatedCount} order berhasil diperbarui, {$errorCount} error.");
            Log::info("CheckExpiredOrders completed", [
                'total_found' => $expiredOrders->count(),
                'updated' => $updatedCount,
                'errors' => $errorCount,
            ]);
        }

        return self::SUCCESS;
    }
}
