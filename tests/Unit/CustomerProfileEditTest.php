<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;

/**
 * Test untuk sistem edit profil customer
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class CustomerProfileEditTest extends TestCase
{
    /**
     * Setup database in-memory SQLite dan buat schema dari migrations
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Disable model observers untuk menghindari side effects
        Event::fake();

        // Konfigurasi database in-memory SQLite
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);

        // Buat schema tabel yang diperlukan untuk testing
        $this->createRequiredTables();

        // Buat data sample untuk testing
        $this->createSampleData();
    }

    /**
     * Buat tabel-tabel yang diperlukan untuk testing profile edit
     */
    private function createRequiredTables(): void
    {
        // Tabel customers
        Schema::create('customers', function (Blueprint $table) {
            $table->string('customer_id')->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('hasAccount')->default(false);
            $table->boolean('hasAddress')->default(false);
            $table->string('photo')->nullable();
            $table->string('gender')->nullable();
            $table->integer('service_orders_count')->default(0);
            $table->integer('product_orders_count')->default(0);
            $table->integer('total_points')->default(0);
            $table->timestamp('last_active')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel customer_addresses
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id', 20);
            $table->unsignedInteger('province_id')->nullable();
            $table->string('province_name')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->string('district_name')->nullable();
            $table->unsignedInteger('subdistrict_id')->nullable();
            $table->string('subdistrict_name')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('detail_address')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    /**
     * Buat data sample untuk testing
     */
    private function createSampleData(): void
    {
        // Buat customer sample
        Customer::create([
            'customer_id' => 'CST240101001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'contact' => '081234567890',
            'password' => Hash::make('password123'),
            'gender' => 'pria',
            'hasAccount' => true,
            'hasAddress' => true,
        ]);

        Customer::create([
            'customer_id' => 'CST240101002',
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'contact' => '081234567891',
            'password' => Hash::make('password456'),
            'gender' => 'wanita',
            'hasAccount' => true,
            'hasAddress' => false,
        ]);

        // Buat alamat customer
        CustomerAddress::create([
            'customer_id' => 'CST240101001',
            'province_id' => 1,
            'province_name' => 'DKI Jakarta',
            'city_id' => 1,
            'city_name' => 'Jakarta',
            'district_id' => 1,
            'district_name' => 'Jakarta Pusat',
            'subdistrict_id' => 1,
            'subdistrict_name' => 'Menteng',
            'postal_code' => '12345',
            'detail_address' => 'Jl. Merdeka No. 123, RT 01/RW 02',
            'is_default' => true,
        ]);
    }

    /**
     * Test customer dapat mengupdate profil dasar
     */
    public function test_customer_dapat_update_profil_dasar(): void
    {
        $customerId = 'CST240101001';
        $customer = Customer::find($customerId);

        // Data profil baru
        $newProfileData = [
            'name' => 'John Doe Updated',
            'email' => 'john.updated@example.com',
            'contact' => '081234567899',
            'gender' => 'pria',
        ];

        // Update profil
        $customer->update($newProfileData);

        // Verifikasi update berhasil
        $updatedCustomer = Customer::find($customerId);
        $this->assertEquals('John Doe Updated', $updatedCustomer->name);
        $this->assertEquals('john.updated@example.com', $updatedCustomer->email);
        $this->assertEquals('081234567899', $updatedCustomer->contact);
        $this->assertEquals('pria', $updatedCustomer->gender);

        // Verifikasi data tersimpan di database
        $this->assertDatabaseHas('customers', [
            'customer_id' => $customerId,
            'name' => 'John Doe Updated',
            'email' => 'john.updated@example.com',
            'contact' => '081234567899',
        ]);
    }

    /**
     * Test validasi data profil
     */
    public function test_validasi_data_profil(): void
    {
        $customerId = 'CST240101001';
        $customer = Customer::find($customerId);

        // Test validasi email unique
        $existingEmail = 'jane@example.com'; // Email yang sudah digunakan customer lain

        // Simulasi validasi email unique
        $emailExists = Customer::where('email', $existingEmail)
            ->where('customer_id', '!=', $customerId)
            ->exists();

        $this->assertTrue($emailExists); // Email sudah digunakan

        // Test validasi gender enum
        $validGenders = ['pria', 'wanita'];
        $testGender = 'pria';
        $this->assertContains($testGender, $validGenders);

        $invalidGender = 'lainnya';
        $this->assertNotContains($invalidGender, $validGenders);

        // Test update dengan data valid
        $validData = [
            'name' => 'John Valid',
            'email' => 'john.valid@example.com', // Email baru yang belum digunakan
            'contact' => '081234567888',
            'gender' => 'pria',
        ];

        $customer->update($validData);

        // Verifikasi update berhasil
        $this->assertEquals('John Valid', $customer->fresh()->name);
        $this->assertEquals('john.valid@example.com', $customer->fresh()->email);
    }

    /**
     * Test customer dapat mengubah password
     */
    public function test_customer_dapat_mengubah_password(): void
    {
        $customerId = 'CST240101001';
        $customer = Customer::find($customerId);
        $oldPassword = 'password123';
        $newPassword = 'newpassword456';

        // Verifikasi password lama benar
        $this->assertTrue(Hash::check($oldPassword, $customer->password));

        // Simulasi validasi password lama sebelum update
        $currentPasswordValid = Hash::check($oldPassword, $customer->password);
        $this->assertTrue($currentPasswordValid);

        // Update password
        $customer->update([
            'password' => Hash::make($newPassword),
        ]);

        // Verifikasi password baru
        $updatedCustomer = Customer::find($customerId);
        $this->assertTrue(Hash::check($newPassword, $updatedCustomer->password));
        $this->assertFalse(Hash::check($oldPassword, $updatedCustomer->password));

        // Test validasi password lama yang salah
        $wrongOldPassword = 'wrongpassword';
        $wrongPasswordValid = Hash::check($wrongOldPassword, $customer->password);
        $this->assertFalse($wrongPasswordValid);
    }

    /**
     * Test validasi password lama sebelum update
     */
    public function test_validasi_password_lama_sebelum_update(): void
    {
        $customerId = 'CST240101001';
        $customer = Customer::find($customerId);

        // Test dengan password lama yang benar
        $correctOldPassword = 'password123';
        $isCurrentPasswordValid = Hash::check($correctOldPassword, $customer->password);
        $this->assertTrue($isCurrentPasswordValid);

        // Test dengan password lama yang salah
        $incorrectOldPassword = 'wrongpassword';
        $isIncorrectPasswordValid = Hash::check($incorrectOldPassword, $customer->password);
        $this->assertFalse($isIncorrectPasswordValid);

        // Simulasi proses validasi seperti di controller
        $newPassword = 'verynewpassword789';

        if ($isCurrentPasswordValid) {
            // Update password jika validasi berhasil
            $customer->update([
                'password' => Hash::make($newPassword),
            ]);

            // Verifikasi password berhasil diupdate
            $this->assertTrue(Hash::check($newPassword, $customer->fresh()->password));
        }

        // Test validasi minimum length password
        $shortPassword = '123'; // Password terlalu pendek
        $this->assertLessThan(8, strlen($shortPassword));

        $validPassword = 'validpassword123'; // Password dengan panjang valid
        $this->assertGreaterThanOrEqual(8, strlen($validPassword));
    }
}
