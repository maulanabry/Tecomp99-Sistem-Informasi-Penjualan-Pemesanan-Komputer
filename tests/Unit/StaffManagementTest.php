<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Unit untuk Manajemen Staff (Teknisi dan Admin)
 * 
 * Test ini mencakup user story Owner:
 * - Sebagai owner, saya ingin mengelola data teknisi dan admin
 */
class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test membuat admin baru
     * 
     * Menguji apakah admin berhasil ditambahkan ke database
     */
    public function test_owner_dapat_membuat_admin_baru()
    {
        // Arrange: Data admin baru
        $adminData = [
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'theme' => 'light'
        ];

        // Act: Buat admin baru
        $admin = Admin::create($adminData);

        // Assert: Verifikasi admin tersimpan
        $this->assertInstanceOf(Admin::class, $admin);
        $this->assertEquals('Admin Test', $admin->name);
        $this->assertEquals('admin@test.com', $admin->email);
        $this->assertEquals('admin', $admin->role);
    }

    /**
     * Test membuat teknisi baru
     * 
     * Menguji apakah teknisi berhasil ditambahkan ke database
     */
    public function test_owner_dapat_membuat_teknisi_baru()
    {
        // Arrange: Data teknisi baru
        $teknisiData = [
            'name' => 'Teknisi Test',
            'email' => 'teknisi@test.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'dark'
        ];

        // Act: Buat teknisi baru
        $teknisi = Admin::create($teknisiData);

        // Assert: Verifikasi teknisi tersimpan
        $this->assertInstanceOf(Admin::class, $teknisi);
        $this->assertEquals('Teknisi Test', $teknisi->name);
        $this->assertEquals('teknisi@test.com', $teknisi->email);
        $this->assertEquals('teknisi', $teknisi->role);
    }

    /**
     * Test mengupdate data admin
     * 
     * Menguji apakah data admin berhasil diupdate
     */
    public function test_owner_dapat_mengupdate_data_admin()
    {
        // Arrange: Buat admin
        $admin = Admin::create([
            'name' => 'Admin Lama',
            'email' => 'admin.lama@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'theme' => 'light'
        ]);

        // Act: Update data admin
        $admin->update([
            'name' => 'Admin Baru',
            'theme' => 'dark'
        ]);

        // Assert: Verifikasi perubahan tersimpan
        $this->assertEquals('Admin Baru', $admin->name);
        $this->assertEquals('dark', $admin->theme);
    }

    /**
     * Test menghapus admin (soft delete)
     * 
     * Menguji apakah admin berhasil dihapus (soft delete)
     */
    public function test_owner_dapat_menghapus_admin()
    {
        // Arrange: Buat admin
        $admin = Admin::create([
            'name' => 'Admin Hapus',
            'email' => 'admin.hapus@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'theme' => 'light'
        ]);

        $adminId = $admin->id;

        // Act: Hapus admin (soft delete)
        $admin->delete();

        // Assert: Verifikasi admin tidak muncul di query normal
        $this->assertNull(Admin::find($adminId));

        // Verifikasi admin masih ada dengan withTrashed()
        $trashedAdmin = Admin::withTrashed()->find($adminId);
        $this->assertNotNull($trashedAdmin);
        $this->assertNotNull($trashedAdmin->deleted_at);
    }

    /**
     * Test status online admin
     * 
     * Menguji logika penentuan status online admin
     */
    public function test_status_online_admin()
    {
        // Arrange: Buat admin dengan last_seen_at 2 menit yang lalu (online)
        $admin = Admin::create([
            'name' => 'Admin Online',
            'email' => 'admin.online@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'theme' => 'light',
            'last_seen_at' => now()->subMinutes(2)
        ]);

        // Assert: Admin harus online (aktif dalam 5 menit terakhir)
        $this->assertTrue($admin->isOnline());

        // Act: Update last_seen_at menjadi 10 menit yang lalu
        $admin->update(['last_seen_at' => now()->subMinutes(10)]);

        // Assert: Admin harus offline
        $this->assertFalse($admin->isOnline());
    }
}
