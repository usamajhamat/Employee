<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class TanazaApiService
{
    protected $apiKey;
    protected $deviceId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = '11F975B0CFD3F548776B8C9C738605D1'; // Ideally, use env('TANAZA_API_KEY')
        $this->deviceId = 'A9E3F295D529FABA248B0790DAA30D9F'; // Ideally, use env('TANAZA_DEVICE_ID')
        $this->baseUrl = 'https://cloud.tanaza.com/apis/v3.0';
    }

    public function getDeviceStatus(): array
    {
        $url = "{$this->baseUrl}/{$this->apiKey}/{$this->deviceId}/status";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch data from Tanaza API',
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}