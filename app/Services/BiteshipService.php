<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BiteshipService
{
    protected $client;
    protected $apiKey;
    protected $baseUri;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('BITESHIP_API_KEY');
        $this->baseUri = env('BITESHIP_API_URL');
    }

    // Generic API call method
    public function callApi($endpoint, $method = 'GET', $data = [])
    {
        try {
            $response = $this->client->request($method, $this->baseUri . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    // Fetch provinces
    public function getProvinces()
    {
        return $this->callApi('locations/provinces', 'GET');
    }

    // Fetch cities by province ID
    public function getCitiesByProvince($provinceId)
    {
        return $this->callApi("cities?province=$provinceId", 'GET');
    }
}
