<?php

namespace App\Http\Controllers;

use App\Services\TanazaApiService;
use App\Services\TanazaService;
use Illuminate\Http\JsonResponse;

class TanazaController extends Controller
{
    protected $tanazaService;

    public function __construct(TanazaApiService $tanazaService)
    {
        $this->tanazaService = $tanazaService;
    }

    public function getDeviceStatus(): JsonResponse
    {
        $result = $this->tanazaService->getDeviceStatus();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data' => $result['data'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], $result['status'] ?? 500);
    }
}