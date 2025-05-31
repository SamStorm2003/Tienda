<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use App\Models\Proveedores;
use App\Models\Subcategorias;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductoDescuentos;
use App\Models\Descuentos;
use App\Models\Productos_detalles;
use App\Models\Colores;
use App\Models\Tallas;
use App\Models\Envios;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Productos::with('proveedor')->get();
        $proveedores = Proveedores::all();
        $subcategorias = Subcategorias::all();
        $descuentos = Descuentos::all();
        $productodescuentos = ProductoDescuentos::all();
        $productodetalles = Productos_detalles::all();

        return inertia('Productos/Index', [
            'Productos' => $productos,
            'Proveedores' => $proveedores,
            'Subcategorias' => $subcategorias,
            'Descuentos' => $descuentos,
            'ProductoDescuentos' => $productodescuentos,
            'Productos_detalles' => $productodetalles,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedores::all();
        $subcategorias = Subcategorias::all();
        $colores = Colores::all();
        $tallas = Tallas::all();
        return inertia('Productos/Create', [
            'proveedores' => $proveedores,
            'subcategorias' => $subcategorias,
            'colores' => $colores,
            'tallas' => $tallas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'sku' => 'required|string|max:50|unique:productos,sku',
            'precio' => 'required|numeric|min:0',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'imagen' => 'nullable|image|mimes:jpeg,png,svg|max:1024',
            'subcategoria_id' => 'nullable|exists:subcategorias,subcategoria_id',
            'proveedor_id' => 'nullable|exists:proveedores,proveedor_id',
            'temporada' => 'required|in:Primavera,Verano,Otoño,Invierno',
            'genero' => 'required|in:Hombre,Mujer,Niño,Niña,Unisex',
        ]);

        $rutaImagen = 'imagenes/default.jpg';
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = now()->format('Ymd_His') . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = $imagen->storeAs('imagenes', $nombreArchivo, 'public');
        }

        $producto = Productos::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'sku' => $request->sku,
            'precio' => $request->precio,
            'descuento_porcentaje' => $request->descuento_porcentaje,
            'imagen_url' => $rutaImagen,
            'subcategoria_id' => $request->subcategoria_id,
            'proveedor_id' => $request->proveedor_id,
            'temporada' => $request->temporada,
            'genero' => $request->genero,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente.',
            'producto_id' => $producto->producto_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($producto_id)
    {
        $producto = Productos::findOrFail($producto_id);
        $proveedores = Proveedores::all();
        $subcategorias = Subcategorias::all();
        $descuentos = Descuentos::all();
        $productodescuentos = ProductoDescuentos::all();
        $productodetalles = Productos_detalles::all();
        $colores = Colores::all();
        $tallas = Tallas::all();
        $envios = Envios::all();

        return inertia('Productos/Buy', [
            'Producto' => $producto,
            'Proveedores' => $proveedores,
            'Subcategorias' => $subcategorias,
            'Descuentos' => $descuentos,
            'ProductoDescuentos' => $productodescuentos,
            'Productos_detalles' => $productodetalles,
            'Colores' => $colores,
            'Tallas' => $tallas,
            'Envios' => $envios,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($producto_id)
    {
        $producto = Productos::findOrFail($producto_id);
        $proveedores = Proveedores::all();
        $subcategorias = Subcategorias::all();
        $productodetalles = Productos_detalles::all();
        $colores = Colores::all();
        $tallas = Tallas::all();

        return inertia('Productos/Edit', [
            'producto' => $producto,
            'proveedores' => $proveedores,
            'subcategorias' => $subcategorias,
            'productosdetalles' => $productodetalles,
            'colores' => $colores,
            'tallas' => $tallas,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $producto_id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('productos', 'sku')->ignore($producto_id, 'producto_id'),
            ],
            'precio' => 'required|numeric|min:0',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'imagen' => 'nullable|image|mimes:jpeg,png,svg|max:1024',
            'subcategoria_id' => 'nullable|exists:subcategorias,subcategoria_id',
            'proveedor_id' => 'nullable|exists:proveedores,proveedor_id',
            'temporada' => 'required|in:Primavera,Verano,Otoño,Invierno',
            'genero' => 'required|in:Hombre,Mujer,Niño,Niña,Unisex',
        ]);

        $producto = Productos::findOrFail($producto_id);

        if ($request->hasFile('imagen')) {

            if ($producto->imagen_url && $producto->imagen_url !== 'imagenes/default.jpg') {
                Storage::disk('public')->delete($producto->imagen_url);
            }

            $imagen = $request->file('imagen');
            $nombreArchivo = now()->format('Ymd_His') . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = $imagen->storeAs('imagenes', $nombreArchivo, 'public');
            $validatedData['imagen_url'] = $rutaImagen;
        }

        $producto->update($validatedData);

        return redirect()->route('Productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($producto_id)
    {
        $producto = Productos::findOrFail($producto_id);
        if ($producto->imagen_url && $producto->imagen_url !== 'imagenes/default.jpg') {
            Storage::disk('public')->delete($producto->imagen_url);
        }
        $producto->delete();
        return redirect()->route('Productos.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function comprar($productoId)
    {
        $producto = Productos::findOrFail($productoId);
        $subtotal = request()->get('subtotal');
        $ciudad = request()->get('ciudad');
        $envio = request()->get('envio');
        $descuento = request()->get('descuento');
        $total = request()->get('total');
        $color = request()->get('color');
        $talla = request()->get('talla');
        $cantidad = request()->get('cantidad');

        return Inertia::render('Productos/FormComprar', [
            'producto' => $producto,
            'subtotal' => $subtotal,
            'ciudad' => $ciudad,
            'envio' => $envio,
            'descuento' => $descuento,
            'total' => $total,
            'color' => $color,
            'talla' => $talla,
            'cantidad' => $cantidad,
        ]);
    }

    public function agregar_detalle_producto(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,producto_id',
            'color_id' => 'required|exists:colores,color_id',
            'talla_id' => 'required|exists:tallas,talla_id',
            'stock' => 'required|integer|min:0',
        ]);
        Productos_detalles::create([
            'producto_id' => $request->producto_id,
            'color_id' => $request->color_id,
            'talla_id' => $request->talla_id,
            'stock' => $request->stock,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Detalles del producto agregados exitosamente.',
        ]);
    }
    public function insertar_color(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:colores,nombre',
        ]);

        $color = Colores::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Color creado exitosamente.',
            'color_id' => $color->color_id,
        ]);
    }

    public function actualizar_stock_producto(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer',
            'color_id' => 'required|exists:colores,color_id',
            'talla_id' => 'required|exists:tallas,talla_id',
        ]);
        $productoDetalle = Productos_detalles::findOrFail($id);
        $productoDetalle->stock = $request->stock;
        $productoDetalle->color_id = $request->color_id;
        $productoDetalle->talla_id = $request->talla_id;
        $productoDetalle->fecha_actualizacion = now();
        $productoDetalle->save();
        return response()->json(['message' => 'Stock actualizado correctamente.']);
    }
}
