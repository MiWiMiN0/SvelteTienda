<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ClienteController;
use App\Http\Controllers\api\ProductoController;
use App\Http\Controllers\api\FacturaController;
use App\Http\Controllers\Api\AuthController;


Route::apiResource('productos', ProductoController::class);

Route::apiResource('cliente', ClienteController::class)->only(['index', 'show']);
Route::apiResource('factura', FacturaController::class)->only(['index', 'show']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('cliente', ClienteController::class)->except(['index', 'show']);
    
    Route::apiResource('factura', FacturaController::class)->except(['index', 'show']);
    Route::post('factura/{id}/add-producto', [FacturaController::class, 'agregarProducto']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('registrar', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('producto/{id}/imagen', [ProductoController::class, 'uploadImagen']);
