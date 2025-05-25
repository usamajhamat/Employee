<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return [
                'message' => 'Unauthorized',
            ];
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials, true);
        if (!$token) {
            return [
                'message' => 'Unauthorized',
            ];
        }

        $user = Auth::user();
        return [
            'message' => 'Logged in successfully.',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ];
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
