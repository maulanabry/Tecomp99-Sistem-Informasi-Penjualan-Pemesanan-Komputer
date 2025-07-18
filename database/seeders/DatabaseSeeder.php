<?php

namespace Database\Seeders;

use App\Models\CustomerAddress;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            ProductImagesSeeder::class,
            VoucherSeeder::class,
            CustomerSeeder::class,
            CustomerAddressSeeder::class,
            OrderProductSeeder::class,
            ShippingSeeder::class,
            PaymentSeeder::class,

            // Order Service related seeders
            OrderServiceSeeder::class,
            OrderServiceItemSeeder::class,
            ServiceTicketSeeder::class,
            ServiceTicketActionSeeder::class,
            PaymentDetailSeeder::class,
        ]);
    }
}
