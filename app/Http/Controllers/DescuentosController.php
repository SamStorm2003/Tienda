<?php

namespace App\Http\Controllers;

use App\Models\Descuentos;
use Illuminate\Http\Request;
use Inertia\Response;
use App\Models\Productos;
use App\Models\ProductoDescuentos;
use App\Models\Productos_detalles;
use App\Models\Colores;
use App\Models\Tallas;
use App\Models\Proveedores;
use App\Models\Subcategorias;
use Carbon\Carbon;

class DescuentosController extends Controller
{
    /**
     * Muestra todos los descuentos y datos relacionados (para Inertia).
     */
    public function index()
    {
        return inertia('Descuentos/Index', [
            'Descuentos' => Descuentos::all(),
            'Productos' => Productos::with('proveedor')->get(),
            'Proveedores' => Proveedores::all(),
            'Subcategorias' => Subcategorias::all(),
            'ProductoDescuentos' => ProductoDescuentos::all(),
            'Productos_detalles' => Productos_detalles::all(),
        ]);
    }

    /**
     * API: Lista todos los descuentos activos (sin filtrar por fecha).
     */
    public function descuentosActivos()
    {
        $descuentos = Descuentos::where('activo', true)->get();

        return response()->json([
            'success' => true,
            'data' => $descuentos,
        ]);
    }
    /**
     * API: Lista todos los descuentos inactivos
     */
    public function descuentosInactivos()
{
    $descuentos = Descuentos::where('activo', 0)->get();

    return response()->json([
        'success' => true,
        'data' => $descuentos,
    ]);
}



    /**
     * API: Muestra un solo descuento.
     */
    public function show($id)
    {
        $descuento = Descuentos::find($id);

        if (!$descuento) {
            return response()->json(['message' => 'Descuento no encontrado'], 404);
        }

        return response()->json($descuento);
    }

    /**
     * API: Guarda un nuevo descuento.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,monto',
            'valor' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'activo' => 'required|boolean',
        ]);

        $descuento = Descuentos::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Descuento creado exitosamente',
            'data' => $descuento,
        ]);
    }

    /**
     * API: Actualiza un descuento existente.
     */
    public function update(Request $request, $id)
    {
        $descuento = Descuentos::find($id);

        if (!$descuento) {
            return response()->json(['message' => 'Descuento no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'sometimes|required|in:porcentaje,monto',
            'valor' => 'sometimes|required|numeric|min:0',
            'fecha_inicio' => 'sometimes|required|date',
            'fecha_fin' => 'sometimes|required|date|after_or_equal:fecha_inicio',
            'activo' => 'sometimes|required|boolean',
        ]);

        $descuento->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Descuento actualizado exitosamente',
            'data' => $descuento,
        ]);
    }

    /**
     * API: Elimina un descuento.
     */
    public function destroy($id)
    {
        $descuento = Descuentos::find($id);

        if (!$descuento) {
            return response()->json(['message' => 'Descuento no encontrado'], 404);
        }

        $descuento->activo = false;
        $descuento->save();

        return response()->json([
            'success' => true,
            'message' => 'Descuento eliminado exitosamente',
        ]);
    }
}
