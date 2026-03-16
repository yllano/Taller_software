<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Simulación de usuario de ejemplo para cumplir el requisito sin BD
        if ($request->email === 'admin@tienda.com' && $request->password === '123456') {
            $payload = [
                'iss' => "api-gateway", // Emisor
                'aud' => "microservices", // Audiencia
                'iat' => time(), // Tiempo de emisión
                'exp' => time() + (60*60), // Expira en 1 hora
                'data' => [
                    'id' => 'USR-001',
                    'email' => $request->email,
                    'role' => 'admin'
                ]
            ];

            $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

            return response()->json([
                'message' => 'Login exitoso',
                'token' => $jwt
            ], 200);
        }

        return response()->json(['error' => 'Credenciales inválidas'], 401);
    }
}
