<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuponesController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\EncargadoVentas;
use App\Http\Controllers\DescuentosController;
use App\Models\Cupones;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
//Api (publicacion de servicio de descuentos)

Route::get('/descuentos', [DescuentosController::class, 'descuentosActivos']);
Route::get('/descuentos/{id}', [DescuentosController::class, 'show']);
Route::post('/descuentos', [DescuentosController::class, 'store']);
Route::put('/descuentos/{id}', [DescuentosController::class, 'update']);
Route::delete('/descuentos/{id}', [DescuentosController::class, 'destroy']);
Route::get('/descuentos/inactivos', [DescuentosController::class, 'descuentosInactivos']);
