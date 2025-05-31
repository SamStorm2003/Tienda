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
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id('favorito_id'); 
            $table->unsignedBigInteger('usuario_id'); 
            $table->unsignedBigInteger('producto_id'); 
            $table->timestamp('fecha_agregado')->useCurrent(); 
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('producto_id')->references('producto_id')->on('productos')->onDelete('cascade');

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};
