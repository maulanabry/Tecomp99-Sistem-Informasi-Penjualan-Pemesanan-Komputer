<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Admin;

class KomerceAPIControllerTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Create or retrieve an admin user for authentication
        $this->admin = Admin::first() ?? Admin::factory()->create();
    }

    public function testProvincesReturnsData()
    {
        // Mock the external API response
        Http::fake([
            'https://api.komerceapi.com/v1/provinces' => Http::response([
                ['id' => 1, 'name' => 'Province A'],
                ['id' => 2, 'name' => 'Province B'],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/komerce/provinces');

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'name' => 'Province A'],
            ['id' => 2, 'name' => 'Province B'],
        ]);
    }

    public function testCitiesReturnsData()
    {
        // Mock the external API response
        Http::fake([
            'https://api.komerceapi.com/v1/cities?province_id=1' => Http::response([
                ['id' => 10, 'name' => 'City X'],
                ['id' => 11, 'name' => 'City Y'],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/komerce/cities?province=1');

        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 10, 'name' => 'City X'],
            ['id' => 11, 'name' => 'City Y'],
        ]);
    }

    public function testCitiesRequiresProvinceParameter()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/komerce/cities');

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Province ID is required',
        ]);
    }
}
