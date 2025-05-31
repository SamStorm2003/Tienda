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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id('ingreso_id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['Pendiente', 'Pagado', 'Enviado', 'Completado', 'Cancelado'])->default('Pendiente');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('sucursal_id')->references('sucursal_id')->on('sucursales');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
