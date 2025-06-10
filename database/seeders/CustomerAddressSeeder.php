<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;

class CustomerAddressSeeder extends Seeder
{
    public function run(): void
    {

        CustomerAddress::insert([
            [
                'customer_id' => 'CST080625001',
                'province_id' => 1001,
                'province_name' => 'Jawa Timur',
                'city_id' => 2001,
                'city_name' => 'Surabaya',
                'district_id' => 3001,
                'district_name' => 'Gubeng',
                'postal_code' => '60286',
                'detail_address' => 'Jl. Airlangga No. 45, RT 02/RW 03',
                'is_default' => true,
            ],
            [
                'customer_id' => 'CST080625002',
                'province_id' => 1001,
                'province_name' => 'Jawa Timur',
                'city_id' => 2001,
                'city_name' => 'Surabaya',
                'district_id' => 3002,
                'district_name' => 'Genteng',
                'postal_code' => '60275',
                'detail_address' => 'Jl. Genteng Besar No. 28, RT 03/RW 05',
                'is_default' => true,
            ],
            [
                'customer_id' => 'CST080625003',
                'province_id' => 1001,
                'province_name' => 'Jawa Timur',
                'city_id' => 2002,
                'city_name' => 'Malang',
                'district_id' => 3003,
                'district_name' => 'Klojen',
                'postal_code' => '65111',
                'detail_address' => 'Jl. Ijen No. 55, RT 04/RW 02',
                'is_default' => true,
            ],
            [
                'customer_id' => 'CST080625004',
                'province_id' => 1001,
                'province_name' => 'Jawa Timur',
                'city_id' => 2002,
                'city_name' => 'Malang',
                'district_id' => 3004,
                'district_name' => 'Lowokwaru',
                'postal_code' => '65141',
                'detail_address' => 'Jl. Soekarno Hatta No. 15, RT 01/RW 04',
                'is_default' => true,
            ],
            [
                'customer_id' => 'CST080625005',
                'province_id' => 1001,
                'province_name' => 'Jawa Timur',
                'city_id' => 2003,
                'city_name' => 'Sidoarjo',
                'district_id' => 3005,
                'district_name' => 'Waru',
                'postal_code' => '61256',
                'detail_address' => 'Jl. Brigjen Katamso No. 88, RT 05/RW 02',
                'is_default' => true,
            ],
        ]);
    }
}
