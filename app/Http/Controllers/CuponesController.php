<?php

namespace App\Http\Controllers;

use App\Models\Cupones;
use Illuminate\Http\Request;

class CuponesController extends Controller
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
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show($codigo, Request $request)
    {
        $producto_id = $request->query('producto_id');
        $coupon = Cupones::where('codigo', $codigo)->first();

        if ($coupon) {
            if ($coupon->id_objeto == $producto_id) {
                return response()->json([
                    'valido' => true,
                    'cupon' => [
                    'descripcion' => $coupon->descripcion,
                    'descuento' => $coupon->descuento 
                ],
                    'mensaje' => 'Descuento aplicable'
                ]);
            } else {
                return response()->json([
                    'valido' => false,
                    'mensaje' => 'Cupón no aplicable para el producto'
                ]);
            }
        } else {
            return response()->json([
                'valido' => false,
                'mensaje' => 'Cupón no válido'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cupones $cupones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cupones $cupones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupones $cupones)
    {
        //
    }
}
