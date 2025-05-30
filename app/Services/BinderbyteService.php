<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BinderbyteService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.binderbyte.key');
        $this->apiUrl = config('services.binderbyte.url');
    }

    /**
     * Fetch provinces from Binderbyte API.
     *
     * @return array
     */
    public function getProvinces()
    {
        try {
            $url = "{$this->apiUrl}/wilayah/provinsi?api_key={$this->apiKey}";
            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                Log::error('Binderbyte getProvinces Error', ['response' => $response->body()]);
                return ['error' => true, 'message' => 'Failed to fetch provinces from Binderbyte API'];
            }

            $data = $response->json();

            if (isset($data['code']) && $data['code'] != 200) {
                return [
                    'error' => true,
                    'message' => $data['messages'] ?? 'Unknown error from Binderbyte API'
                ];
            }

            return ['error' => false, 'data' => $data];
        } catch (\Exception $e) {
            Log::error('Exception in BinderbyteService@getProvinces', ['exception' => $e->getMessage()]);
            return ['error' => true, 'message' => 'Exception occurred while fetching provinces'];
        }
    }

    /**
     * Fetch cities (kabupaten) for a given province ID from Binderbyte API.
     *
     * @param  int|string $provinceId
     * @return array
     */
    public function getCitiesByProvince($provinceId)
    {
        try {
            $url = "{$this->apiUrl}/wilayah/kabupaten?api_key={$this->apiKey}&id_provinsi={$provinceId}";
            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                Log::error('Binderbyte getCitiesByProvince Error', ['response' => $response->body()]);
                return ['error' => true, 'message' => 'Failed to fetch cities from Binderbyte API'];
            }

            $data = $response->json();

            if (isset($data['code']) && $data['code'] != 200) {
                return [
                    'error' => true,
                    'message' => $data['messages'] ?? 'Unknown error from Binderbyte API'
                ];
            }

            return ['error' => false, 'data' => $data];
        } catch (\Exception $e) {
            Log::error('Exception in BinderbyteService@getCitiesByProvince', ['exception' => $e->getMessage()]);
            return ['error' => true, 'message' => 'Exception occurred while fetching cities'];
        }
    }
}
