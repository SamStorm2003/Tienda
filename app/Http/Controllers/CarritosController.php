<?php

namespace App\Http\Controllers;

use App\Models\Carritos;
use Illuminate\Http\Request;
use App\Models\DetalleCarrito;
use App\Models\Ventas;
use App\Models\DetalleVentas;
use App\Models\Productos;
use App\Models\Productos_detalles;
use App\Models\Colores;
use App\Models\Tallas;
use App\Models\Envios;
use App\Models\Proveedores;
use App\Models\Subcategorias;
use App\Models\ProductoDescuentos;
use App\Models\Descuentos;
use Inertia\Inertia;

class CarritosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Carrito = Carritos::all(); 
        $detalleCarrito = DetalleCarrito::all();
        $ventas = Ventas::all();
        $detalleventas = DetalleVentas::all();
        $productos = Productos::all();
        $productos_detalles = Productos_detalles::all();
        $proveedores = Proveedores::all();
        $subcategorias = Subcategorias::all();
        $descuentos = Descuentos::all();
        $productodescuentos = ProductoDescuentos::all();
        $colores = Colores::all();
        $tallas = Tallas::all();
        $envios = Envios::all();
        return inertia('Carrito/Index', [
            'Carrito' => $Carrito,
            'DetalleCarrito' => $detalleCarrito,
            'Ventas' => $ventas,
            'DetalleVentas' => $detalleventas,
            'Productos' => $productos,
            'Productos_detalles' => $productos_detalles,
            'Proveedores' => $proveedores,
            'Subcategorias' => $subcategorias,
            'Descuentos' => $descuentos,
            'ProductoDescuentos' => $productodescuentos,
            'Colores' => $colores,
            'Tallas' => $tallas,
            'Envios' => $envios,
        ]);
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
    public function show(Carritos $carritos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carritos $carritos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carritos $carritos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($detalleCarritoId)
{
    try {
        $detalleCarrito = DetalleCarrito::findOrFail($detalleCarritoId);
        $carrito = Carritos::find($detalleCarrito->carrito_id);
        
        $detalleCarrito->delete();

        if ($carrito && $carrito->detalles()->count() === 0) {
            $carrito->delete();
        }

        return redirect()->back()
            ->with('message', 'Producto eliminado del carrito exitosamente!');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error al eliminar el producto del carrito');
    }
}
    public function agregarAlCarrito(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
        ]);
        $carrito = Carritos::create([
            'usuario_id' => $request->usuario_id,
        ]);
        return response()->json([
            'carrito_id' => $carrito->carrito_id,
            'message' => 'Carrito creado con éxito!'
        ], 201);
    }

    public function comprar(Request $request, $productoId)
{
    $producto = Productos::findOrFail($productoId);
    return Inertia::render('Productos/FormComprar', [
        'producto' => $producto,
        'subtotal' => $request->subtotal,
        'ciudad' => $request->ciudad,
        'envio' => $request->envio,
        'descuento' => $request->descuento,
        'total' => $request->total,
        'color' => $request->color,
        'talla' => $request->talla,
        'cantidad' => $request->cantidad,
        'detalle_carrito_id' => $request->detalle_carrito_id,
        'carrito_id' => $request->carrito_id,
    ]);
}

    public function comprarCarrito($detalleCarritoId)
    {
        $detalleCarrito = DetalleCarrito::findOrFail($detalleCarritoId);
        $detalleCarrito->delete();
        $carrito = Carritos::with('detalles')->find($detalleCarrito->carrito_id);
        if ($carrito->detalles->isEmpty()) {
            $carrito->delete();
        }
        return Inertia::render('Productos/FormComprar', []);
    }
}
