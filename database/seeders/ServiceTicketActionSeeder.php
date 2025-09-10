<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class ServiceTicketActionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_actions')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all service tickets with order type
        $serviceTickets = DB::table('service_tickets')
            ->join('order_services', 'service_tickets.order_service_id', '=', 'order_services.order_service_id')
            ->select('service_tickets.service_ticket_id', 'service_tickets.status', 'service_tickets.created_at', 'service_tickets.schedule_date', 'order_services.type as order_type')
            ->get();

        // Action templates based on ticket status - updated to match all ENUM values
        $actionTemplates = [
            'menunggu' => 'Tiket masih menunggu konfirmasi',
            'dijadwalkan' => 'Tiket telah dijadwalkan untuk servis',
            'menuju_lokasi' => 'Teknisi dalam perjalanan ke lokasi pelanggan',
            'diproses' => 'Perbaikan perangkat sedang dilakukan teknisi',
            'menunggu_sparepart' => 'Menunggu ketersediaan sparepart yang diperlukan',
            'siap_diambil' => 'Perangkat telah selesai diperbaiki dan siap diambil',
            'diantar' => 'Perangkat sedang dalam proses pengiriman ke pelanggan',
            'selesai' => 'Layanan servis selesai, perangkat telah diterima pelanggan',
            'dibatalkan' => 'Tiket layanan telah dibatalkan atas permintaan pelanggan',
            'melewati_jatuh_tempo' => 'Tiket layanan kedaluwarsa karena tidak ada aktivitas pembayaran'
        ];

        $serviceActions = [];
        $actionCounter = 1;

        foreach ($serviceTickets as $ticket) {
            $ticketCreatedAt = Carbon::parse($ticket->created_at);
            $scheduleDate = Carbon::parse($ticket->schedule_date);
            $currentStatus = $ticket->status;
            $orderType = $ticket->order_type;

            // Generate historical actions sequence based on order type and current status
            $actionSequence = $this->generateActionSequence($currentStatus, $orderType);

            $actionNumber = 1;
            foreach ($actionSequence as $status) {
                $actionId = 'ACT' . str_pad($actionCounter, 6, '0', STR_PAD_LEFT);
                $actionText = $actionTemplates[$status] ?? 'Status tidak dikenali';

                // Calculate action timestamp (progressive)
                $actionTime = $ticketCreatedAt->copy()->addMinutes($actionNumber * $faker->numberBetween(10, 60));

                $serviceActions[] = [
                    'service_action_id' => $actionId,
                    'service_ticket_id' => $ticket->service_ticket_id,
                    'number' => $actionNumber,
                    'action' => $actionText,
                    'created_at' => $actionTime,
                ];

                $actionCounter++;
                $actionNumber++;
            }
        }

        // Insert all service actions
        foreach (array_chunk($serviceActions, 50) as $chunk) {
            DB::table('service_actions')->insert($chunk);
        }

        $this->command->info('ServiceTicketActionSeeder completed: ' . count($serviceActions) . ' actions created for ' . $serviceTickets->count() . ' tickets');
    }

    /**
     * Generate action sequence based on current status and order type
     */
    private function generateActionSequence($currentStatus, $orderType)
    {
        // Define sequences for each possible current status
        $sequences = [];

        if ($orderType === 'onsite') {
            $sequences = [
                'menunggu' => ['menunggu'],
                'dijadwalkan' => ['menunggu', 'dijadwalkan'],
                'menuju_lokasi' => ['menunggu', 'dijadwalkan', 'menuju_lokasi'],
                'diproses' => ['menunggu', 'dijadwalkan', 'menuju_lokasi', 'diproses'],
                'menunggu_sparepart' => ['menunggu', 'dijadwalkan', 'menuju_lokasi', 'diproses', 'menunggu_sparepart'],
                'siap_diambil' => ['menunggu', 'dijadwalkan', 'menuju_lokasi', 'diproses', 'menunggu_sparepart', 'siap_diambil'],
                'diantar' => ['menunggu', 'dijadwalkan', 'menuju_lokasi', 'diproses', 'menunggu_sparepart', 'siap_diambil', 'diantar'],
                'selesai' => ['menunggu', 'dijadwalkan', 'menuju_lokasi', 'diproses', 'menunggu_sparepart', 'siap_diambil', 'diantar', 'selesai'],
                'dibatalkan' => ['menunggu', 'dijadwalkan', 'menuju_lokasi', 'diproses', 'dibatalkan'],
                'melewati_jatuh_tempo' => ['menunggu', 'dijadwalkan', 'menuju_lokasi', 'diproses', 'melewati_jatuh_tempo']
            ];
        } else {
            // In-store
            $sequences = [
                'menunggu' => ['menunggu'],
                'dijadwalkan' => ['menunggu', 'dijadwalkan'],
                'diproses' => ['menunggu', 'dijadwalkan', 'diproses'],
                'menunggu_sparepart' => ['menunggu', 'dijadwalkan', 'diproses', 'menunggu_sparepart'],
                'siap_diambil' => ['menunggu', 'dijadwalkan', 'diproses', 'menunggu_sparepart', 'siap_diambil'],
                'selesai' => ['menunggu', 'dijadwalkan', 'diproses', 'menunggu_sparepart', 'siap_diambil', 'selesai'],
                'dibatalkan' => ['menunggu', 'dijadwalkan', 'diproses', 'dibatalkan'],
                'melewati_jatuh_tempo' => ['menunggu', 'dijadwalkan', 'diproses', 'melewati_jatuh_tempo']
            ];
        }

        return $sequences[$currentStatus] ?? [$currentStatus];
    }
}
