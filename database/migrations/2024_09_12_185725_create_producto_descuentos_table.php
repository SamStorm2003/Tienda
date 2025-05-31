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
        Schema::create('productos_descuentos', function (Blueprint $table) {
            $table->id('producto_descuento_id'); 
            $table->foreignId('producto_id')
            ->constrained('productos', 'producto_id')
            ->onDelete('cascade'); 
            $table->foreignId('descuento_id')
            ->constrained('descuentos', 'descuento_id')
            ->onDelete('cascade');      
            $table->timestamp('fecha_asignacion')->default(DB::raw('CURRENT_TIMESTAMP')); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_descuentos');
    }
};
