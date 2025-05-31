<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('egresos', function (Blueprint $table) {
            $table->id('egreso_id'); 
            $table->unsignedBigInteger('sucursal_id'); 
            $table->unsignedBigInteger('proveedor_id'); 
            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP')); // Timestamp con valor por defecto
            $table->decimal('total', 10, 2); 
            $table->foreign('sucursal_id')->references('sucursal_id')->on('sucursales');
            $table->foreign('proveedor_id')->references('proveedor_id')->on('proveedores');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egresos');
    }
};
