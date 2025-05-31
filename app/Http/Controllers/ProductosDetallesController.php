<?php

namespace App\Http\Controllers;

use App\Models\Productos_detalles;
use Illuminate\Http\Request;

class ProductosDetallesController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Productos_detalles $productos_detalles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Productos_detalles $productos_detalles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Productos_detalles $productos_detalles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Productos_detalles $productos_detalles)
    {
        //
    }

    // Método para actualizar el stock
    public function actualizarStock(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        try {
            $producto_id = $request->producto_id;
            $cantidad = $request->cantidad;
            $productoDetalle = Productos_detalles::where('producto_id', $producto_id)->first();
            if (!$productoDetalle) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            if ($productoDetalle->stock < $cantidad) {
                return response()->json(['message' => 'No hay suficiente stock disponible'], 400);
            }
            $productoDetalle->stock -= $cantidad;
            $productoDetalle->fecha_actualizacion = now();
            $productoDetalle->save();

            return response()->json(['message' => 'Stock actualizado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el stock', 'error' => $e->getMessage()], 500);
        }
    }
}
