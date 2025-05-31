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
        Schema::create('detalle_egresos', function (Blueprint $table) {
            $table->id('detalle_egreso_id'); 
            $table->unsignedBigInteger('egreso_id'); 
            $table->unsignedBigInteger('producto_id'); 
            $table->unsignedBigInteger('color_id'); 
            $table->unsignedBigInteger('talla_id'); 
            $table->integer('cantidad'); 
            $table->decimal('precio_unitario', 10, 2); 
            $table->decimal('subtotal', 10, 2); 
            $table->foreign('egreso_id')->references('egreso_id')->on('egresos')->onDelete('cascade');
            $table->foreign('producto_id')->references('producto_id')->on('productos');
            $table->foreign('color_id')->references('color_id')->on('colores');
            $table->foreign('talla_id')->references('talla_id')->on('tallas');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_egresos');
    }
};
