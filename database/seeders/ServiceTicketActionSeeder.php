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

        // Action templates based on ticket status - exact descriptions as provided
        $actionTemplates = [
            'Menunggu' => 'Tiket masih menunggu konfirmasi',
            'Dijadwalkan' => 'Tiket telah dijadwalkan',
            'Menuju_lokasi' => 'Teknisi dalam perjalanan ke lokasi',
            'Diproses' => 'Perbaikan sedang dilakukan',
            'Menunggu_sparepart' => 'Menunggu ketersediaan sparepart',
            'Siap_diambil' => 'Perangkat siap diambil pelanggan',
            'Diantar' => 'Perangkat sedang diantar ke pelanggan',
            'Selesai' => 'Layanan selesai, perangkat diterima pelanggan',
            'Dibatalkan' => 'Tiket layanan dibatalkan',
            'Expired' => 'Tiket layanan kedaluwarsa karena tidak ada pembayaran'
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
                'Menunggu' => ['Menunggu'],
                'Dijadwalkan' => ['Menunggu', 'Dijadwalkan'],
                'Menuju_lokasi' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi'],
                'Diproses' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi', 'Diproses'],
                'Menunggu_sparepart' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi', 'Diproses', 'Menunggu_sparepart'],
                'Siap_diambil' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi', 'Diproses', 'Menunggu_sparepart', 'Siap_diambil'],
                'Diantar' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi', 'Diproses', 'Menunggu_sparepart', 'Siap_diambil', 'Diantar'],
                'Selesai' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi', 'Diproses', 'Menunggu_sparepart', 'Siap_diambil', 'Diantar', 'Selesai'],
                'Dibatalkan' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi', 'Diproses', 'Dibatalkan'],
                'Expired' => ['Menunggu', 'Dijadwalkan', 'Menuju_lokasi', 'Diproses', 'Expired']
            ];
        } else {
            // In-store
            $sequences = [
                'Menunggu' => ['Menunggu'],
                'Dijadwalkan' => ['Menunggu', 'Dijadwalkan'],
                'Diproses' => ['Menunggu', 'Dijadwalkan', 'Diproses'],
                'Menunggu_sparepart' => ['Menunggu', 'Dijadwalkan', 'Diproses', 'Menunggu_sparepart'],
                'Siap_diambil' => ['Menunggu', 'Dijadwalkan', 'Diproses', 'Menunggu_sparepart', 'Siap_diambil'],
                'Selesai' => ['Menunggu', 'Dijadwalkan', 'Diproses', 'Menunggu_sparepart', 'Siap_diambil', 'Selesai'],
                'Dibatalkan' => ['Menunggu', 'Dijadwalkan', 'Diproses', 'Dibatalkan'],
                'Expired' => ['Menunggu', 'Dijadwalkan', 'Diproses', 'Expired']
            ];
        }

        return $sequences[$currentStatus] ?? [$currentStatus];
    }
}
