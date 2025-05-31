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
        Schema::create('detalle_carritos', function (Blueprint $table) {
            $table->id('detalle_carrito_id'); 
            $table->unsignedBigInteger('carrito_id');  
            $table->unsignedInteger('producto_detalle_id'); 
            $table->integer('cantidad');
            $table->decimal('precio_con_descuento', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('costo_envio', 10, 2)->default(0.00);
            $table->string('direccion_envio', 255)->nullable();
            $table->string('ciudad_envio', 100)->nullable();
            $table->foreign('carrito_id')->references('carrito_id')->on('carritos')->onDelete('cascade');
            $table->foreign('producto_detalle_id')->references('producto_detalle_id')->on('productos_detalles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_carritos');
    }
};
