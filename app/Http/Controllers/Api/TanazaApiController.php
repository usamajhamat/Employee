<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class TanazaApiController extends Controller
{
    public function getTotalUsers()
    {
        // Tanaza API credentials (store securely in .env)
        $userId = env('TANAZA_AP_USER_ID', '11F975B0CFD3F548776B8C9C738605D1');
        $token = env('TANAZA_AP_TOKEN', 'A9E3F295D529FABA248B0790DAA30D9F');
        $url = "https://cloud.tanaza.com/apis/v3.0/{$userId}/{$token}/status";

        try {
            // Make the API request
            $response = Http::get($url);
            Log::info($response);
            
            // Check for HTTP errors
            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to fetch data from Tanaza API',
                    'status' => $response->status()
                ], $response->status());
            }

            $data = $response->json();

            // Check for API-level errors
            if ($data['errorCode'] !== 0) {
                return response()->json([
                    'error' => $data['errorMessage'] ?? 'Tanaza API request failed'
                ], 400);
            }

            // Calculate total users
            $totalUsers = 0;
            $payload = $data['payload'] ?? [];

            // Main account
            $account = $payload['account'] ?? [];
            foreach ($account['networks'] ?? [] as $network) {
                foreach ($network['devices'] ?? [] as $device) {
                    foreach ($device['ssids'] ?? [] as $ssid) {
                        $totalUsers += count($ssid['clients'] ?? []);
                    }
                }
            }

            // Subaccounts
            foreach ($payload['subaccounts'] ?? [] as $subaccount) {
                foreach ($subaccount['networks'] ?? [] as $network) {
                    if (is_array($network)) {
                        foreach ($network['devices'] ?? [] as $device) {
                            if ($device) {
                                foreach ($device['ssids'] ?? [] as $ssid) {
                                    if ($ssid) {
                                        $totalUsers += count($ssid['clients'] ?? []);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'totalUsers' => $totalUsers,
                'timestamp_utc' => $data['timestamp_utc'] ?? now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}