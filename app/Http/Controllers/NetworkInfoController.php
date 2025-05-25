<?php

namespace App\Http\Controllers;

use App\Models\Network;
use App\Models\NetworkInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NetworkInfoController extends Controller
{

    public function storeNetwork(Request $request)
    {
        // Log::info($request->all());
        try {
            // Validate that 'account' is provided and is valid JSON
            $request->validate([
                'account' => 'required|json',
            ]);

            // Parse the JSON data
            $accountData = json_decode($request->input('account'), true);
            if (!$accountData) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            // Normalize input: Convert single account to array for consistent processing
            $accounts = isset($accountData['label']) ? [$accountData] : $accountData;

            $records = [];

            foreach ($accounts as $account) {
                if (!isset($account['devices'])) {
                    continue;
                }

                Network::create([
                     'name' =>$account['label'],
                ]);

               
            }

            // Insert records in bulk to improve performance
           

            return response()->json(['message' => 'Network information stored successfully', 'count' => count($records)], 200);
        } catch (\Exception $e) {
            Log::error('Error storing network info: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to store network information'], 500);
        }
    }
    public function store(Request $request)
    {
        Log::info($request->all());
        try {
            // Validate that 'account' is provided and is valid JSON
            $request->validate([
                'account' => 'required|json',
            ]);

            // Parse the JSON data
            $accountData = json_decode($request->input('account'), true);
            if (!$accountData) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            // Normalize input: Convert single account to array for consistent processing
            $accounts = isset($accountData['label']) ? [$accountData] : $accountData;

            $records = [];

            foreach ($accounts as $account) {
                if (!isset($account['devices'])) {
                    continue;
                }

                Network::create([
                     'name' =>$account['label'],
                ]);

                foreach ($account['devices'] as $device) {
                    // Skip offline devices or those with no clients
                    Log::info('Processing device: ' . $device['label']);
                    Log::info('Date Added: ' . $device['add_date']);
                    Log::info('ID: ' . $device['id']);
                    Log::info('MAC: ' . $device['mac_address']);


                    NetworkInfo::create([
                        'device_id' =>$device['id'] ,
                        'label' => $device['label'] ,
                        'mac_address' => $device['mac_address'] ,
                        'status' => $device['status'] ,
                        'public_ip_address' => $device['public_ip_address'] ?? "N/A" ,
                        'add_date' => $device['add_date'] ,
                        'last_timestamp' =>$device['last_timestamp'] ,
                        'last_uptime' => $device['last_uptime'] ,
                        'last_bytes_rx' => $device['last_bytes_rx'] ,
                        'last_bytes_tx' => $device['last_bytes_tx'] ,
                        'last_pkts_rx' => $device['last_pkts_rx'] ,
                        'last_pkts_tx' => $device['last_pkts_tx'] ,
                        'last_load_percentage' => $device['last_load_percentage'] ,
                        'last_total_ram' => $device['last_total_ram'] ,
                        'last_free_ram' => $device['last_free_ram'] ,
                        'last_used_ram' => $device['last_used_ram'] ,
                        'last_used_ram_percentage' => $device['last_used_ram_percentage'] ,
                    ]);


                }
            }

            // Insert records in bulk to improve performance
           

            return response()->json(['message' => 'Network information stored successfully', 'count' => count($records)], 200);

        } catch (\Exception $e) {
            Log::error('Error storing network info: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to store network information'], 500);
        }
    }
}