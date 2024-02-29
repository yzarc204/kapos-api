<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only("email", "password");

        if (!auth()->attempt($credentials)) {
            $response = [
                'error' => 'Credentials incorrect'
            ];
            return response()->json($response, 401);
        }

        $token = auth()->user()->createToken('administrator')->plainTextToken;

        $response = [
            'access_token' => $token
        ];
        return response()->json($response, 200);
    }
}
