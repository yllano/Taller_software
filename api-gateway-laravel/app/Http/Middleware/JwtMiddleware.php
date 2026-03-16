<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        try {
            $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            // Pasamos los datos del usuario decodificado a la petición
            $request->user_authenticated = (array) $credentials->data;

        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido', 'message' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
