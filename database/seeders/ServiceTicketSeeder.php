<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class ServiceTicketSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_tickets')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get order services that should have tickets (not 'Menunggu')
        $orderServices = DB::table('order_services')
            ->where('status_order', '!=', 'Menunggu')
            ->select('order_service_id', 'status_order', 'type', 'created_at')
            ->get();

        // Get available technicians (admin with role 'teknisi' or 'admin')
        $technicians = DB::table('admins')
            ->whereIn('role', ['teknisi', 'admin'])
            ->pluck('id')
            ->toArray();

        if (empty($technicians)) {
            $this->command->error('No technicians found. Please run AdminSeeder first.');
            return;
        }

        $serviceTickets = [];
        $ticketCounter = 1;

        foreach ($orderServices as $order) {
            $orderCreatedAt = Carbon::parse($order->created_at);
            $ticketId = 'TKT' . str_pad($ticketCounter, 6, '0', STR_PAD_LEFT);

            // Assign random technician
            $adminId = $faker->randomElement($technicians);

            // Schedule date (usually 1-3 days after order creation)
            $scheduleDate = $orderCreatedAt->copy()->addDays($faker->numberBetween(1, 3));

            // Visit schedule (for onsite services, include time)
            $visitSchedule = null;
            if ($order->type === 'onsite') {
                $visitTime = $faker->time('H:i:s', '17:00:00'); // Business hours
                $visitSchedule = $scheduleDate->copy()->setTimeFromTimeString($visitTime);
            }

            // Estimation days based on service complexity
            $estimationDays = $faker->numberBetween(1, 7);
            $estimateDate = $scheduleDate->copy()->addDays($estimationDays);

            // 🔁 Step-by-Step Flow: Ticket status based on order status
            $ticketStatus = 'Menunggu';
            switch ($order->status_order) {
                case 'Diproses':
                    // Step 3-4: Repair process ongoing
                    $ticketStatus = $faker->randomElement([
                        'Menunggu',        // Step 2: In queue
                        'Diproses',        // Step 3: Technician working
                        'Perlu Diambil',   // Step 4: Ready for pickup
                        'Diantar'          // Step 4: Ready for delivery
                    ]);
                    break;
                case 'Selesai':
                    // Step 5: Delivery/Pickup confirmed
                    $ticketStatus = 'Selesai';
                    break;
                case 'Dibatalkan':
                    $ticketStatus = 'Dibatalkan';
                    break;
            }

            // Ticket creation time (same as order or slightly after)
            $ticketCreatedAt = $orderCreatedAt->copy()->addMinutes($faker->numberBetween(5, 60));

            $serviceTickets[] = [
                'service_ticket_id' => $ticketId,
                'order_service_id' => $order->order_service_id,
                'admin_id' => $adminId,
                'status' => $ticketStatus,
                'schedule_date' => $scheduleDate->format('Y-m-d'),
                'estimation_days' => $estimationDays,
                'estimate_date' => $estimateDate->format('Y-m-d'),
                'visit_schedule' => $visitSchedule ? $visitSchedule->format('Y-m-d H:i:s') : null,
                'created_at' => $ticketCreatedAt,
                'updated_at' => $ticketCreatedAt,
                'deleted_at' => null,
            ];

            $ticketCounter++;
        }

        // Insert all service tickets
        foreach (array_chunk($serviceTickets, 20) as $chunk) {
            DB::table('service_tickets')->insert($chunk);
        }

        $this->command->info('ServiceTicketSeeder completed: ' . count($serviceTickets) . ' tickets created');
    }
}
