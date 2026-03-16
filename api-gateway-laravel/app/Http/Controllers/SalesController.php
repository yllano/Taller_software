<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SalesController extends Controller
{
    public function store(Request $request)
    {
        // El usuario viene del Middleware JWT
        $user = $request->user_authenticated; 
        
        $productId = $request->product_id;
        $quantity = $request->quantity;

        // --- PASO 1: CONSULTAR A FLASK (INVENTARIO) ---
        $responseInv = Http::get(env('URL_INVENTARIO') . "/products/{$productId}");
        
        if ($responseInv->failed()) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        $productData = $responseInv->json();

        if ($productData['stock'] < $quantity) {
            return response()->json(['error' => 'Stock insuficiente en Firebase'], 400);
        }

        // --- PASO 2: CONECTAR CON EXPRESS (VENTAS EN MONGO) ---
        // Enviamos los datos para que Express los guarde en MongoDB
        $responseSales = Http::post(env('URL_VENTAS') . "/sales", [
            'userId'      => (string)$user['id'], // ID del usuario del JWT
            'productId'   => $productId,
            'productName' => $productData['name'],
            'quantity'    => $quantity,
            'totalPrice'  => $productData['price'] * $quantity
        ]);

        if ($responseSales->failed()) {
            return response()->json(['error' => 'No se pudo registrar la venta en MongoDB'], 500);
        }

        // --- PASO 3: CONFIRMAR EN FLASK (REDUCIR STOCK) ---
        // Solo llegamos aquí si Express confirmó que guardó la venta
        Http::patch(env('URL_INVENTARIO') . "/products/{$productId}/stock", [
            'quantity' => $quantity
        ]);

        return response()->json([
            'message' => 'Venta completada con éxito',
            'gateway_status' => 'Sincronizado',
            'db_sales_id' => $responseSales->json()['saleId'] // ID que generó Mongo
        ], 201);
    }
}