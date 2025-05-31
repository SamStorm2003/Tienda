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
        Schema::create('productos_detalles', function (Blueprint $table) {
            $table->increments('producto_detalle_id'); 
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('color_id');  
        $table->unsignedBigInteger('talla_id');
            $table->integer('stock')->default(0);
            $table->timestamp('fecha_actualizacion')->useCurrent();
            $table->foreign('producto_id')->references('producto_id')->on('productos')->onDelete('cascade');
            $table->foreign('color_id')->references('color_id')->on('colores');
            $table->foreign('talla_id')->references('talla_id')->on('tallas');
            $table->unique(['producto_id', 'color_id', 'talla_id']);
            $table->Timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_detalles');
    }
};
