<?php

namespace App\Http\Controllers;

use App\Models\DetalleCarrito;
use Illuminate\Http\Request;

class DetalleCarritoController extends Controller
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
        $request->validate([
            'carrito_id' => 'required|exists:carritos,carrito_id',
            'producto_detalle_id' => 'required|exists:productos_detalles,producto_detalle_id',
            'cantidad' => 'required|integer|min:1',
            'precio_con_descuento' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'costo_envio' => 'nullable|numeric',
            'direccion_envio' => 'nullable|string|max:255',
            'ciudad_envio' => 'nullable|string|max:100',
        ]);
        $detalleCarrito = DetalleCarrito::create($request->all());
        return response()->json([
            'message' => 'Producto agregado al carrito con éxito!',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleCarrito $detalleCarrito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetalleCarrito $detalleCarrito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleCarrito $detalleCarrito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleCarrito $detalleCarrito)
    {
        //
    }
}
