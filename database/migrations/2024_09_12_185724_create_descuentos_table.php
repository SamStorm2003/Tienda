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
        Schema::create('descuentos', function (Blueprint $table) {
            $table->id('descuento_id'); 
            $table->string('nombre', 100); 
            $table->text('descripcion')->nullable(); 
            $table->enum('tipo', ['Porcentaje', 'Valor Fijo']); 
            $table->decimal('valor', 10, 2); 
            $table->date('fecha_inicio')->nullable(); 
            $table->date('fecha_fin')->nullable(); 
            $table->boolean('activo')->default(true); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descuentos');
    }
};
