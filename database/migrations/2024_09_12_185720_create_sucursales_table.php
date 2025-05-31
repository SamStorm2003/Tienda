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
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('sucursal_id'); 
            $table->string('nombre', 100); 
            $table->string('direccion', 255)->nullable(); 
            $table->string('ciudad', 100)->nullable();
            $table->string('telefono', 15)->nullable(); 
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP')); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
