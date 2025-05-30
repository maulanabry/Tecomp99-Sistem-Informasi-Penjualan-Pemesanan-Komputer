<?php

namespace Tests\Unit;

use App\Services\BiteshipService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Mockery;

class BiteshipServiceTest extends TestCase
{
    protected $biteshipService;
    protected $mockClient;

    protected function setUp(): void
    {
        // Mock Guzzle client
        $this->mockClient = Mockery::mock(Client::class);

        // Instansiasi BiteshipService dengan mocked client
        $this->biteshipService = new BiteshipService();
        $this->biteshipService->setClient($this->mockClient); // pastikan Anda memiliki setter untuk client
    }

    // Uji untuk mendapatkan provinsi
    public function testGetProvincesSuccess()
    {
        // Data palsu untuk provinsi
        $fakeResponse = [
            'data' => [
                ['id' => 1, 'name' => 'Jakarta'],
                ['id' => 2, 'name' => 'Bali'],
            ]
        ];

        // Setup mock client untuk merespons dengan data palsu
        $this->mockClient
            ->shouldReceive('request')
            ->once()
            ->with(
                'GET', // Method yang digunakan
                'https://api.biteship.com/v1/provinces', // Endpoint lengkap
                \Mockery::on(function ($args) {
                    return $args['headers']['Authorization'] === 'Bearer ' . env('BITESHIP_API_KEY') &&
                        $args['headers']['Content-Type'] === 'application/json';
                })
            )
            ->andReturn(new Response(200, [], json_encode($fakeResponse)));

        // Panggil metode untuk mendapatkan provinsi
        $response = $this->biteshipService->getProvinces();

        // Verifikasi bahwa response sesuai dengan data palsu
        $this->assertEquals($fakeResponse, $response);
    }


    // Uji untuk mendapatkan kota berdasarkan provinsi
    public function testGetCitiesByProvinceSuccess()
    {
        // Data palsu untuk kota berdasarkan provinsi
        $fakeCitiesResponse = [
            'data' => [
                ['id' => 1, 'name' => 'Kota Jakarta Selatan'],
                ['id' => 2, 'name' => 'Kota Jakarta Utara'],
            ]
        ];

        // Setup mock client untuk merespons dengan data palsu
        $this->mockClient
            ->shouldReceive('request')
            ->once()
            ->with(
                'GET', // Method yang digunakan
                'https://api.biteship.com/v1/cities?province_id=1', // Endpoint lengkap dengan query string
                \Mockery::on(function ($args) {
                    return $args['headers']['Authorization'] === 'Bearer ' . env('BITESHIP_API_KEY') &&
                        $args['headers']['Content-Type'] === 'application/json';
                })
            )
            ->andReturn(new Response(200, [], json_encode($fakeCitiesResponse)));

        // Panggil metode untuk mendapatkan kota berdasarkan provinsi
        $response = $this->biteshipService->getCitiesByProvince(1);

        // Verifikasi bahwa response sesuai dengan data palsu
        $this->assertEquals($fakeCitiesResponse, $response);
    }


    // Uji untuk mengirimkan pengiriman
    public function testCreateShipmentSuccess()
    {
        // Data palsu untuk pengiriman
        $shipmentData = [
            'recipient' => 'John Doe',
            'address' => 'Jl. Raya No. 123',
            'city' => 'Jakarta',
            'postal_code' => '12345',
        ];

        // Respons palsu untuk pengiriman berhasil
        $fakeShipmentResponse = [
            'data' => [
                'id' => 123,
                'status' => 'created',
            ]
        ];

        // Setup mock client untuk merespons dengan data palsu
        $this->mockClient
            ->shouldReceive('request')
            ->once()
            ->with(
                'POST', // Method yang digunakan
                'https://api.biteship.com/v1/shipments', // Endpoint lengkap
                \Mockery::on(function ($args) use ($shipmentData) {
                    return $args['headers']['Authorization'] === 'Bearer ' . env('BITESHIP_API_KEY') &&
                        $args['headers']['Content-Type'] === 'application/json' &&
                        $args['json'] === $shipmentData;
                })
            )
            ->andReturn(new Response(200, [], json_encode($fakeShipmentResponse)));

        // Panggil metode untuk membuat pengiriman
        $response = $this->biteshipService->createShipment($shipmentData);

        // Verifikasi bahwa response sesuai dengan data palsu
        $this->assertEquals($fakeShipmentResponse, $response);
    }

    // Menangani tear down
    protected function tearDown(): void
    {
        Mockery::close();
    }
}
