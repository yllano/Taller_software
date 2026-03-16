<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesController;
use App\Http\Middleware\JwtMiddleware;

// Ruta pública
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por nuestro JWT manual
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/ventas', [SalesController::class, 'store']);
});