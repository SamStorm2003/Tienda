<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Ingresos;
use App\Models\DetalleIngresos;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Usuarioexterno;
use App\Models\Productos_detalles;
use App\Models\Ventas;
use App\Models\DetalleVentas;

class EncargadoVentas extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

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
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function guardarVenta(Request $request)
    {
        $validatedData = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'sucursal_id' => 'required|exists:sucursales,sucursal_id',
            'total' => 'required|numeric',
        ]);
        $venta = Ventas::create([
            'usuario_id' => $validatedData['usuario_id'],
            'fecha' => now(),
            'total' => $validatedData['total'],
            'estado' => 'Pagado',
        ]);
        return response()->json(['venta_id' => $venta->venta_id], 201);
    }

    public function guardarDetalleVenta(Request $request)
    {
        $validatedData = $request->validate([
            'venta_id' => 'required|exists:ventas,venta_id',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,producto_id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.precio_con_descuento' => 'required|numeric|min:0',
            'productos.*.subtotal' => 'required|numeric|min:0',
            'productos.*.costo_envio' => 'nullable|numeric|min:0',
            'productos.*.direccion_envio' => 'nullable|string|max:255',
            'productos.*.ciudad_envio' => 'nullable|string|max:100',
        ]);
        foreach ($validatedData['productos'] as $producto) {
            DetalleVentas::create([
                'venta_id' => $validatedData['venta_id'],
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'precio_con_descuento' => $producto['precio_con_descuento'],
                'subtotal' => $producto['subtotal'],
                'costo_envio' => $producto['costo_envio'] ?? 0.00,
                'direccion_envio' => $producto['direccion_envio'] ?? null,
                'ciudad_envio' => $producto['ciudad_envio'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Detalles de venta guardados exitosamente'], 201);
    }

    public function guardar_venta_stock(Request $request)
    {
        $productos = $request->input('productos');
        foreach ($productos as $producto) {
            $productoDetalle = Productos_detalles::where('producto_id', $producto['producto_id'])
                ->first();

            if ($productoDetalle) {
                $nuevoStock = $productoDetalle->stock - $producto['cantidad'];
                if ($nuevoStock < 0) {
                    return response()->json(['error' => 'Stock insuficiente para el producto: ' . $producto['producto_id']], 400);
                }
                $productoDetalle->stock = $nuevoStock;
                $productoDetalle->save();
            }
        }
        return response()->json(['success' => true]);
    }

    public function buscarUsuarioPorDocumento($documento_identidad)
    {
        $usuario = User::where('documento_identidad', $documento_identidad)->first();

        if ($usuario) {
            return response()->json([
                'id_usuario' => $usuario->id,
                'nombre' => $usuario->name,
                'apellido' => $usuario->apellido,
                'email' => $usuario->email,
                'telefono' => $usuario->telefono,
                'documento_identidad' => $usuario->documento_identidad,
            ], 200);
        } else {
            return response()->json(['message' => 'Usuario no encontrado'], 404); // Usuario no encontrado
        }
    }

    public function ventaAnonima(Request $request)
    {
        $validatedData = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,sucursal_id',
            'total' => 'required|numeric',
        ]);

        $ingreso = Ingresos::create([
            'sucursal_id' => $validatedData['sucursal_id'],
            'fecha' => now(),
            'total' => $validatedData['total'],
            'estado' => 'Pagado',
        ]);
        return response()->json(['ingreso_id' => $ingreso->ingreso_id], 201);
    }

    public function ventaAnonimaDetalle(Request $request)
    {
        $validatedData = $request->validate([
            'ingreso_id' => 'required|exists:ingresos,ingreso_id',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,producto_id',
            'productos.*.color_id' => 'required|exists:colores,color_id',
            'productos.*.talla_id' => 'required|exists:tallas,talla_id',
            'productos.*.vendedor_id' => 'required|exists:users,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric',
            'productos.*.precio_con_descuento' => 'required|numeric',
            'productos.*.subtotal' => 'required|numeric',
        ]);

        foreach ($validatedData['productos'] as $producto) {
            DetalleIngresos::create([
                'ingreso_id' => $validatedData['ingreso_id'],
                'producto_id' => $producto['producto_id'],
                'color_id' => $producto['color_id'],
                'talla_id' => $producto['talla_id'],
                'vendedor_id' => $producto['vendedor_id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'precio_con_descuento' => $producto['precio_con_descuento'],
                'subtotal' => $producto['subtotal'],
            ]);
        }
        return response()->json(['message' => 'Detalles de la venta guardados con éxito'], 200);
    }

    public function actualizarStock(Request $request)
    {
        $validatedData = $request->validate([
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,producto_id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);
    
        foreach ($validatedData['productos'] as $producto) {
            $productoDetalle = Productos_detalles::where('producto_id', $producto['producto_id'])->first();
    
            if ($productoDetalle) {
                $nuevoStock = $productoDetalle->stock - $producto['cantidad'];
                if ($nuevoStock < 0) {
                    return response()->json(['error' => 'Stock insuficiente para el producto: ' . $producto['producto_id']], 400);
                }
                $productoDetalle->stock = $nuevoStock;
                $productoDetalle->fecha_actualizacion = now();
                $productoDetalle->save();
            }
        }
        return response()->json(['message' => 'Stock actualizado correctamente'], 200);
    }    
}
