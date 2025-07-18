<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{

    //Shipping
    public $shipper_postal_code = 60116; // Default to Manyar, Surabaya (id)

    // Cari alamat tujuan
    public function searchDestination(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key'),
        ])->withOptions([
            'verify' => false, // Disable SSL verification for local development
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $request->input('search'),
            'limit' => $request->input('limit', 10),
            'offset' => $request->input('offset', 0),
        ]);

        // Add success indicator to response
        $responseData = $response['data'] ?? [];

        return response()->json([
            'success' => $response->successful() && !empty($responseData),
            'data' => $responseData
        ]);
    }

    // Ongkos Kirim
    public function checkOngkir(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key'),
        ])->withOptions([
            'verify' => false, // Disable SSL verification for local development
        ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin' => $request->input('origin', $this->shipper_postal_code),
            'destination' => $request->input('destination'),
            'weight' => $request->input('weight', 1000), // Default weight 1000 grams
            'courier' => $request->input('courier', 'jne'), // Default courier JNE
            'service' => $request->input('service', 'reg'), // Default service regular
            'search' => $request->input('search'),
            'limit' => $request->input('limit', 10),
            'offset' => $request->input('offset', 0),
        ]);

        // Add success indicator to response
        $responseData = $response['data'] ?? [];

        return response()->json([
            'success' => $response->successful() && !empty($responseData),
            'data' => $responseData
        ]);
    }
}
