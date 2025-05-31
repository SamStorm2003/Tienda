<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cupones;
use App\Models\Proveedores;
use App\Models\Descuentos;

class Edit3Controller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:cupones,codigo',
            'descripcion' => 'nullable|string',
            'descuento' => 'required|numeric',
            'id_objeto' => 'nullable|exists:productos,producto_id',
        ]);

        $cupon = Cupones::create($request->all());
        return response()->json($cupon, 201);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
            'descripcion' => 'nullable|string',
            'descuento' => 'required|numeric',
            'id_objeto' => 'nullable|exists:productos,producto_id',
        ]);

        $cupon = Cupones::findOrFail($id);
        $cupon->update($request->all());
        return response()->json($cupon);
    }

    public function destroy($id)
    {
        $cupon = Cupones::findOrFail($id);
        $cupon->delete();
        return response()->json(null, 204);
    }


    // Métodos para Proveedores

    public function storeProveedor(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $proveedor = Proveedores::create($request->all());
        return response()->json($proveedor, 201);
    }

    public function updateProveedor(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $proveedor = Proveedores::findOrFail($id);
        $proveedor->update($request->all());
        return response()->json($proveedor);
    }

    public function destroyProveedor($id)
    {
        $proveedor = Proveedores::findOrFail($id);
        $proveedor->delete();
        return response()->json(null, 204);
    }

    public function storeDescuento(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:Porcentaje,Valor Fijo',
            'valor' => 'required|numeric',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'activo' => 'boolean',
        ]);

        $descuento = Descuentos::create($request->all());
        return response()->json($descuento, 201);
    }

    public function updateDescuento(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:Porcentaje,Valor Fijo',
            'valor' => 'required|numeric',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'activo' => 'boolean',
        ]);

        $descuento = Descuentos::findOrFail($id);
        $descuento->update($request->all());
        return response()->json($descuento);
    }

    public function destroyDescuento($id)
    {
        $descuento = Descuentos::findOrFail($id);
        $descuento->delete();
        return response()->json(null, 204);
    }
}
