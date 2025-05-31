<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id('inventario_id'); 
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('talla_id');
            $table->integer('stock')->default(0);
            $table->timestamp('fecha_actualizacion')->useCurrent();
            $table->timestamps();

            $table->index(['producto_id', 'sucursal_id', 'color_id', 'talla_id']);

            $table->foreign('producto_id')->references('producto_id')->on('productos')->onDelete('cascade');
            $table->foreign('sucursal_id')->references('sucursal_id')->on('sucursales')->onDelete('cascade');
            $table->foreign('color_id')->references('color_id')->on('colores');
            $table->foreign('talla_id')->references('talla_id')->on('tallas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
