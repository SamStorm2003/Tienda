<?php

namespace App\Http\Controllers;

use App\Models\Ventas;
use Illuminate\Http\Request;
use App\Models\ProductoDescuentos;
use App\Models\Descuentos;
use App\Models\Productos_detalles;
use App\Models\Colores;
use App\Models\Tallas;
use App\Models\Envios;
use App\Models\Productos;
use App\Models\Proveedores;
use App\Models\Subcategorias;
use App\Models\Sucursales;
class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Ventas = Ventas::paginate(25);
        $producto = Productos::all();
        $proveedores = Proveedores::all();
        $subcategorias = Subcategorias::all();
        $descuentos = Descuentos::all();
        $productodescuentos = ProductoDescuentos::all();
        $productodetalles = Productos_detalles::all();
        $colores = Colores::all();
        $tallas = Tallas::all();
        $envios = Envios::all();
        $sucursales =Sucursales::all();
        return inertia('Ventas/Index', [
            'Ventas' => $Ventas,
            'Producto' => $producto,
            'Proveedores' => $proveedores,
            'Subcategorias' => $subcategorias,
            'Descuentos' => $descuentos,
            'ProductoDescuentos' => $productodescuentos,
            'Productos_detalles' => $productodetalles,
            'Colores' => $colores,
            'Tallas' => $tallas,
            'Envios' => $envios,
            'Sucursales' => $sucursales,
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
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
        ]);
        $venta = Ventas::create([
            'usuario_id' => $request->usuario_id,
            'total' => $request->total,
            'estado' => 'Pagado',
        ]);
        return response()->json([
            'venta_id' => $venta->venta_id,
            'message' => 'Venta registrada exitosamente',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ventas $ventas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ventas $ventas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ventas $ventas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ventas $ventas)
    {
        //
    }
}
