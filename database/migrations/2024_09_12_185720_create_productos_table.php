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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('producto_id');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('sku', 50)->unique();
            $table->decimal('precio', 10, 2);
            $table->decimal('descuento_porcentaje', 5, 2)->default(0.0);
            $table->string('imagen_url', 255)->nullable();
            $table->foreignId('subcategoria_id')
                  ->nullable()
                  ->constrained('subcategorias', 'subcategoria_id')
                  ->onDelete('set null');
            $table->foreignId('proveedor_id')
                  ->nullable()
                  ->constrained('proveedores', 'proveedor_id') 
                  ->onDelete('set null');
            $table->enum('temporada', ['Primavera', 'Verano', 'Otoño', 'Invierno']);
            $table->enum('genero', ['Hombre', 'Mujer', 'Niño', 'Niña', 'Unisex']);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
