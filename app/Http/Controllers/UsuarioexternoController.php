<?php

namespace App\Http\Controllers;

use App\Models\Usuarioexterno;
use Illuminate\Http\Request;

class UsuarioexternoController extends Controller
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
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'ingreso_id' => 'required|integer',
        ]);

        $usuarioExterno = UsuarioExterno::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'ingreso_id' => $request->ingreso_id,
        ]);

        return response()->json([
            'message' => 'Usuario externo creado exitosamente.',
        ], 200);        
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuarioexterno $usuarioexterno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuarioexterno $usuarioexterno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuarioexterno $usuarioexterno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuarioexterno $usuarioexterno)
    {
        //
    }
}
