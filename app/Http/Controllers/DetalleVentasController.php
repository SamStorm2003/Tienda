<?php

namespace App\Http\Controllers;

use App\Models\DetalleVentas;
use Illuminate\Http\Request;
use App\Models\Productos;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class DetalleVentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'venta_id' => 'required|exists:ventas,venta_id',
            'producto_id' => 'required|exists:productos,producto_id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric',
            'precio_con_descuento' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'costo_envio' => 'required|numeric',
            'direccion_envio' => 'required|string|max:255',
            'ciudad_envio' => 'required|string|max:100',
        ]);
        $producto = Productos::findOrFail($validatedData['producto_id']);
        $descripcion = $producto->descripcion ?? $producto->nombre;
        $detalleVenta = DetalleVentas::create([
            'venta_id' => $validatedData['venta_id'],
            'producto_id' => $validatedData['producto_id'],
            'cantidad' => $validatedData['cantidad'],
            'precio_unitario' => $validatedData['precio_unitario'],
            'precio_con_descuento' => $validatedData['precio_con_descuento'],
            'subtotal' => $validatedData['subtotal'],
            'costo_envio' => $validatedData['costo_envio'],
            'direccion_envio' => $validatedData['direccion_envio'],
            'ciudad_envio' => $validatedData['ciudad_envio'],
        ]);
        $data = [
            'codigo' => 5,
            'codoperacion' => $detalleVenta->id,
            'fecha' => now()->format('Y-m-d'),
            'montototal' => $validatedData['subtotal'],
            'detalle' => [
                [
                    'codproducto' => $validatedData['producto_id'],
                    'cantidad' => $validatedData['cantidad'],
                    'descripcion' => $descripcion,
                    'monto' => $validatedData['precio_unitario'],
                ],
            ],
        ];
        $apiUrl = '';
        $maxAttempts = 3;
        $attempt = 0;
        $codautorizacion = null;

        while ($attempt < $maxAttempts && $codautorizacion === null) {
            try {
                $response = Http::timeout(10)->post($apiUrl, $data);
                if ($response->status() == 201) {
                    $responseData = $response->json();
                    if (!empty($responseData['codautorizacion'])) {
                        $codautorizacion = $responseData['codautorizacion'];
                        $detalleVenta->update(['codautorizacion' => $codautorizacion]);
                        $secondApiUrl = '';
                        $secondApiData = [
                            'CodigoEmpresa' => 5,
                            'Fecha' => now()->format('Y-m-d'),
                            'Monto' => $validatedData['subtotal'],
                        ];
                        $secondResponse = Http::timeout(10)->post($secondApiUrl, $secondApiData);
                        if ($secondResponse->status() == 200) {
                            $secondResponseData = $secondResponse->json();
                            if (!empty($secondResponseData['cuenta_generada'])) {
                                $detalleVenta->update(['cuenta_generada' => $secondResponseData['cuenta_generada']]);
                            } else {
                                Log::error("No cuenta_generada for detalle_venta_id: {$detalleVenta->id}");
                            }
                        } else {
                            Log::error("Second API error for detalle_venta_id: {$detalleVenta->id}, HTTP Code: {$secondResponse->status()}, Response: {$secondResponse->body()}");
                        }
                    }
                } elseif ($response->status() == 422) {
                    Log::error("Validation error in first API: {$response->body()}");
                    break;
                }
            } catch (\Exception $e) {
                Log::error("API call failed for detalle_venta_id: {$detalleVenta->id}, Attempt: $attempt, Error: {$e->getMessage()}");
            }

            $attempt++;
            if ($codautorizacion === null && $attempt < $maxAttempts) {
                sleep(2);
            }
        }

        if ($codautorizacion === null) {
            Log::error("No codautorizacion obtained for detalle_venta_id: {$detalleVenta->id} after $maxAttempts attempts");
        }

        return response()->json([
            'message' => 'Detalles de la venta guardados con éxito.',
            'detalle_venta' => $detalleVenta,
            'codautorizacion' => $codautorizacion,
            'cuenta_generada' => $detalleVenta->cuenta_generada,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleVentas $detalleVentas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetalleVentas $detalleVentas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleVentas $detalleVentas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleVentas $detalleVentas)
    {
        //
    }
}
