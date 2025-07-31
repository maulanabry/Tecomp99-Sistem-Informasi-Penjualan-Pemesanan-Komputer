<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Test Unit untuk Autentikasi Admin
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin melakukan login sehingga dapat mengakses sistem sesuai hak akses saya
 * - Sebagai admin, saya ingin melakukan logout sehingga dapat mengamankan akun saya ketika selesai menggunakan sistem
 */
class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private $adminData;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Membuat data admin untuk testing
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Data admin untuk testing
        $this->adminData = [
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'theme' => 'light'
        ];
    }

    /**
     * Test login admin dengan kredensial yang valid
     * 
     * Skenario:
     * 1. Buat admin baru di database
     * 2. Coba login dengan email dan password yang benar
     * 3. Verifikasi bahwa login berhasil
     * 4. Verifikasi bahwa admin ter-autentikasi dengan guard 'admin'
     */
    public function test_admin_dapat_login_dengan_kredensial_valid()
    {
        // Arrange: Buat admin di database
        $admin = Admin::create($this->adminData);

        // Act: Coba login dengan kredensial yang benar
        $loginResult = Auth::guard('admin')->attempt([
            'email' => 'admin@test.com',
            'password' => 'password123',
            'role' => 'admin'
        ]);

        // Assert: Verifikasi login berhasil
        $this->assertTrue($loginResult, 'Login admin harus berhasil dengan kredensial yang valid');

        // Verifikasi admin ter-autentikasi
        $this->assertTrue(Auth::guard('admin')->check(), 'Admin harus ter-autentikasi setelah login');

        // Verifikasi data admin yang login
        $authenticatedAdmin = Auth::guard('admin')->user();
        $this->assertEquals($admin->id, $authenticatedAdmin->id, 'ID admin yang login harus sesuai');
        $this->assertEquals('admin@test.com', $authenticatedAdmin->email, 'Email admin yang login harus sesuai');
    }

    /**
     * Test login admin dengan email yang salah
     * 
     * Skenario:
     * 1. Buat admin baru di database
     * 2. Coba login dengan email yang salah
     * 3. Verifikasi bahwa login gagal
     */
    public function test_admin_tidak_dapat_login_dengan_email_salah()
    {
        // Arrange: Buat admin di database
        Admin::create($this->adminData);

        // Act: Coba login dengan email yang salah
        $loginResult = Auth::guard('admin')->attempt([
            'email' => 'salah@test.com',
            'password' => 'password123',
            'role' => 'admin'
        ]);

        // Assert: Verifikasi login gagal
        $this->assertFalse($loginResult, 'Login harus gagal dengan email yang salah');
        $this->assertFalse(Auth::guard('admin')->check(), 'Admin tidak boleh ter-autentikasi dengan email salah');
    }

    /**
     * Test login admin dengan password yang salah
     * 
     * Skenario:
     * 1. Buat admin baru di database
     * 2. Coba login dengan password yang salah
     * 3. Verifikasi bahwa login gagal
     */
    public function test_admin_tidak_dapat_login_dengan_password_salah()
    {
        // Arrange: Buat admin di database
        Admin::create($this->adminData);

        // Act: Coba login dengan password yang salah
        $loginResult = Auth::guard('admin')->attempt([
            'email' => 'admin@test.com',
            'password' => 'passwordsalah',
            'role' => 'admin'
        ]);

        // Assert: Verifikasi login gagal
        $this->assertFalse($loginResult, 'Login harus gagal dengan password yang salah');
        $this->assertFalse(Auth::guard('admin')->check(), 'Admin tidak boleh ter-autentikasi dengan password salah');
    }

    /**
     * Test login admin dengan role yang salah
     * 
     * Skenario:
     * 1. Buat admin dengan role 'admin'
     * 2. Coba login dengan role 'teknisi'
     * 3. Verifikasi bahwa login gagal
     */
    public function test_admin_tidak_dapat_login_dengan_role_salah()
    {
        // Arrange: Buat admin dengan role 'admin'
        Admin::create($this->adminData);

        // Act: Coba login dengan role yang salah
        $loginResult = Auth::guard('admin')->attempt([
            'email' => 'admin@test.com',
            'password' => 'password123',
            'role' => 'teknisi'
        ]);

        // Assert: Verifikasi login gagal
        $this->assertFalse($loginResult, 'Login harus gagal dengan role yang salah');
        $this->assertFalse(Auth::guard('admin')->check(), 'Admin tidak boleh ter-autentikasi dengan role salah');
    }

    /**
     * Test logout admin
     * 
     * Skenario:
     * 1. Login admin terlebih dahulu
     * 2. Verifikasi admin ter-autentikasi
     * 3. Logout admin
     * 4. Verifikasi admin tidak lagi ter-autentikasi
     */
    public function test_admin_dapat_logout()
    {
        // Arrange: Buat dan login admin
        $admin = Admin::create($this->adminData);
        Auth::guard('admin')->login($admin);

        // Verifikasi admin ter-autentikasi sebelum logout
        $this->assertTrue(Auth::guard('admin')->check(), 'Admin harus ter-autentikasi sebelum logout');

        // Act: Logout admin
        Auth::guard('admin')->logout();

        // Assert: Verifikasi admin tidak lagi ter-autentikasi
        $this->assertFalse(Auth::guard('admin')->check(), 'Admin tidak boleh ter-autentikasi setelah logout');
        $this->assertNull(Auth::guard('admin')->user(), 'User admin harus null setelah logout');
    }

    /**
     * Test update last_seen_at saat login
     * 
     * Skenario:
     * 1. Buat admin dengan last_seen_at null
     * 2. Login admin
     * 3. Verifikasi last_seen_at ter-update
     */
    public function test_last_seen_at_terupdate_saat_login()
    {
        // Arrange: Buat admin dengan last_seen_at null
        $admin = Admin::create(array_merge($this->adminData, ['last_seen_at' => null]));
        $this->assertNull($admin->last_seen_at, 'last_seen_at harus null sebelum login');

        // Act: Login admin
        Auth::guard('admin')->login($admin);

        // Simulasi update last_seen_at seperti di AuthController
        $admin->update(['last_seen_at' => now()]);

        // Assert: Verifikasi last_seen_at ter-update
        $admin->refresh();
        $this->assertNotNull($admin->last_seen_at, 'last_seen_at harus ter-update setelah login');
        $this->assertTrue($admin->last_seen_at->isToday(), 'last_seen_at harus hari ini');
    }

    /**
     * Test admin online status
     * 
     * Skenario:
     * 1. Buat admin dengan last_seen_at 3 menit yang lalu (online)
     * 2. Verifikasi admin dianggap online
     * 3. Update last_seen_at menjadi 10 menit yang lalu (offline)
     * 4. Verifikasi admin dianggap offline
     */
    public function test_admin_online_status()
    {
        // Arrange: Buat admin dengan last_seen_at 3 menit yang lalu (online)
        $admin = Admin::create(array_merge($this->adminData, [
            'last_seen_at' => now()->subMinutes(3)
        ]));

        // Assert: Admin harus dianggap online (aktif dalam 5 menit terakhir)
        $this->assertTrue($admin->isOnline(), 'Admin harus online jika aktif dalam 5 menit terakhir');

        // Act: Update last_seen_at menjadi 10 menit yang lalu
        $admin->update(['last_seen_at' => now()->subMinutes(10)]);

        // Assert: Admin harus dianggap offline
        $this->assertFalse($admin->isOnline(), 'Admin harus offline jika tidak aktif lebih dari 5 menit');
    }

    /**
     * Test login dengan remember me
     * 
     * Skenario:
     * 1. Buat admin di database
     * 2. Login dengan remember = true
     * 3. Verifikasi remember token ter-set
     */
    public function test_admin_login_dengan_remember_me()
    {
        // Arrange: Buat admin di database
        $admin = Admin::create($this->adminData);

        // Act: Login dengan remember = true
        $loginResult = Auth::guard('admin')->attempt([
            'email' => 'admin@test.com',
            'password' => 'password123',
            'role' => 'admin'
        ], true); // remember = true

        // Assert: Verifikasi login berhasil dan remember token ter-set
        $this->assertTrue($loginResult, 'Login dengan remember me harus berhasil');

        $admin->refresh();
        $this->assertNotNull($admin->remember_token, 'Remember token harus ter-set saat login dengan remember me');
    }

    /**
     * Test validasi role admin yang berbeda
     * 
     * Skenario:
     * 1. Buat admin dengan role 'teknisi'
     * 2. Coba login dengan guard 'teknisi'
     * 3. Verifikasi login berhasil
     * 4. Coba login teknisi dengan guard 'admin'
     * 5. Verifikasi login gagal
     */
    public function test_validasi_role_admin_berbeda()
    {
        // Arrange: Buat admin dengan role 'teknisi'
        $teknisi = Admin::create([
            'name' => 'Teknisi Test',
            'email' => 'teknisi@test.com',
            'password' => Hash::make('password123'),
            'role' => 'teknisi',
            'theme' => 'light'
        ]);

        // Act & Assert: Login teknisi dengan guard 'teknisi' harus berhasil
        $loginTeknisi = Auth::guard('teknisi')->attempt([
            'email' => 'teknisi@test.com',
            'password' => 'password123',
            'role' => 'teknisi'
        ]);
        $this->assertTrue($loginTeknisi, 'Teknisi harus bisa login dengan guard teknisi');

        // Logout teknisi
        Auth::guard('teknisi')->logout();

        // Act & Assert: Login teknisi dengan guard 'admin' harus gagal
        $loginAdmin = Auth::guard('admin')->attempt([
            'email' => 'teknisi@test.com',
            'password' => 'password123',
            'role' => 'admin'
        ]);
        $this->assertFalse($loginAdmin, 'Teknisi tidak boleh bisa login dengan guard admin');
    }

    /**
     * Cleanup setelah setiap test
     */
    protected function tearDown(): void
    {
        // Logout semua guard untuk memastikan state bersih
        Auth::guard('admin')->logout();
        Auth::guard('teknisi')->logout();
        Auth::guard('pemilik')->logout();

        parent::tearDown();
    }
}
