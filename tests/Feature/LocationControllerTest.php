<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\Admin;

class LocationControllerTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure an admin user exists for authentication if required.
        $this->admin = Admin::first() ?? Admin::factory()->create();
    }

    public function testProvincesReturnsData()
    {
        // Fake Binderbyte API response for provinces
        Http::fake([
            'https://api.binderbyte.com/wilayah/provinsi*' => Http::response([
                ['id' => 1, 'province' => 'Province A'],
                ['id' => 2, 'province' => 'Province B'],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get('/locations/provinces');

        $response->assertStatus(200);
        $response->assertJsonFragment(['Province A']);
        $response->assertJsonFragment(['Province B']);
    }

    public function testCitiesReturnsData()
    {
        // Fake Binderbyte API response for cities in a given province (e.g., province ID 1)
        Http::fake([
            'https://api.binderbyte.com/wilayah/kabupaten*' => Http::response([
                ['id' => 10, 'city' => 'City X'],
                ['id' => 11, 'city' => 'City Y'],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get('/locations/cities/1');

        $response->assertStatus(200);
        $response->assertJsonFragment(['City X']);
        $response->assertJsonFragment(['City Y']);
    }

    public function testCitiesRequiresProvinceParameter()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get('/locations/cities/');

        // Accept 400 or 404 depending on route handling
        $this->assertTrue(in_array($response->getStatusCode(), [400, 404]));

        if ($response->getStatusCode() === 400) {
            $response->assertJson(['error' => 'Province ID is required']);
        } else {
            $response->assertStatus(404);
        }
    }
}
