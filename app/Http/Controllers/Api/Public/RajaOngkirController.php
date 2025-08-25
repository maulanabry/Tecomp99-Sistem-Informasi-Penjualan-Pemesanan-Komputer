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
        try {
            $response = Http::withHeaders([
                'key' => config('rajaongkir.api_key'),
            ])->withOptions([
                'verify' => false, // Disable SSL verification for local development
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $request->input('search'),
                'limit' => $request->input('limit', 10),
                'offset' => $request->input('offset', 0),
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data destinasi dari API',
                    'data' => []
                ], 500);
            }

            $responseData = $response->json();
            $destinations = $responseData['data'] ?? [];

            // Return direct array for backward compatibility with existing JavaScript
            return response()->json($destinations);
        } catch (\Exception $e) {
            \Log::error('RajaOngkir searchDestination error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari destinasi',
                'data' => []
            ], 500);
        }
    }

    // Ongkos Kirim
    public function checkOngkir(Request $request)
    {
        try {
            // Validate required parameters
            $request->validate([
                'destination' => 'required',
                'weight' => 'required|numeric|min:1',
            ]);

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

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data ongkos kirim dari API',
                    'data' => []
                ], 500);
            }

            $responseData = $response->json();
            $shippingCosts = $responseData['data'] ?? [];

            // Log for debugging
            \Log::info('RajaOngkir checkOngkir response', [
                'destination' => $request->input('destination'),
                'weight' => $request->input('weight'),
                'response_count' => count($shippingCosts)
            ]);

            // Return direct array for backward compatibility with existing JavaScript
            return response()->json($shippingCosts);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter tidak valid: ' . implode(', ', $e->validator->errors()->all()),
                'data' => []
            ], 422);
        } catch (\Exception $e) {
            \Log::error('RajaOngkir checkOngkir error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung ongkos kirim',
                'data' => []
            ], 500);
        }
    }
}
