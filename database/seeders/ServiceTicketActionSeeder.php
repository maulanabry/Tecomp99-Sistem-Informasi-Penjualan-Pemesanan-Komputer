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

        // Get all service tickets
        $serviceTickets = DB::table('service_tickets')
            ->select('service_ticket_id', 'status', 'created_at', 'schedule_date')
            ->get();

        // Action templates based on ticket progress
        $actionTemplates = [
            'initial' => [
                'Tiket service dibuat dan menunggu jadwal kunjungan',
                'Pemeriksaan awal perangkat dan identifikasi masalah',
                'Analisis kerusakan dan estimasi waktu perbaikan',
                'Konfirmasi dengan customer mengenai kondisi perangkat'
            ],
            'progress' => [
                'Mulai proses perbaikan perangkat',
                'Penggantian komponen yang rusak',
                'Pembersihan dan maintenance perangkat',
                'Testing fungsi perangkat setelah perbaikan',
                'Instalasi software dan driver yang diperlukan',
                'Optimasi performa sistem',
                'Backup data customer sebelum perbaikan',
                'Kalibrasi dan fine-tuning perangkat'
            ],
            'completion' => [
                'Perbaikan selesai, perangkat berfungsi normal',
                'Quality check dan testing final',
                'Perangkat siap untuk diambil customer',
                'Dokumentasi perbaikan dan garansi',
                'Serah terima perangkat kepada customer',
                'Service completed successfully'
            ],
            'cancelled' => [
                'Service dibatalkan atas permintaan customer',
                'Perangkat dikembalikan tanpa perbaikan',
                'Pembatalan service karena biaya perbaikan tinggi',
                'Customer memutuskan untuk tidak melanjutkan service'
            ]
        ];

        $serviceActions = [];
        $actionCounter = 1;

        foreach ($serviceTickets as $ticket) {
            $ticketCreatedAt = Carbon::parse($ticket->created_at);
            $scheduleDate = Carbon::parse($ticket->schedule_date);

            // Determine number of actions based on ticket status
            $actionCount = 1;
            switch ($ticket->status) {
                case 'Menunggu':
                    $actionCount = 1;
                    break;
                case 'Menuju Lokasi':
                case 'Diantar':
                    $actionCount = $faker->numberBetween(2, 3);
                    break;
                case 'Diproses':
                    $actionCount = $faker->numberBetween(3, 4);
                    break;
                case 'Selesai':
                    $actionCount = $faker->numberBetween(4, 5);
                    break;
                case 'Dibatalkan':
                    $actionCount = $faker->numberBetween(1, 2);
                    break;
                default:
                    $actionCount = $faker->numberBetween(2, 3);
            }

            // Generate actions for this ticket
            for ($i = 1; $i <= $actionCount; $i++) {
                $actionId = 'ACT' . str_pad($actionCounter, 6, '0', STR_PAD_LEFT);

                // Determine action content based on sequence and ticket status
                $actionText = '';
                if ($i === 1) {
                    // First action - always initial
                    $actionText = $faker->randomElement($actionTemplates['initial']);
                } elseif ($i === $actionCount && $ticket->status === 'Selesai') {
                    // Last action for completed tickets
                    $actionText = $faker->randomElement($actionTemplates['completion']);
                } elseif ($i === $actionCount && $ticket->status === 'Dibatalkan') {
                    // Last action for cancelled tickets
                    $actionText = $faker->randomElement($actionTemplates['cancelled']);
                } else {
                    // Progress actions
                    $actionText = $faker->randomElement($actionTemplates['progress']);
                }

                // Calculate action timestamp
                $actionTime = $ticketCreatedAt->copy();
                if ($i === 1) {
                    // First action happens when ticket is created
                    $actionTime = $ticketCreatedAt->copy()->addMinutes($faker->numberBetween(5, 30));
                } else {
                    // Subsequent actions happen over time
                    $hoursToAdd = ($i - 1) * $faker->numberBetween(2, 24);
                    $actionTime = $ticketCreatedAt->copy()->addHours($hoursToAdd);
                }

                $serviceActions[] = [
                    'service_action_id' => $actionId,
                    'service_ticket_id' => $ticket->service_ticket_id,
                    'number' => $i,
                    'action' => $actionText,
                    'created_at' => $actionTime,
                ];

                $actionCounter++;
            }
        }

        // Insert all service actions
        foreach (array_chunk($serviceActions, 50) as $chunk) {
            DB::table('service_actions')->insert($chunk);
        }

        $this->command->info('ServiceTicketActionSeeder completed: ' . count($serviceActions) . ' actions created for ' . $serviceTickets->count() . ' tickets');
    }
}
