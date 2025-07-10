<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;

class CustomerAddressSeeder extends Seeder
{
    public function run(): void
    {
        // Addresses for customers with addresses and accounts (CST080625001-008)
        $addressesWithAccount = [
            // Jakarta - DKI Jakarta
            [
                'customer_id' => 'CST080625001',
                'province_id' => 3100,
                'province_name' => 'DKI Jakarta',
                'city_id' => 3171,
                'city_name' => 'Jakarta Selatan',
                'district_id' => 3171010,
                'district_name' => 'Kebayoran Baru',
                'subdistrict_id' => 317101001,
                'subdistrict_name' => 'Kramat Pela',
                'postal_code' => '12130',
                'detail_address' => 'Jl. Senopati Raya No. 45, RT 02/RW 03, Apartemen Senopati Suites Lt. 15',
                'is_default' => true,
            ],
            // Surabaya - Jawa Timur
            [
                'customer_id' => 'CST080625002',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578080,
                'district_name' => 'Gubeng',
                'subdistrict_id' => 357808001,
                'subdistrict_name' => 'Airlangga',
                'postal_code' => '60286',
                'detail_address' => 'Jl. Airlangga No. 28, RT 03/RW 05, Perumahan Airlangga Indah',
                'is_default' => true,
            ],
            // Bandung - Jawa Barat
            [
                'customer_id' => 'CST080625003',
                'province_id' => 3200,
                'province_name' => 'Jawa Barat',
                'city_id' => 3273,
                'city_name' => 'Bandung',
                'district_id' => 3273010,
                'district_name' => 'Coblong',
                'subdistrict_id' => 327301001,
                'subdistrict_name' => 'Dago',
                'postal_code' => '40135',
                'detail_address' => 'Jl. Ir. H. Djuanda No. 155, RT 01/RW 04, Komplek Villa Dago',
                'is_default' => true,
            ],
            // Yogyakarta - DI Yogyakarta
            [
                'customer_id' => 'CST080625004',
                'province_id' => 3400,
                'province_name' => 'DI Yogyakarta',
                'city_id' => 3471,
                'city_name' => 'Yogyakarta',
                'district_id' => 3471020,
                'district_name' => 'Gondokusuman',
                'subdistrict_id' => 347102001,
                'subdistrict_name' => 'Baciro',
                'postal_code' => '55225',
                'detail_address' => 'Jl. Kaliurang KM 5.2 No. 87, RT 02/RW 08, Perumahan Kaliurang Asri',
                'is_default' => true,
            ],
            // Medan - Sumatera Utara
            [
                'customer_id' => 'CST080625005',
                'province_id' => 1200,
                'province_name' => 'Sumatera Utara',
                'city_id' => 1275,
                'city_name' => 'Medan',
                'district_id' => 1275010,
                'district_name' => 'Medan Petisah',
                'subdistrict_id' => 127501001,
                'subdistrict_name' => 'Petisah Tengah',
                'postal_code' => '20112',
                'detail_address' => 'Jl. Gajah Mada No. 234, RT 05/RW 02, Ruko Gajah Mada Plaza',
                'is_default' => true,
            ],
            // Denpasar - Bali
            [
                'customer_id' => 'CST080625006',
                'province_id' => 5100,
                'province_name' => 'Bali',
                'city_id' => 5171,
                'city_name' => 'Denpasar',
                'district_id' => 5171010,
                'district_name' => 'Denpasar Selatan',
                'subdistrict_id' => 517101001,
                'subdistrict_name' => 'Sanur',
                'postal_code' => '80228',
                'detail_address' => 'Jl. Danau Tamblingan No. 76, RT 01/RW 03, Villa Sanur Indah',
                'is_default' => true,
            ],
            // Semarang - Jawa Tengah
            [
                'customer_id' => 'CST080625007',
                'province_id' => 3300,
                'province_name' => 'Jawa Tengah',
                'city_id' => 3374,
                'city_name' => 'Semarang',
                'district_id' => 3374070,
                'district_name' => 'Banyumanik',
                'subdistrict_id' => 337407001,
                'subdistrict_name' => 'Banyumanik',
                'postal_code' => '50269',
                'detail_address' => 'Jl. Ngesrep Timur V No. 45, RT 04/RW 06, Perumahan Bukit Ngesrep',
                'is_default' => true,
            ],
            // Makassar - Sulawesi Selatan
            [
                'customer_id' => 'CST080625008',
                'province_id' => 7300,
                'province_name' => 'Sulawesi Selatan',
                'city_id' => 7371,
                'city_name' => 'Makassar',
                'district_id' => 7371010,
                'district_name' => 'Mariso',
                'subdistrict_id' => 737101001,
                'subdistrict_name' => 'Mariso',
                'postal_code' => '90125',
                'detail_address' => 'Jl. Pengayoman No. 123, RT 02/RW 05, Komplek Pengayoman Indah',
                'is_default' => true,
            ],
        ];

        // Addresses for customers with addresses but no accounts (CST080625009-017)
        $addressesNoAccount = [
            // Jakarta - Same apartment building as customer 001
            [
                'customer_id' => 'CST080625009',
                'province_id' => 3100,
                'province_name' => 'DKI Jakarta',
                'city_id' => 3171,
                'city_name' => 'Jakarta Selatan',
                'district_id' => 3171010,
                'district_name' => 'Kebayoran Baru',
                'subdistrict_id' => 317101001,
                'subdistrict_name' => 'Kramat Pela',
                'postal_code' => '12130',
                'detail_address' => 'Jl. Senopati Raya No. 45, RT 02/RW 03, Apartemen Senopati Suites Lt. 8',
                'is_default' => true,
            ],
            // Surabaya - Different area
            [
                'customer_id' => 'CST080625010',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578090,
                'district_name' => 'Genteng',
                'subdistrict_id' => 357809001,
                'subdistrict_name' => 'Genteng',
                'postal_code' => '60275',
                'detail_address' => 'Jl. Genteng Besar No. 67, RT 03/RW 07, Toko Elektronik Genteng',
                'is_default' => true,
            ],
            // Malang - Jawa Timur
            [
                'customer_id' => 'CST080625011',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3573,
                'city_name' => 'Malang',
                'district_id' => 3573010,
                'district_name' => 'Klojen',
                'subdistrict_id' => 357301001,
                'subdistrict_name' => 'Kasin',
                'postal_code' => '65117',
                'detail_address' => 'Jl. Ijen No. 89, RT 02/RW 04, Perumahan Ijen Nirwana',
                'is_default' => true,
            ],
            // Palembang - Sumatera Selatan
            [
                'customer_id' => 'CST080625012',
                'province_id' => 1600,
                'province_name' => 'Sumatera Selatan',
                'city_id' => 1671,
                'city_name' => 'Palembang',
                'district_id' => 1671010,
                'district_name' => 'Ilir Barat I',
                'subdistrict_id' => 167101001,
                'subdistrict_name' => '24 Ilir',
                'postal_code' => '30114',
                'detail_address' => 'Jl. Sudirman No. 456, RT 01/RW 02, Komplek Sudirman Square',
                'is_default' => true,
            ],
            // Balikpapan - Kalimantan Timur
            [
                'customer_id' => 'CST080625013',
                'province_id' => 6400,
                'province_name' => 'Kalimantan Timur',
                'city_id' => 6472,
                'city_name' => 'Balikpapan',
                'district_id' => 6472010,
                'district_name' => 'Balikpapan Selatan',
                'subdistrict_id' => 647201001,
                'subdistrict_name' => 'Sepinggan',
                'postal_code' => '76115',
                'detail_address' => 'Jl. Sepinggan Raya No. 78, RT 04/RW 03, Perumahan Sepinggan Asri',
                'is_default' => true,
            ],
            // Pekanbaru - Riau
            [
                'customer_id' => 'CST080625014',
                'province_id' => 1400,
                'province_name' => 'Riau',
                'city_id' => 1471,
                'city_name' => 'Pekanbaru',
                'district_id' => 1471010,
                'district_name' => 'Sukajadi',
                'subdistrict_id' => 147101001,
                'subdistrict_name' => 'Kampung Melayu',
                'postal_code' => '28122',
                'detail_address' => 'Jl. HR. Soebrantas KM 12.5 No. 234, RT 02/RW 05, Perumahan Soebrantas',
                'is_default' => true,
            ],
            // Bandung - Same complex as customer 003
            [
                'customer_id' => 'CST080625015',
                'province_id' => 3200,
                'province_name' => 'Jawa Barat',
                'city_id' => 3273,
                'city_name' => 'Bandung',
                'district_id' => 3273010,
                'district_name' => 'Coblong',
                'subdistrict_id' => 327301002,
                'subdistrict_name' => 'Dago',
                'postal_code' => '40135',
                'detail_address' => 'Jl. Ir. H. Djuanda No. 155, RT 01/RW 04, Komplek Villa Dago Blok B-12',
                'is_default' => true,
            ],
            // Solo - Jawa Tengah
            [
                'customer_id' => 'CST080625016',
                'province_id' => 3300,
                'province_name' => 'Jawa Tengah',
                'city_id' => 3372,
                'city_name' => 'Surakarta',
                'district_id' => 3372010,
                'district_name' => 'Laweyan',
                'subdistrict_id' => 337201001,
                'subdistrict_name' => 'Laweyan',
                'postal_code' => '57146',
                'detail_address' => 'Jl. Dr. Radjiman No. 567, RT 03/RW 06, Perumahan Laweyan Indah',
                'is_default' => true,
            ],
            // Ubud - Bali
            [
                'customer_id' => 'CST080625017',
                'province_id' => 5100,
                'province_name' => 'Bali',
                'city_id' => 5108,
                'city_name' => 'Gianyar',
                'district_id' => 5108080,
                'district_name' => 'Ubud',
                'subdistrict_id' => 510808001,
                'subdistrict_name' => 'Ubud',
                'postal_code' => '80571',
                'detail_address' => 'Jl. Monkey Forest Road No. 89, RT 01/RW 02, Villa Ubud Asri',
                'is_default' => true,
            ],
        ];

        // Additional Surabaya addresses for more customers
        $additionalSurabayaAddresses = [
            // Surabaya - Wonokromo
            [
                'customer_id' => 'CST080625018',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578310,
                'district_name' => 'Wonokromo',
                'subdistrict_id' => 357831001,
                'subdistrict_name' => 'Wonokromo',
                'postal_code' => '60243',
                'detail_address' => 'Jl. Raya Wonokromo No. 123, RT 01/RW 02, Perumahan Wonokromo Indah',
                'is_default' => true,
            ],
            // Surabaya - Sukolilo
            [
                'customer_id' => 'CST080625019',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578290,
                'district_name' => 'Sukolilo',
                'subdistrict_id' => 357829001,
                'subdistrict_name' => 'Keputih',
                'postal_code' => '60111',
                'detail_address' => 'Jl. Keputih Tegal Timur No. 45, RT 04/RW 03, Apartemen Keputih Residence',
                'is_default' => true,
            ],
            // Surabaya - Rungkut
            [
                'customer_id' => 'CST080625020',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578250,
                'district_name' => 'Rungkut',
                'subdistrict_id' => 357825001,
                'subdistrict_name' => 'Rungkut Kidul',
                'postal_code' => '60293',
                'detail_address' => 'Jl. Rungkut Industri Raya No. 78, RT 02/RW 05, Komplek Rungkut Square',
                'is_default' => true,
            ],
            // Surabaya - Mulyorejo
            [
                'customer_id' => 'CST080625021',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578200,
                'district_name' => 'Mulyorejo',
                'subdistrict_id' => 357820001,
                'subdistrict_name' => 'Mulyorejo',
                'postal_code' => '60115',
                'detail_address' => 'Jl. Mulyorejo Utara No. 234, RT 03/RW 07, Perumahan Mulyorejo Asri',
                'is_default' => true,
            ],
            // Surabaya - Tenggilis Mejoyo
            [
                'customer_id' => 'CST080625022',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578300,
                'district_name' => 'Tenggilis Mejoyo',
                'subdistrict_id' => 357830001,
                'subdistrict_name' => 'Tenggilis Mejoyo',
                'postal_code' => '60292',
                'detail_address' => 'Jl. Tenggilis Utara No. 156, RT 05/RW 04, Villa Tenggilis Garden',
                'is_default' => true,
            ],
            // Surabaya - Wiyung
            [
                'customer_id' => 'CST080625023',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578320,
                'district_name' => 'Wiyung',
                'subdistrict_id' => 357832001,
                'subdistrict_name' => 'Wiyung',
                'postal_code' => '60229',
                'detail_address' => 'Jl. Wiyung Sejahtera No. 89, RT 01/RW 06, Komplek Wiyung Indah',
                'is_default' => true,
            ],
            // Surabaya - Lakarsantri
            [
                'customer_id' => 'CST080625024',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578150,
                'district_name' => 'Lakarsantri',
                'subdistrict_id' => 357815001,
                'subdistrict_name' => 'Lakarsantri',
                'postal_code' => '60213',
                'detail_address' => 'Jl. Lakarsantri Selatan No. 67, RT 02/RW 03, Perumahan Lakarsantri Residence',
                'is_default' => true,
            ],
            // Surabaya - Tandes
            [
                'customer_id' => 'CST080625025',
                'province_id' => 3500,
                'province_name' => 'Jawa Timur',
                'city_id' => 3578,
                'city_name' => 'Surabaya',
                'district_id' => 3578280,
                'district_name' => 'Tandes',
                'subdistrict_id' => 357828001,
                'subdistrict_name' => 'Tandes',
                'postal_code' => '60186',
                'detail_address' => 'Jl. Tandes Kidul No. 145, RT 04/RW 08, Komplek Tandes Plaza',
                'is_default' => true,
            ],
        ];

        // Combine all addresses for batch insert
        $allAddresses = array_merge(
            $addressesWithAccount,
            $addressesNoAccount,
            $additionalSurabayaAddresses
        );

        // Add timestamps to all addresses
        $now = now();
        foreach ($allAddresses as &$address) {
            $address['created_at'] = $now;
            $address['updated_at'] = $now;
        }

        // Batch insert all addresses
        CustomerAddress::insert($allAddresses);

        // Update hasAddress flag for customers who now have addresses
        $customerIdsWithAddresses = array_column($allAddresses, 'customer_id');

        Customer::whereIn('customer_id', $customerIdsWithAddresses)
            ->update(['hasAddress' => true]);
    }
}
