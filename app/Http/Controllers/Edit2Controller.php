<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colores;
use App\Models\Tallas;
use App\Models\Envios;
use App\Models\Sucursales;

class Edit2Controller extends Controller
{

    // Agregar un nuevo color
    public function addColor(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:colores',
        ]);

        $color = Colores::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json($color);
    }

    // Editar un color existente
    public function editColor(Request $request, $id)
    {
        $color = Colores::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:50|unique:colores,nombre,' . $id . ',color_id',
        ]);

        $color->update([
            'nombre' => $request->nombre,
        ]);

        return response()->json($color);
    }

    // Eliminar un color
    public function deleteColor($id)
    {
        $color = Colores::findOrFail($id);
        $color->delete();

        return response()->json(['message' => 'Color eliminado']);
    }

    // Agregar nueva talla
    public function addtallas(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:10|unique:tallas,nombre',
        ]);

        $talla = Tallas::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json($talla, 201);
    }

    // Editar talla existente
    public function edittallas(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:10|unique:tallas,nombre,' . $id . ',talla_id',
        ]);

        $talla = Tallas::findOrFail($id);
        $talla->update(['nombre' => $request->nombre]);

        return response()->json($talla);
    }

    // Eliminar talla
    public function deletetallas($id)
    {
        $talla = Tallas::findOrFail($id);
        $talla->delete();

        return response()->json(null, 204);
    }


    // Agregar nuevo envío
    public function addEnvios(Request $request)
    {
        $request->validate([
            'ciudad' => 'required|string',
            'pais' => 'required|string',
            'precio' => 'required|numeric',
        ]);

        $envio = Envios::create([
            'ciudad' => $request->ciudad,
            'pais' => $request->pais,
            'precio' => $request->precio,
        ]);

        return response()->json($envio, 201);
    }

    // Editar envío existente
    public function editEnvios(Request $request, $id)
    {
        $request->validate([
            'ciudad' => 'required|string',
            'pais' => 'required|string',
            'precio' => 'required|numeric',
        ]);

        $envio = Envios::findOrFail($id);
        $envio->update([
            'ciudad' => $request->ciudad,
            'pais' => $request->pais,
            'precio' => $request->precio,
        ]);

        return response()->json($envio);
    }

    // Eliminar envío
    public function deleteEnvios($id)
    {
        $envio = Envios::findOrFail($id);
        $envio->delete();

        return response()->json(null, 204);
    }

    // Agregar nueva sucursal
    public function addSucursales(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:15',
        ]);

        $sucursal = Sucursales::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono,
        ]);

        return response()->json($sucursal, 201);
    }

    // Editar sucursal existente
    public function editSucursales(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:15',
        ]);

        $sucursal = Sucursales::findOrFail($id);
        $sucursal->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono,
        ]);

        return response()->json($sucursal);
    }

    // Eliminar sucursal
    public function deleteSucursales($id)
    {
        $sucursal = Sucursales::findOrFail($id);
        $sucursal->delete();

        return response()->json(null, 204);
    }
}
