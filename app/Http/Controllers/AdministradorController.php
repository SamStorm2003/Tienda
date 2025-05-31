<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Administrador;
use App\Models\ProductoDescuentos;
use App\Models\Descuentos;
use App\Models\Productos_detalles;
use App\Models\Colores;
use App\Models\Tallas;
use App\Models\Envios;
use App\Models\Proveedores;
use App\Models\Subcategorias;
use App\Models\Productos;
use App\Models\Carritos;
use App\Models\DetalleCarrito;
use App\Models\Ingresos;
use App\Models\DetalleIngresos;
use App\Models\User;
use App\Models\Usuarioexterno;
use App\Models\Sucursales;
use App\Models\DetalleCompras;
use App\Models\DetalleVentas;
use App\Models\Cupones;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function obtenerDatosGenerales()
     {
        $cupones = Cupones::with('producto')->get(); 
         return response()->json([
             'administradores' => Administrador::all(),
             'productosDescuentos' => ProductoDescuentos::all(),
             'descuentos' => Descuentos::all(),
             'productosDetalles' => Productos_detalles::all(),
             'colores' => Colores::all(),
             'tallas' => Tallas::all(),
             'envios' => Envios::all(),
             'proveedores' => Proveedores::all(),
             'subcategorias' => Subcategorias::all(),
             'productos' => Productos::all(),
             'carritos' => Carritos::all(),
             'detalleCarrito' => DetalleCarrito::all(),
             'ingresos' => Ingresos::all(),
             'detalleIngresos' => DetalleIngresos::all(),
             'usuarios' => User::all(),
             'usuariosExternos' => Usuarioexterno::all(),
             'sucursales' => Sucursales::all(),
             'detalleCompras' => DetalleCompras::all(),
             'detalleVentas' => DetalleVentas::all(),
             'cupones' => $cupones,
         ]);
     }

    public function index()
    {
        $Administrador = Administrador::all();
        return inertia('Administrador/Index',['Administrador'=>$Administrador]);
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
    public function show(Administrador $administrador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrador $administrador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Administrador $administrador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrador $administrador)
    {
        //
    }

    public function edit2()
    {
        return Inertia::render('Administrador/Edit2');
    }
    public function edit3()
    {
        return Inertia::render('Administrador/Edit3');
    }

    public function obtenerDatosedit2()
    {
        $colores = Colores::all();
        $tallas = Tallas::all();
        $cupones = Cupones::all();
        $envios = Envios::all();
        $sucursales = Sucursales::all();

        return response()->json([
            'colores' => $colores,
            'tallas' => $tallas,
            'cupones' => $cupones,
            'envios' => $envios,
            'sucursales' => $sucursales,
        ]);
    }
}
