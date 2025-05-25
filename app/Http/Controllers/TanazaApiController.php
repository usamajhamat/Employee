<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class TanazaApiController extends Controller
{
  public function getTotalUsers()
{
    // \Log::info('TanazaApiController::getTotalUsers called');
    $userId = env('TANAZA_USER_ID', '11F975B0CFD3F548776B8C9C738605D1');
    $token = env('TANAZA_TOKEN', 'A9E3F295D529FABA248B0790DAA30D9F');
    $url = "https://cloud.tanaza.com/apis/v3.0/{$userId}/{$token}/status";

    try {
        $response = Http::withoutVerifying()->get($url);

        // \Log::info('Tanaza API Response', ['status' => $response->status(), 'body' => substr($response->body(), 0, 500)]);
        if ($response->failed() || strpos($response->body(), '<!DOCTYPE') !== false) {
            // \Log::error('Invalid Tanaza response', ['status' => $response->status()]);
            return response()->json(['error' => 'Invalid response from Tanaza API'], 502);
        }

        $data = $response->json();
        if ($data['errorCode'] !== 0) {
            // \Log::error('Tanaza API error', ['errorCode' => $data['errorCode'], 'errorMessage' => $data['errorMessage']]);
            return response()->json(['error' => $data['errorMessage'] ?? 'Tanaza API request failed'], 400);
        }

        $ssidData = [];
        $totalUsers = 0;

        // Process main account networks
        $payload = $data['payload'] ?? [];
        foreach ($payload['account']['networks'] ?? [] as $network) {
            $networkName = $network['name'] ?? 'Unknown Network';
            foreach ($network['devices'] ?? [] as $device) {
                $deviceName = $device['name'] ?? 'Unknown Device';
                $addDate = $device['add_date'] ?? null;
                $label = $device['label'] ?? null;
                $id = $device['id'] ?? null;
                $macAddress = $device['mac_address'] ?? null;
                // \Log::info('Name', ['name' => $deviceName]);
                // \Log::info('Add Date', ['add_date' => $addDate]);
                // \Log::info('Label', ['label' => $label]);
                // \Log::info('ID', ['id' => $id]);
                // \Log::info('MAC Address', ['mac_address' => $macAddress]);
                foreach ($device['ssids'] ?? [] as $ssid) {
                    $clientCount = count($ssid['clients'] ?? []);
                    $totalUsers += $clientCount;
                    $ssidData[] = [
                        'id' => $id,
                        'mac_address' => $macAddress,
                        'label' => $label,
                        'client_count' => $clientCount,
                        'clients' => $ssid['clients'] ?? [] // Include client details if needed
                    ];
                }
            }
        }

        // \Log::info('SSID Data Collected', ['ssidData' => $ssidData, 'totalUsers' => $totalUsers]);
        return response()->json([
            'totalUsers' => $totalUsers,
            'ssidData' => $ssidData,
            'timestamp_utc' => $data['timestamp_utc'] ?? now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        // \Log::error('Exception in getTotalUsers', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}


   public function getCustomers()
{
    // \Log::info('TanazaApiController::getCustomers called');

    $userId = env('TANAZA_USER_ID', '11F975B0CFD3F548776B8C9C738605D1');
    $token = env('TANAZA_TOKEN', 'A9E3F295D529FABA248B0790DAA30D9F');
    $url = "https://cloud.tanaza.com/apis/v3.0/{$userId}/{$token}/status";

    try {
        $response = Http::withoutVerifying()->get($url);
        // \Log::info('Tanaza API Response', [
        //     'status' => $response->status(),
        //     'body' => substr($response->body(), 0, 500)
        // ]);

        if ($response->failed() || strpos($response->body(), '<!DOCTYPE') !== false) {
            // \Log::error('Invalid Tanaza response', ['status' => $response->status()]);
            return response()->json(['error' => 'Invalid response from Tanaza API'], 502);
        }

        $data = $response->json();
        if ($data['errorCode'] !== 0) {
            // \Log::error('Tanaza API error', [
            //     'errorCode' => $data['errorCode'],
            //     'errorMessage' => $data['errorMessage']
            // ]);
            return response()->json(['error' => $data['errorMessage'] ?? 'Tanaza API request failed'], 400);
        }

        $ssidData = [];
        $totalUsers = 0;

        // Process main account networks
        $payload = $data['payload'] ?? [];
        foreach ($payload['account']['networks'] ?? [] as $network) {
            $networkName = $network['label'] ?? 'Unknown Network';
            $devices = [];

            foreach ($network['devices'] ?? [] as $device) {
                // Count clients for totalUsers
                $deviceClients = 0;
                foreach ($device['ssids'] ?? [] as $ssid) {
                    $clientCount = count($ssid['clients'] ?? []);
                    $deviceClients += $clientCount;
                    $totalUsers += $clientCount;
                }

                // Map device data to match the expected frontend structure
                $devices[] = [
                    'public_ip_address' => $device['public_ip_address'] ?? null,
                    'add_date' => $device['add_date'] ?? null,
                    'label' => $device['label'] ?? 'Unknown Device',
                    'id' => $device['id'] ?? null,
                    'mac_address' => $device['mac_address'] ?? null,
                    'mac_addresses' => $device['mac_addresses'] ?? [],
                    'ssids' => $device['ssids'] ?? [],
                    'status' => $device['status'] ?? 'unknown',
                    'last_timestamp' => $device['last_timestamp'] ?? null,
                    'last_uptime' => $device['last_uptime'] ?? 0,
                    'last_bytes_rx' => $device['last_bytes_rx'] ?? 0,
                    'last_bytes_tx' => $device['last_bytes_tx'] ?? 0,
                    'last_pkts_rx' => $device['last_pkts_rx'] ?? 0,
                    'last_pkts_tx' => $device['last_pkts_tx'] ?? 0,
                    'last_load_percentage' => $device['last_load_percentage'] ?? 0,
                    'last_total_ram' => $device['last_total_ram'] ?? 0,
                    'last_free_ram' => $device['last_free_ram'] ?? 0,
                    'last_used_ram' => $device['last_used_ram'] ?? 0,
                    'last_used_ram_percentage' => $device['last_used_ram_percentage'] ?? 0,
                ];
            }

            // Add network to ssidData
            $ssidData[] = [
                'label' => $networkName,
                'devices' => $devices,
            ];
        }

        // \Log::info('Processed Data', [
        //     'totalUsers' => $totalUsers,
        //     'ssidData' => $ssidData
        // ]);

        return response()->json([
            'totalUsers' => $totalUsers,
            'ssidData' => $ssidData,
            'timestamp_utc' => $data['timestamp_utc'] ?? now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        // \Log::error('Exception in getCustomers', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}


public function getAccountInfo()
{
    // \Log::info('TanazaApiController::getNetworks called');

    $userId = env('TANAZA_USER_ID', '11F975B0CFD3F548776B8C9C738605D1');
    $token = env('TANAZA_TOKEN', 'A9E3F295D529FABA248B0790DAA30D9F');
    $url = "https://cloud.tanaza.com/apis/v3.0/{$userId}/{$token}/status";

    try {
        $response = Http::withoutVerifying()->get($url);

        if ($response->failed()) {
            \Log::error('Tanaza API failed', ['status' => $response->status()]);
            return response()->json(['error' => 'Failed to fetch networks'], 502);
        }

        $data = $response->json();

        if ($data['errorCode'] !== 0) {
            return response()->json(['error' => $data['errorMessage'] ?? 'Unknown error'], 400);
        }

        $networks = $data['payload']['account']['networks'] ?? [];
        // \Log::info($networks);

        return response()->json([
            'networks' => $networks,
            'timestamp_utc' => $data['timestamp_utc'] ?? now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        \Log::error('Exception in getNetworks', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}

public function store(Request $request)

{
    Log::info($request->all());
    // \Log::info('TanazaApiController::store called');
    // Handle the request to store Tanaza account information
    // This is a placeholder for your actual implementation
    return response()->json(['message' => 'Store method called'], 200);



}
}