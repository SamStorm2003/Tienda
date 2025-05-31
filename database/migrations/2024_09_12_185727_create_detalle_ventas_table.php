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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id('detalle_venta_id');
            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('precio_con_descuento', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('costo_envio', 10, 2)->default(0.00);
            $table->string('direccion_envio', 255)->nullable();
            $table->string('ciudad_envio', 100)->nullable();
            $table->foreign('venta_id')->references('venta_id')->on('ventas')->onDelete('cascade');
            $table->foreign('producto_id')->references('producto_id')->on('productos'); // Asegúrate de que la tabla `productos` existe
            $table->string('codautorizacion')->nullable();
            $table->string('cuenta_generada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
